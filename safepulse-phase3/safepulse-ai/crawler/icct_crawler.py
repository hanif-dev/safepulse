"""
Auto-crawler for ICCT (International Centre for Counter-Terrorism).
Fetches RSS feed, downloads new PDFs, indexes into FAISS.
Runs weekly via scheduler.
"""

import hashlib
from datetime import datetime
from typing import List, Dict

import httpx
import feedparser
from loguru import logger
from sqlalchemy.orm import Session

from db import SessionLocal, RAGDocument, CrawlLog
from rag.vectorstore import get_vectorstore
from rag.chunker import chunk_text, extract_text_from_file

ICCT_FEEDS = [
    "https://icct.nl/feed/",
    "https://icct.nl/feed/?cat=publications",
]

ICCT_DOMAIN_TAGS = ["radicalization_pcve", "ai_weaponized", "separatism_sectarian"]


async def crawl_icct(db: Session) -> List[Dict]:
    results = []
    vs      = get_vectorstore()

    async with httpx.AsyncClient(timeout=30, follow_redirects=True) as client:
        for feed_url in ICCT_FEEDS:
            try:
                resp  = await client.get(feed_url)
                feed  = feedparser.parse(resp.text)

                for entry in feed.entries[:10]:
                    url     = entry.get("link", "")
                    title   = entry.get("title", "Untitled")
                    url_hash = hashlib.md5(url.encode()).hexdigest()

                    # Skip if already indexed
                    existing = db.query(RAGDocument).filter(
                        RAGDocument.url == url
                    ).first()
                    if existing:
                        continue

                    try:
                        doc_resp = await client.get(url)
                        content_type = doc_resp.headers.get("content-type", "")

                        if "pdf" in content_type:
                            text = extract_text_from_file(doc_resp.content, "doc.pdf")
                            ext  = "pdf"
                        else:
                            from bs4 import BeautifulSoup
                            soup = BeautifulSoup(doc_resp.text, "html.parser")
                            for tag in soup(["script", "style", "nav", "footer"]):
                                tag.decompose()
                            text = soup.get_text(separator=" ", strip=True)
                            ext  = "html"

                        if len(text) < 200:
                            continue

                        import uuid
                        doc_id = str(uuid.uuid4())
                        metadata = {
                            "doc_id":       doc_id,
                            "title":        title,
                            "source":       "ICCT",
                            "organization": "International Centre for Counter-Terrorism",
                            "url":          url,
                            "domain_tags":  ICCT_DOMAIN_TAGS,
                            "language":     "en",
                        }

                        chunks = chunk_text(text, metadata)
                        added  = vs.add_chunks(chunks)

                        doc = RAGDocument(
                            title        = title,
                            source       = "ICCT",
                            organization = "International Centre for Counter-Terrorism",
                            url          = url,
                            domain_tags  = ICCT_DOMAIN_TAGS,
                            language     = "en",
                            chunk_count  = added,
                            indexed_at   = datetime.utcnow(),
                        )
                        db.add(doc)

                        log = CrawlLog(
                            source     = "icct",
                            url        = url,
                            status     = "success",
                            docs_added = 1,
                        )
                        db.add(log)
                        db.commit()

                        results.append({"url": url, "title": title, "chunks": added})
                        logger.info(f"ICCT crawled: {title} ({added} chunks)")

                    except Exception as e:
                        log = CrawlLog(source="icct", url=url, status="failed", error_msg=str(e))
                        db.add(log)
                        db.commit()
                        logger.warning(f"ICCT failed {url}: {e}")

            except Exception as e:
                logger.error(f"ICCT feed error {feed_url}: {e}")

    return results
