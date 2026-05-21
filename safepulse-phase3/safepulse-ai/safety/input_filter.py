"""
Input safety filter — block operational/dangerous requests before
they reach the LLM. Educational, preventive use only.
"""

import re
from typing import Tuple

# Patterns that must be refused regardless of context
BLOCKED_PATTERNS = [
    r"cara\s+(membuat|merakit|membeli)\s+(bom|senjata|racun|peledak)",
    r"how\s+to\s+(make|build|buy|create)\s+(bomb|weapon|explosive|poison)",
    r"cara\s+merekrut\s+(anggota|member)\s+(isis|jemaah|kelompok|geng)",
    r"cara\s+menyebarkan\s+(propaganda|konten\s+radikal)",
    r"cara\s+(menghindari|lolos\s+dari)\s+(polisi|intelijen|deteksi)",
    r"panduan\s+(serangan|pengeboman|pembunuhan|teror)",
    r"step.by.step\s+(attack|bomb|recruit|radicali)",
    r"bagaimana\s+cara\s+(menyerang|membunuh|mengebom)",
    r"كيف\s+(أصنع|أصنع|أفجر|أفجر)",                   # Arabic
    r"วิธีทำ\s*(ระเบิด|อาวุธ)",                          # Thai
    r"cách\s+(làm|chế\s+tạo)\s+(bom|vũ\s+khí)",         # Vietnamese
]

# Patterns that redirect without refusal (sensitive but educational)
SENSITIVE_PATTERNS = [
    r"csam|child\s+abuse\s+material|materi\s+pelecehan\s+anak",
    r"sextortion|pemerasan\s+seksual",
    r"klitih\s+(cara\s+bergabung|cara\s+masuk)",
]

# Safe refusal message template
REFUSAL_TEMPLATE = {
    "id": (
        "Maaf, saya tidak dapat memberikan informasi tersebut karena "
        "SafePulse hanya menyediakan konten yang bersifat edukatif dan "
        "preventif — bukan panduan operasional. Untuk melaporkan "
        "ancaman, silakan hubungi:\n"
        "- BNPT: bnpt.go.id · 021-7972962\n"
        "- Patrolisiber: patrolisiber.id\n"
        "- INTERPOL: interpol.int"
    ),
    "en": (
        "I cannot provide that information. SafePulse provides only "
        "educational and preventive content — not operational guidance. "
        "To report a threat, contact:\n"
        "- BNPT: bnpt.go.id · +62-21-7972962\n"
        "- Patrolisiber: patrolisiber.id\n"
        "- INTERPOL: interpol.int"
    ),
    "ar": (
        "لا أستطيع تقديم هذه المعلومات. يقدم SafePulse محتوى تعليمياً "
        "ووقائياً فقط. للإبلاغ عن تهديد، تواصل مع: "
        "BNPT أو INTERPOL."
    ),
}


def check_input(text: str, locale: str = "en") -> Tuple[bool, str]:
    """
    Returns (is_safe, refusal_message_or_empty).
    If is_safe=False, include the refusal message in the response.
    """
    lower = text.lower()

    for pattern in BLOCKED_PATTERNS:
        if re.search(pattern, lower, re.IGNORECASE):
            lang = locale[:2].lower()
            msg  = REFUSAL_TEMPLATE.get(lang, REFUSAL_TEMPLATE["en"])
            return False, msg

    return True, ""


def check_output(text: str) -> bool:
    """
    Verify LLM output does not accidentally contain operational content.
    Returns True if safe.
    """
    danger_phrases = [
        "langkah-langkah untuk menyerang",
        "cara membuat bom",
        "step by step to build",
        "how to recruit members for",
        "bomb-making instructions",
        "cara merekrut anggota teroris",
    ]
    lower = text.lower()
    return not any(p in lower for p in danger_phrases)
