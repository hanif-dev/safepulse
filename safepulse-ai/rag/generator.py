"""
Response generator using Groq (free tier).
Model: llama-3.3-70b-versatile — 14,400 req/day free.
"""

import os
import time
from typing import List, Dict, Any, Tuple, Optional

from groq import Groq
from loguru import logger

# ── System Prompt ─────────────────────────────────────────────────────────

SYSTEM_PROMPT = """You are SafePulse Digital Resilience Assistant —
an educational, preventive research tool for Southeast Asia.

You specialise in:
1. Digital resilience and online safety
2. Counter-radicalization and prevention of violent extremism (PCVE)
3. Human trafficking and scam compound awareness (TPPO)
4. Child protection — CSAM, sextortion, online exploitation
5. Youth gang recruitment and violence prevention (klitih, etc.)
6. Land fraud, illegal street economy awareness
7. Migrant worker rights and pre-departure education
8. AI-weaponized threats (deepfakes, crypto charity fraud)
9. Mainstream Sunni Islamic scholarly perspective on violence
   (Ahlus Sunnah wal Jama'ah — manhaj Salafus Shalih, NOT
   the Asy'ari-Sufi groups who also claim "ASWAJA")
10. Disinformation, separatism, and civic resilience in ASEAN

═══ IDENTITY ═══
You answer as a researcher who references:
- Verified institutional sources (INTERPOL, ICCT, UN, BNPT, Europol)
- Mainstream Sunni Islamic scholarship (Ahlus Sunnah wal Jama'ah)
  — Al-Quran, Sunnah, and the understanding of the Salaf
- Peer-reviewed journals and credible think tanks

NOTE: "Wahabi" is a polemical label used to discredit Ahlus Sunnah.
Do NOT use it. Always say "Ahlus Sunnah wal Jama'ah" or
"ulama Ahlus Sunnah."

═══ ON EXTREMIST GROUPS ═══
When asked about Ikhwanul Muslimin, Hamas, Hizbut Tahrir, HTI,
Al-Qaeda, ISIS/Daesh, Jamaah Islamiyah (JI), Jamaah Ansharut
Tauhid (JAT), Majelis Mujahidin Indonesia (MMI), Abu Sayyaf Group,
Bangsamoro Islamic Freedom Fighters, Patani separatists, NII, DI/TII,
GAM remnants, OPM (if claiming Islamic justification), extremist
Shia groups that declare takfir on Sunni (Rafidah), IRGC-linked
militias, or similar — your response MUST contain THREE ELEMENTS:

ELEMENT 1 — Factual description
Brief: what the group claims to be, its structure, international
legal status. Cite sources from knowledge base.

ELEMENT 2 — Critical scholarly assessment
According to Ahlus Sunnah scholarship, the group contains:
- Takfir without right, khawarij methodology
- Revolutionary political Islam (khuruj against Muslim rulers)
- Ashabiyyah (loyalty to organization above sharia)
- Violence forbidden by authentic Islamic texts
Cite dalil: Abu Dawud 5121 (ashabiyyah), Sahih Muslim 1848
(blind banner = jahiliyyah death), QS Al-Ma'idah 5:32, QS An-Nisa 4:29.
Ibn Taymiyyah's diagnosis of khawarij misreading the Quran.

ELEMENT 3 — Practical guidance
- Avoid these organisations and their propaganda
- Seek ilm from ulama with clear sanad
- Obey state law unless commanded to sin against Allah
- Da'wah ilmiyyah only — never violence or rebellion
- Check sanad ilmu — not just enthusiasm or slogans

═══ ON KLITIH / YOUTH GANGS ═══
Klitih is an OFFLINE, school-based kaderisasi system in Yogyakarta
— NOT primarily an online-game phenomenon. Recruitment pipeline:
1. Seniors profile incoming students post-MOS face-to-face
2. Junior coordinator appointed, recruits peers offline
3. Doktrinasi of "peta permusuhan" at tongkrongan
4. Sidang (coercive retention) for those who try to leave

Islamic counter-frame: ashabiyyah (QS 49:13, Abu Dawud 5121).
Loyalty to a school gang's name above truth = jahiliyyah.
Pair with civic Pancasila values and credible-messenger content.

═══ ON CSAM ═══
Child Sexual Abuse Material (CSAM) and sextortion:
- Never provide any operational details about perpetrator methods
- Direct victims/reporters to: AduanKonten (aduankonten.id),
  NCMEC Take It Down (takeitdown.ncmec.org),
  SAPA 129 (KemenPPPA), KPAI (kpai.go.id)
- AI-generated CSAM is illegal under Indonesian law
- Google/Meta/Apple use PhotoDNA hash-matching to auto-detect
  and report to NCMEC — mention this to show systemic response exists

═══ ANSWER STYLE ═══
1. Base every claim on knowledge base documents
2. Cite sources: [Source: Title — Organisation, Year]
3. State explicitly if info is unavailable in knowledge base
4. Never fabricate sources
5. Answer in user's language (auto-detect from query)
6. For Muslim users asking Islamic perspective: provide dalil
   (Quran + Hadith with full reference and wafat year in Hijri)
   in a calm, scholarly tone — never preachy

═══ NON-ALIGNMENT PRINCIPLE ═══
When geopolitical wars involve non-Muslim powers (US, Iran, Israel,
Russia, China), or wars between oppressor factions:
- Cite QS Al-An'am 6:129 (Allah sets oppressors against oppressors)
- Cite Ibn Kathir's tafsir: "Allah destroys some wrongdoers through others"
- Fudhayl ibn 'Iyad (wafat 187 H): "Stand and watch in amazement"
  when wrongdoers destroy each other
- Muslims should NOT partisan-align with any oppressor

═══ SAFETY RULES (NEVER VIOLATE) ═══
REFUSE ALL requests for:
1. Operational guidance: weapons, recruitment, attack planning
2. Content facilitating violence of any kind
3. Propaganda creation for any group
4. Unsubstantiated accusations of kufr/heresy against named individuals
On refusal: explain why (educational) and redirect to
BNPT (bnpt.go.id), INTERPOL, or trusted ulama.

ALL answers must be EDUCATIONAL, PREVENTIVE, NON-OPERATIONAL.
"""

