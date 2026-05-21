"""
Local embedding model — no API cost.
paraphrase-multilingual-MiniLM-L12-v2 supports 50+ languages
including Indonesian, Arabic, Thai, Vietnamese, Khmer.
"""

import os
from sentence_transformers import SentenceTransformer
from loguru import logger

_model = None


def get_embedder() -> SentenceTransformer:
    global _model
    if _model is None:
        model_name = os.getenv(
            "EMBEDDING_MODEL",
            "paraphrase-multilingual-MiniLM-L12-v2"
        )
        logger.info(f"Loading embedding model: {model_name}")
        _model = SentenceTransformer(model_name)
        logger.info("Embedding model ready.")
    return _model
