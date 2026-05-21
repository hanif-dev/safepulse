"""
GET /ai/sources       — list indexed documents
GET /ai/sources/{id}  — document detail
"""

from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session

from db import get_db, RAGDocument
from rag.vectorstore import get_vectorstore

router = APIRouter()


@router.get("/sources")
async def list_sources(
    limit:     int = 50,
    language:  str = None,
    domain:    str = None,
    db:        Session = Depends(get_db),
):
    q = db.query(RAGDocument)
    if language:
        q = q.filter(RAGDocument.language == language)
    if domain:
        q = q.filter(RAGDocument.domain_tags.contains(domain))

    docs = q.order_by(RAGDocument.indexed_at.desc()).limit(limit).all()
    return {
        "total":     db.query(RAGDocument).count(),
        "documents": [
            {
                "id":           d.id,
                "title":        d.title,
                "source":       d.source,
                "organization": d.organization,
                "year":         d.year,
                "url":          d.url,
                "domain_tags":  d.domain_tags,
                "language":     d.language,
                "chunk_count":  d.chunk_count,
                "indexed_at":   d.indexed_at.isoformat() if d.indexed_at else None,
            }
            for d in docs
        ],
    }


@router.get("/sources/{doc_id}")
async def get_source(doc_id: int, db: Session = Depends(get_db)):
    doc = db.query(RAGDocument).filter(RAGDocument.id == doc_id).first()
    if not doc:
        from fastapi import HTTPException
        raise HTTPException(status_code=404, detail="Document not found.")
    return {
        "id":            doc.id,
        "title":         doc.title,
        "source":        doc.source,
        "organization":  doc.organization,
        "year":          doc.year,
        "url":           doc.url,
        "domain_tags":   doc.domain_tags,
        "language":      doc.language,
        "chunk_count":   doc.chunk_count,
        "sunni_scholarly": doc.sunni_scholarly,
        "indexed_at":    doc.indexed_at.isoformat() if doc.indexed_at else None,
    }
