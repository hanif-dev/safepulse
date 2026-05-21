"""
SafePulse Phase 3 — Digital Resilience Assistant
FastAPI service running on port 8001
"""

import os
from contextlib import asynccontextmanager
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from loguru import logger
from dotenv import load_dotenv

load_dotenv()

from api.chat import router as chat_router
from api.ingest import router as ingest_router
from api.sources import router as sources_router
from api.health import router as health_router
from rag.vectorstore import get_vectorstore
from db import init_db


@asynccontextmanager
async def lifespan(app: FastAPI):
    """Startup: init DB tables and load FAISS index."""
    logger.info("SafePulse AI Service starting...")
    init_db()
    vs = get_vectorstore()
    vs.load_or_create()
    logger.info(f"FAISS index loaded — {vs.index.ntotal} vectors")

    # Start background crawler scheduler
    from crawler.scheduler import start_scheduler
    start_scheduler()

    yield

    logger.info("SafePulse AI Service shutting down.")


app = FastAPI(
    title="SafePulse Digital Resilience Assistant",
    description="RAG-powered counter-radicalization and digital resilience AI for Southeast Asia",
    version="3.0.0",
    lifespan=lifespan,
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(health_router,  prefix="/ai")
app.include_router(chat_router,    prefix="/ai")
app.include_router(ingest_router,  prefix="/ai")
app.include_router(sources_router, prefix="/ai")
