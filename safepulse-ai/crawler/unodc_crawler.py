"""
Auto-crawler for UNODC (United Nations Office on Drugs and Crime).
Targets publications relevant to trafficking, scam compounds, and cybercrime.
"""

import uuid
from datetime import datetime
from typing import List, Dict

import httpx
from bs4 import BeautifulSoup
from loguru import logger
from sqlalchemy.orm import Session

from db import SessionLocal, RAGDocument, CrawlLog
from rag.vectorstore import get_vectorstore
from rag.chunker import chunk_text, extract_text_from_file

UNODC_URLS = [
    "https://www.unodc.org/unodc/en/human-trafficking/publications.html",
    "https://www.unodc.org/unodc/en/cybercrime/publications.html",
]

UNODC_DOMAIN_TAGS = ["tppo", "money_laundering", "cyberbullying_csam"]


async def crawl_unodc(db: Session) -> List[Dict]:
    results = []
    vs      = get_vectorstore()

    async with httpx.AsyncClient(timeout=30, follow_redirects=True) as client:
        for page_url in UNODC_URLS:
            try:
                resp = await client.get(page_url)
                soup = BeautifulSoup(resp.text, "html.parser")

                pdf_links = [
                    a["href"] for a in soup.find_all("a", href=True)
                    if a["href"].endswith(".pdf")
                ][:5]

                for pdf_url in pdf_links:
                    if not pdf_url.startswith("http"):
                        pdf_url = "https://www.unodc.org" + pdf_url

                    existing = db.query(RAGDocument).filter(
                        RAGDocument.url == pdf_url
                    ).first()
                    if existing:
                        continue

                    try:
                        pdf_resp = await client.get(pdf_url)
                        text     = extract_text_from_file(pdf_resp.content, "doc.pdf")

                        if len(text) < 200:
                            continue

                        title   = pdf_url.split("/")[-1].replace(".pdf", "").replace("-", " ").title()
                        doc_id  = str(uuid.uuid4())
                        metadata = {
                            "doc_id":       doc_id,
                            "title":        title,
                            "source":       "UNODC",
                            "organization": "United Nations Office on Drugs and Crime",
                            "url":          pdf_url,
                            "domain_tags":  UNODC_DOMAIN_TAGS,
                            "language":     "en",
                        }

                        chunks = chunk_text(text, metadata)
                        added  = vs.add_chunks(chunks)

                        doc = RAGDocument(
                            title        = title,
                            source       = "UNODC",
                            organization = "United Nations Office on Drugs and Crime",
                            url          = pdf_url,
                            domain_tags  = UNODC_DOMAIN_TAGS,
                            language     = "en",
                            chunk_count  = added,
                            indexed_at   = datetime.utcnow(),
                        )
                        db.add(doc)

                        log = CrawlLog(source="unodc", url=pdf_url, status="success", docs_added=1)
                        db.add(log)
                        db.commit()

                        results.append({"url": pdf_url, "title": title, "chunks": added})
                        logger.info(f"UNODC crawled: {title} ({added} chunks)")

                    except Exception as e:
                        log = CrawlLog(source="unodc", url=pdf_url, status="failed", error_msg=str(e))
                        db.add(log)
                        db.commit()
                        logger.warning(f"UNODC failed {pdf_url}: {e}")

            except Exception as e:
                logger.error(f"UNODC page error {page_url}: {e}")

    return results
