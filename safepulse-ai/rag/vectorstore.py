"""
FAISS vector store — lightweight, no separate server needed.
Persisted to disk at FAISS_INDEX_PATH.
"""

import os
import json
import pickle
from pathlib import Path
from typing import List, Dict, Any, Optional

import faiss
import numpy as np
from loguru import logger

from rag.embeddings import get_embedder

INDEX_PATH = os.getenv("FAISS_INDEX_PATH", "./knowledge_base/faiss_index")
META_PATH  = INDEX_PATH + "_meta.pkl"


class VectorStore:
    def __init__(self):
        self.embedder   = get_embedder()
        self.index      = None
        self.metadata   = []          # list of dicts parallel to FAISS vectors
        self.dimension  = 384         # MiniLM-L12 output dim

    def load_or_create(self):
        idx_file = Path(INDEX_PATH)
        if idx_file.exists() and Path(META_PATH).exists():
            self.index    = faiss.read_index(str(idx_file))
            with open(META_PATH, "rb") as f:
                self.metadata = pickle.load(f)
            logger.info(f"Loaded FAISS index ({self.index.ntotal} vectors).")
        else:
            self.index = faiss.IndexFlatL2(self.dimension)
            self.metadata = []
            Path(INDEX_PATH).parent.mkdir(parents=True, exist_ok=True)
            logger.info("Created new FAISS index.")

    def _save(self):
        faiss.write_index(self.index, INDEX_PATH)
        with open(META_PATH, "wb") as f:
            pickle.dump(self.metadata, f)

    def add_chunks(self, chunks: List[Dict[str, Any]]) -> int:
        """Add pre-chunked text objects to the index."""
        texts = [c["content"] for c in chunks]
        embeddings = self.embedder.encode(texts, show_progress_bar=False)
        embeddings = np.array(embeddings).astype("float32")

        self.index.add(embeddings)
        self.metadata.extend(chunks)
        self._save()
        return len(chunks)

    def search(
        self,
        query: str,
        k: int = 6,
        domain_filter: Optional[str] = None,
    ) -> List[Dict[str, Any]]:
        """Semantic search — returns top-k chunks with scores."""
        if self.index.ntotal == 0:
            return []

        q_emb = self.embedder.encode([query])
        q_emb = np.array(q_emb).astype("float32")

        distances, indices = self.index.search(q_emb, min(k * 3, self.index.ntotal))

        results = []
        for dist, idx in zip(distances[0], indices[0]):
            if idx < 0 or idx >= len(self.metadata):
                continue
            meta = self.metadata[idx]
            if domain_filter and domain_filter not in meta.get("domain_tags", []):
                continue
            results.append({
                "content":  meta.get("content", ""),
                "metadata": meta,
                "score":    float(1 / (1 + dist)),
            })
            if len(results) >= k:
                break

        return results

    def hybrid_search(
        self,
        query: str,
        k: int = 6,
        domain_filter: Optional[str] = None,
    ) -> List[Dict[str, Any]]:
        """Hybrid: semantic + BM25 keyword re-ranking."""
        from rank_bm25 import BM25Okapi

        semantic = self.search(query, k=k * 2, domain_filter=domain_filter)
        if not semantic:
            return []

        corpus = [r["content"].lower().split() for r in semantic]
        bm25   = BM25Okapi(corpus)
        bm25_scores = bm25.get_scores(query.lower().split())

        for i, result in enumerate(semantic):
            result["score"] = 0.7 * result["score"] + 0.3 * float(bm25_scores[i])

        semantic.sort(key=lambda x: x["score"], reverse=True)
        return semantic[:k]

    def stats(self) -> Dict[str, Any]:
        return {
            "total_vectors": self.index.ntotal if self.index else 0,
            "total_documents": len(set(
                m.get("doc_id", "") for m in self.metadata
            )),
        }


_vs: Optional[VectorStore] = None


def get_vectorstore() -> VectorStore:
    global _vs
    if _vs is None:
        _vs = VectorStore()
    return _vs