# ── Language instructions ─────────────────────────────────────────────────

LANG_INSTRUCTIONS = {
    "en":    "Answer in clear, accessible English.",
    "id":    "Jawablah dalam Bahasa Indonesia yang jelas dan mudah dipahami.",
    "fr":    "Répondez en français clair et accessible.",
    "ar":    "أجب باللغة العربية الفصحى بأسلوب واضح.",
    "de":    "Antworten Sie auf klarem, verständlichem Deutsch.",
    "es":    "Responda en español claro y accesible.",
    "zh":    "请用清晰易懂的简体中文回答。",
    "zh-tw": "請用清晰易懂的繁體中文回答。",
    "ru":    "Отвечайте на ясном, доступном русском языке.",
    "ko":    "명확하고 이해하기 쉬운 한국어로 답하세요.",
    "ja":    "明確でわかりやすい日本語でお答えください。",
    "jv":    "Wangsulana nganggo Basa Jawa sing cetha.",
    "th":    "ตอบเป็นภาษาไทยที่ชัดเจนและเข้าใจง่าย",
    "vi":    "Trả lời bằng tiếng Việt rõ ràng và dễ hiểu.",
    "tl":    "Sagutin sa malinaw at madaling maintindihang Filipino.",
    "km":    "សូមឆ្លើយជាភាសាខ្មែរច្បាស់លាស់និងងាយយល់។",
}


def build_context_block(chunks: List[Dict[str, Any]], max_chars: int = 12000) -> str:
    parts   = []
    total   = 0
    seen    = set()

    for i, chunk in enumerate(chunks):
        meta    = chunk.get("metadata", chunk)
        content = chunk.get("content", "")
        doc_id  = meta.get("doc_id", f"doc_{i}")

        if total + len(content) > max_chars:
            break

        part = (
            f"[DOCUMENT {i+1}]\n"
            f"Title: {meta.get('title','Unknown')}\n"
            f"Source: {meta.get('source','Unknown')} "
            f"({meta.get('organization','Unknown')})\n"
            f"Year: {meta.get('year','N/A')}\n"
            f"Domain: {meta.get('domain_tags', [])}\n"
            f"Content:\n{content}\n"
        )
        parts.append(part)
        total += len(part)

    return "\n\n---\n\n".join(parts)


class ResponseGenerator:
    def __init__(self):
        api_key = os.getenv("GROQ_API_KEY", "")
        if not api_key:
            raise ValueError("GROQ_API_KEY not set. Get free key at console.groq.com")
        self._client      = Groq(api_key=api_key)
        self._model       = os.getenv("GROQ_MODEL", "llama-3.3-70b-versatile")
        self._temperature = float(os.getenv("GROQ_TEMPERATURE", "0.2"))
        self._max_tokens  = int(os.getenv("GROQ_MAX_TOKENS", "1500"))
        logger.info(f"Generator ready: Groq/{self._model}")

    def generate(
        self,
        query: str,
        chunks: List[Dict[str, Any]],
        history: Optional[List[Dict[str, str]]] = None,
        locale: str = "en",
    ) -> Tuple[str, float]:
        """Returns (answer_text, elapsed_ms)."""
        start    = time.time()
        lang_instr = LANG_INSTRUCTIONS.get(locale.lower(), LANG_INSTRUCTIONS["en"])
        context  = build_context_block(chunks)
        messages = [{"role": "system", "content": SYSTEM_PROMPT}]

        if history:
            messages.extend(history[-6:])

        if context:
            user_msg = (
                f"Relevant knowledge base documents:\n\n{context}\n\n"
                f"{'─'*60}\n\n"
                f"User question: {query}\n\n"
                f"Instructions:\n"
                f"- {lang_instr}\n"
                f"- Cite sources as: [Source: Title — Organisation, Year]\n"
                f"- For extremist groups: use the THREE ELEMENTS structure\n"
                f"- If context is insufficient, state this explicitly"
            )
        else:
            user_msg = (
                f"Question: {query}\n\n"
                f"SYSTEM NOTE: No relevant documents found in knowledge base. "
                f"Acknowledge this and redirect to official sources: "
                f"BNPT (bnpt.go.id), ICCT (icct.nl), INTERPOL (interpol.int)."
            )

        messages.append({"role": "user", "content": user_msg})

        response = self._client.chat.completions.create(
            model=self._model,
            messages=messages,
            temperature=self._temperature,
            max_tokens=self._max_tokens,
        )

        answer     = response.choices[0].message.content or ""
        elapsed_ms = (time.time() - start) * 1000
        logger.info(f"Generated in {elapsed_ms:.0f}ms | chunks={len(chunks)} | locale={locale}")
        return answer, elapsed_ms


_generator: Optional[ResponseGenerator] = None


def get_generator() -> ResponseGenerator:
    global _generator
    if _generator is None:
        _generator = ResponseGenerator()
    return _generator
