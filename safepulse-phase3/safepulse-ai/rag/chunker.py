"""
Text chunking with overlap.
Supports plain text, PDF (via pdfplumber), DOCX (via python-docx).
"""

import os
import io
from pathlib import Path
from typing import List, Dict, Any

CHUNK_SIZE    = int(os.getenv("CHUNK_SIZE", "500"))
CHUNK_OVERLAP = int(os.getenv("CHUNK_OVERLAP", "50"))


def chunk_text(
    text: str,
    metadata: Dict[str, Any],
) -> List[Dict[str, Any]]:
    """Split text into overlapping chunks."""
    words  = text.split()
    chunks = []
    step   = CHUNK_SIZE - CHUNK_OVERLAP

    for i, start in enumerate(range(0, len(words), step)):
        chunk_words = words[start : start + CHUNK_SIZE]
        if not chunk_words:
            break
        content = " ".join(chunk_words)
        chunks.append({
            **metadata,
            "content":  content,
            "chunk_idx": i,
        })

    return chunks


def extract_text_from_file(file_bytes: bytes, filename: str) -> str:
    """Extract plain text from PDF, DOCX, or TXT."""
    suffix = Path(filename).suffix.lower()

    if suffix == ".pdf":
        import pdfplumber
        with pdfplumber.open(io.BytesIO(file_bytes)) as pdf:
            return "\n".join(
                page.extract_text() or "" for page in pdf.pages
            )

    if suffix in (".docx", ".doc"):
        import docx
        doc = docx.Document(io.BytesIO(file_bytes))
        return "\n".join(p.text for p in doc.paragraphs)

    if suffix in (".txt", ".md"):
        return file_bytes.decode("utf-8", errors="replace")

    raise ValueError(f"Unsupported file type: {suffix}")
