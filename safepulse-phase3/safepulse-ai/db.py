"""
Database connection using the same MySQL instance as Laravel.
Tables prefixed with ai_ to avoid conflicts.
"""

import os
from datetime import datetime, timedelta
from sqlalchemy import (
    create_engine, Column, String, Text, Boolean,
    Integer, DateTime, Float, JSON, BigInteger,
)
from sqlalchemy.orm import declarative_base, sessionmaker
from loguru import logger

DB_URL = (
    f"mysql+pymysql://{os.getenv('DB_USER','safepulse')}:"
    f"{os.getenv('DB_PASS','secret')}@"
    f"{os.getenv('DB_HOST','127.0.0.1')}:"
    f"{os.getenv('DB_PORT','3306')}/"
    f"{os.getenv('DB_NAME','safepulse')}?charset=utf8mb4"
)

engine = create_engine(DB_URL, pool_pre_ping=True, pool_recycle=3600)
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base = declarative_base()


# ── Models ────────────────────────────────────────────────────────────────

class AIConversation(Base):
    __tablename__ = "ai_conversations"

    id             = Column(String(36), primary_key=True)
    session_token  = Column(String(64), unique=True, nullable=False, index=True)
    locale         = Column(String(8), default="en")
    domain_hint    = Column(String(32), nullable=True)
    created_at     = Column(DateTime, default=datetime.utcnow)
    expires_at     = Column(DateTime, nullable=False)


class AIMessage(Base):
    __tablename__ = "ai_messages"

    id              = Column(BigInteger, primary_key=True, autoincrement=True)
    conversation_id = Column(String(36), nullable=False, index=True)
    role            = Column(String(16), nullable=False)          # user | assistant
    content         = Column(Text, nullable=False)
    sources         = Column(JSON, nullable=True)
    safety_flagged  = Column(Boolean, default=False)
    created_at      = Column(DateTime, default=datetime.utcnow)


class RAGDocument(Base):
    __tablename__ = "rag_documents"

    id              = Column(BigInteger, primary_key=True, autoincrement=True)
    title           = Column(String(500))
    source          = Column(String(200))
    organization    = Column(String(200))
    year            = Column(Integer, nullable=True)
    url             = Column(String(1000), nullable=True)
    file_path       = Column(String(500), nullable=True)
    domain_tags     = Column(JSON)
    language        = Column(String(8), default="en")
    sunni_scholarly = Column(Boolean, default=False)
    chunk_count     = Column(Integer, default=0)
    indexed_at      = Column(DateTime, nullable=True)
    created_at      = Column(DateTime, default=datetime.utcnow)


class CrawlLog(Base):
    __tablename__ = "crawl_logs"

    id         = Column(BigInteger, primary_key=True, autoincrement=True)
    source     = Column(String(50))
    url        = Column(String(1000))
    status     = Column(String(20))          # success | failed | skipped
    docs_added = Column(Integer, default=0)
    error_msg  = Column(Text, nullable=True)
    ran_at     = Column(DateTime, default=datetime.utcnow)


def init_db():
    """Create tables if not exist."""
    try:
        Base.metadata.create_all(bind=engine)
        logger.info("Database tables ready.")
    except Exception as e:
        logger.error(f"DB init failed: {e}")
        raise


def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()


def purge_expired_sessions():
    """Remove expired conversations and their messages."""
    db = SessionLocal()
    try:
        expired = db.query(AIConversation).filter(
            AIConversation.expires_at < datetime.utcnow()
        ).all()
        for conv in expired:
            db.query(AIMessage).filter(
                AIMessage.conversation_id == conv.id
            ).delete()
            db.delete(conv)
        db.commit()
        logger.info(f"Purged {len(expired)} expired sessions.")
    finally:
        db.close()
