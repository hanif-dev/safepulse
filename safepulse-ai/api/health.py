from fastapi import APIRouter
from rag.vectorstore import get_vectorstore

router = APIRouter()


@router.get("/health")
async def health():
    vs    = get_vectorstore()
    stats = vs.stats()
    return {
        "status":         "ok",
        "service":        "SafePulse Digital Resilience Assistant",
        "version":        "3.0.0",
        "total_vectors":  stats["total_vectors"],
        "total_documents": stats["total_documents"],
    }
