"""
POST /ai/ingest        — upload file and index
POST /ai/ingest/url    — fetch URL and index
GET  /ai/crawl/run     — trigger manual crawl
GET  /ai/crawl/logs    — crawl history
"""

import os
import uuid
from datetime import datetime
from typing import List, Optional

from fastapi import APIRouter, File, UploadFile, Form, HTTPException, Header, Depends
from pydantic import BaseModel
from sqlalchemy.orm import Session
from loguru import logger

from db import get_db, RAGDocument, CrawlLog
from rag.vectorstore import get_vectorstore
from rag.chunker import chunk_text, extract_text_from_file

router = APIRouter()


def require_admin(x_admin_token: str = Header(None)):
    expected = os.getenv("ADMIN_TOKEN", "")
    if not expected or x_admin_token != expected:
        raise HTTPException(status_code=401, detail="Admin token required.")


@router.post("/ingest")
async def ingest_file(
    file:          UploadFile = File(...),
    title:         str  = Form(...),
    source:        str  = Form("Unknown"),
    organization:  str  = Form("Unknown"),
    year:          Optional[int] = Form(None),
    url:           Optional[str] = Form(None),
    domain_tags:   str  = Form("[]"),       # JSON string
    language:      str  = Form("en"),
    sunni_scholarly: bool = Form(False),
    db:            Session = Depends(get_db),
    _:             None = Depends(require_admin),
):
    import json

    content     = await file.read()
    text        = extract_text_from_file(content, file.filename)
    tags        = json.loads(domain_tags)
    doc_id      = str(uuid.uuid4())

    metadata = {
        "doc_id":         doc_id,
        "title":          title,
        "source":         source,
        "organization":   organization,
        "year":           year,
        "url":            url,
        "domain_tags":    tags,
        "language":       language,
        "sunni_scholarly": sunni_scholarly,
    }

    chunks     = chunk_text(text, metadata)
    vs         = get_vectorstore()
    added      = vs.add_chunks(chunks)

    doc = RAGDocument(
        title          = title,
        source         = source,
        organization   = organization,
        year           = year,
        url            = url,
        file_path      = file.filename,
        domain_tags    = tags,
        language       = language,
        sunni_scholarly = sunni_scholarly,
        chunk_count    = added,
        indexed_at     = datetime.utcnow(),
    )
    db.add(doc)
    db.commit()

    logger.info(f"Ingested '{title}' — {added} chunks.")
    return {"title": title, "chunks_added": added, "doc_id": doc_id}


class URLIngestRequest(BaseModel):
    url:           str
    title:         str
    source:        str = "Unknown"
    organization:  str = "Unknown"
    year:          Optional[int] = None
    domain_tags:   List[str] = []
    language:      str = "en"
    sunni_scholarly: bool = False


@router.post("/ingest/url")
async def ingest_url(
    req: URLIngestRequest,
    db:  Session = Depends(get_db),
    _:   None = Depends(require_admin),
):
    import httpx
    from bs4 import BeautifulSoup

    async with httpx.AsyncClient(timeout=30) as client:
        resp = await client.get(req.url, follow_redirects=True)
        resp.raise_for_status()

    content_type = resp.headers.get("content-type", "")
    if "pdf" in content_type:
        text = extract_text_from_file(resp.content, "doc.pdf")
    else:
        soup = BeautifulSoup(resp.text, "html.parser")
        for tag in soup(["script", "style", "nav", "footer"]):
            tag.decompose()
        text = soup.get_text(separator=" ", strip=True)

    doc_id = str(uuid.uuid4())
    metadata = {
        "doc_id":          doc_id,
        "title":           req.title,
        "source":          req.source,
        "organization":    req.organization,
        "year":            req.year,
        "url":             req.url,
        "domain_tags":     req.domain_tags,
        "language":        req.language,
        "sunni_scholarly": req.sunni_scholarly,
    }

    chunks = chunk_text(text, metadata)
    vs     = get_vectorstore()
    added  = vs.add_chunks(chunks)

    doc = RAGDocument(
        title          = req.title,
        source         = req.source,
        organization   = req.organization,
        year           = req.year,
        url            = req.url,
        domain_tags    = req.domain_tags,
        language       = req.language,
        sunni_scholarly = req.sunni_scholarly,
        chunk_count    = added,
        indexed_at     = datetime.utcnow(),
    )
    db.add(doc)
    db.commit()

    return {"title": req.title, "chunks_added": added, "doc_id": doc_id}


@router.get("/crawl/run")
async def manual_crawl(
    db: Session = Depends(get_db),
    _:  None = Depends(require_admin),
):
    from crawler.icct_crawler  import crawl_icct
    from crawler.unodc_crawler import crawl_unodc

    results = []
    results += await crawl_icct(db)
    results += await crawl_unodc(db)
    return {"crawled": results}


@router.get("/crawl/logs")
async def crawl_logs(
    limit: int = 50,
    db:    Session = Depends(get_db),
    _:     None = Depends(require_admin),
):
    logs = db.query(CrawlLog).order_by(
        CrawlLog.ran_at.desc()
    ).limit(limit).all()
    return [
        {
            "source":     l.source,
            "url":        l.url,
            "status":     l.status,
            "docs_added": l.docs_added,
            "error":      l.error_msg,
            "ran_at":     l.ran_at.isoformat(),
        }
        for l in logs
    ]
