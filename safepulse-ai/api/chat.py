"""
POST /ai/session/start   — create anonymous conversation
POST /ai/chat            — send a message, get RAG response
GET  /ai/session/{token}/history
DELETE /ai/session/{token}
"""

import os
import uuid
from datetime import datetime, timedelta
from typing import Optional

from fastapi import APIRouter, HTTPException, Depends
from pydantic import BaseModel
from sqlalchemy.orm import Session
from loguru import logger

from db import get_db, AIConversation, AIMessage
from rag.vectorstore import get_vectorstore
from rag.generator import get_generator
from safety.input_filter import check_input, check_output

router = APIRouter()

SESSION_TTL = int(os.getenv("SESSION_TTL_DAYS", "7"))


# ── Schemas ───────────────────────────────────────────────────────────────

class SessionStartRequest(BaseModel):
    locale:      str = "en"
    domain_hint: Optional[str] = None


class ChatRequest(BaseModel):
    session_token: str
    message:       str
    locale:        Optional[str] = None       # override session locale


class SessionStartResponse(BaseModel):
    session_token: str
    expires_at:    str


# ── Endpoints ─────────────────────────────────────────────────────────────

@router.post("/session/start", response_model=SessionStartResponse)
async def start_session(req: SessionStartRequest, db: Session = Depends(get_db)):
    """Create an anonymous conversation session."""
    token = uuid.uuid4().hex
    conv  = AIConversation(
        id            = str(uuid.uuid4()),
        session_token = token,
        locale        = req.locale,
        domain_hint   = req.domain_hint,
        expires_at    = datetime.utcnow() + timedelta(days=SESSION_TTL),
    )
    db.add(conv)
    db.commit()
    return {"session_token": token, "expires_at": conv.expires_at.isoformat()}


@router.post("/chat")
async def chat(req: ChatRequest, db: Session = Depends(get_db)):
    """Main RAG chat endpoint."""

    # 1. Resolve session
    conv = db.query(AIConversation).filter(
        AIConversation.session_token == req.session_token,
        AIConversation.expires_at   > datetime.utcnow(),
    ).first()
    if not conv:
        raise HTTPException(status_code=401, detail="Invalid or expired session token.")

    locale = req.locale or conv.locale or "en"

    # 2. Safety check
    is_safe, refusal = check_input(req.message, locale)
    if not is_safe:
        _save_message(db, conv.id, "user", req.message, safety_flagged=True)
        _save_message(db, conv.id, "assistant", refusal)
        return {
            "answer":       refusal,
            "sources":      [],
            "safety_flagged": True,
            "powered_by":   "SafePulse Safety Filter",
        }

    # 3. Retrieve relevant chunks
    vs     = get_vectorstore()
    chunks = vs.hybrid_search(
        query=req.message,
        k=6,
        domain_filter=conv.domain_hint,
    )

    # 4. Build conversation history (last 6 turns)
    past = db.query(AIMessage).filter(
        AIMessage.conversation_id == conv.id,
        AIMessage.safety_flagged  == False,
    ).order_by(AIMessage.id.desc()).limit(12).all()

    history = [
        {"role": m.role, "content": m.content}
        for m in reversed(past)
    ]

    # 5. Generate answer
    gen          = get_generator()
    answer, ms   = gen.generate(req.message, chunks, history, locale)

    # 6. Output safety check
    if not check_output(answer):
        logger.warning("Output safety filter triggered — replacing with refusal.")
        answer = (
            "I'm sorry, I cannot provide that information. "
            "For guidance, please contact BNPT (bnpt.go.id) or INTERPOL (interpol.int)."
        )

    # 7. Extract source citations
    sources = [
        {
            "title":        c.get("metadata", c).get("title", "Unknown"),
            "organization": c.get("metadata", c).get("organization", ""),
            "year":         c.get("metadata", c).get("year"),
            "url":          c.get("metadata", c).get("url"),
            "excerpt":      c.get("content", "")[:200] + "...",
            "score":        round(c.get("score", 0), 3),
        }
        for c in chunks[:4]
    ]

    # 8. Persist messages
    _save_message(db, conv.id, "user", req.message)
    _save_message(db, conv.id, "assistant", answer, sources=sources)

    return {
        "answer":         answer,
        "sources":        sources,
        "safety_flagged": False,
        "response_ms":    round(ms),
        "powered_by":     f"Groq llama-3.3-70b + FAISS + SafePulse Safety Filter",
    }


@router.get("/session/{token}/history")
async def get_history(token: str, db: Session = Depends(get_db)):
    conv = db.query(AIConversation).filter(
        AIConversation.session_token == token,
        AIConversation.expires_at   > datetime.utcnow(),
    ).first()
    if not conv:
        raise HTTPException(status_code=404, detail="Session not found.")

    messages = db.query(AIMessage).filter(
        AIMessage.conversation_id == conv.id,
    ).order_by(AIMessage.id.asc()).all()

    return {
        "session_token": token,
        "locale":        conv.locale,
        "messages": [
            {
                "role":    m.role,
                "content": m.content,
                "sources": m.sources,
                "at":      m.created_at.isoformat(),
            }
            for m in messages if not m.safety_flagged
        ],
    }


@router.delete("/session/{token}")
async def delete_session(token: str, db: Session = Depends(get_db)):
    conv = db.query(AIConversation).filter(
        AIConversation.session_token == token
    ).first()
    if conv:
        db.query(AIMessage).filter(
            AIMessage.conversation_id == conv.id
        ).delete()
        db.delete(conv)
        db.commit()
    return {"deleted": True}


def _save_message(
    db: Session,
    conversation_id: str,
    role: str,
    content: str,
    sources: list = None,
    safety_flagged: bool = False,
):
    msg = AIMessage(
        conversation_id = conversation_id,
        role            = role,
        content         = content,
        sources         = sources,
        safety_flagged  = safety_flagged,
    )
    db.add(msg)
    db.commit()
