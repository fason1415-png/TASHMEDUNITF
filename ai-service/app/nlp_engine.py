from __future__ import annotations

import math
import re
from collections import Counter
from functools import lru_cache
from typing import Iterable

import numpy as np
from sklearn.metrics.pairwise import cosine_similarity

try:
    from sentence_transformers import SentenceTransformer
except Exception:
    SentenceTransformer = None  # type: ignore[assignment]

try:
    from transformers import pipeline
except Exception:
    pipeline = None  # type: ignore[assignment]


POSITIVE_TOKENS = {
    "uz_latn": ["yaxshi", "ajoyib", "zo'r", "rahmat", "tez"],
    "uz_cyrl": ["яхши", "ажойиб", "зур", "рахмат", "тез"],
    "ru": ["хорошо", "отлично", "спасибо", "быстро", "вежливо"],
    "en": ["good", "great", "excellent", "thanks", "fast", "kind"],
}

NEGATIVE_TOKENS = {
    "uz_latn": ["yomon", "uzoq", "navbat", "qo'pol", "sekin"],
    "uz_cyrl": ["ёмон", "узоқ", "навбат", "қўпол", "секин"],
    "ru": ["плохо", "долго", "очередь", "грубо", "медленно"],
    "en": ["bad", "long", "queue", "rude", "slow"],
}

TOXIC_TOKENS = ["ahmoq", "tentak", "идиот", "дурак", "stupid", "idiot"]

TOPIC_MAP = {
    "waiting_time": ["queue", "navbat", "очередь", "wait", "кут"],
    "communication": ["explain", "tushuntir", "объяс", "communication", "aloqa"],
    "service_quality": ["service", "xizmat", "сервис", "quality", "sifat"],
    "cleanliness": ["clean", "toza", "чист", "dirty", "iflos"],
}


def _normalize(text: str) -> str:
    text = text.lower().strip()
    text = re.sub(r"\s+", " ", text)
    return text


def _tokenize(text: str) -> list[str]:
    return re.findall(r"[a-zA-Zа-яА-ЯёЁўқғҳʼ']+", _normalize(text))


class NLPEngine:
    def __init__(self) -> None:
        self._sentiment_model = self._load_sentiment_model()
        self._embedder = self._load_embedder()

    @staticmethod
    @lru_cache(maxsize=1)
    def _load_sentiment_model():
        if pipeline is None:
            return None

        try:
            return pipeline(
                "text-classification",
                model="cardiffnlp/twitter-xlm-roberta-base-sentiment",
                return_all_scores=True,
            )
        except Exception:
            return None

    @staticmethod
    @lru_cache(maxsize=1)
    def _load_embedder():
        if SentenceTransformer is None:
            return None

        try:
            return SentenceTransformer("sentence-transformers/paraphrase-multilingual-MiniLM-L12-v2")
        except Exception:
            return None

    def analyze(self, text: str, language: str, previous_texts: Iterable[str] | None = None) -> dict:
        normalized = _normalize(text)
        tokens = _tokenize(normalized)

        sentiment_label, sentiment_score = self._sentiment(tokens, normalized, language)
        toxicity_score = self._toxicity(tokens)
        topics = self._topics(normalized)
        keywords = [word for word, _count in Counter(tokens).most_common(8)]
        summary = normalized[:240]
        duplicate_probability = self._duplicate_probability(normalized, previous_texts or [])
        coaching_suggestion = self._coaching(topics, sentiment_label)
        flags = self._flags(toxicity_score, duplicate_probability, sentiment_label, sentiment_score)

        return {
            "sentiment": {"label": sentiment_label, "score": round(float(sentiment_score), 3)},
            "toxicity": {"score": round(float(toxicity_score), 3)},
            "topics": topics,
            "keywords": keywords,
            "summary": summary,
            "coaching_suggestion": coaching_suggestion,
            "duplicate_probability": round(float(duplicate_probability), 3),
            "flags": flags,
        }

    def _sentiment(self, tokens: list[str], normalized: str, language: str) -> tuple[str, float]:
        if self._sentiment_model is not None:
            try:
                prediction = self._sentiment_model(normalized)[0]
                mapped = {
                    "LABEL_2": ("positive", 0.9),
                    "LABEL_1": ("neutral", 0.5),
                    "LABEL_0": ("negative", 0.1),
                }

                best = max(prediction, key=lambda item: item["score"])
                label, base = mapped.get(best["label"], ("neutral", 0.5))
                return label, float(best["score"]) * base
            except Exception:
                pass

        positive_hits = sum(token in POSITIVE_TOKENS.get(language, POSITIVE_TOKENS["en"]) for token in tokens)
        negative_hits = sum(token in NEGATIVE_TOKENS.get(language, NEGATIVE_TOKENS["en"]) for token in tokens)
        score = 0.5 + (positive_hits - negative_hits) * 0.12
        score = max(0.0, min(1.0, score))

        if score >= 0.65:
            return "positive", score
        if score <= 0.35:
            return "negative", score
        return "neutral", score

    def _toxicity(self, tokens: list[str]) -> float:
        toxic_hits = sum(token in TOXIC_TOKENS for token in tokens)
        if toxic_hits == 0:
            return 0.05
        return min(1.0, 0.45 + toxic_hits * 0.2)

    def _topics(self, normalized: str) -> list[str]:
        found: list[str] = []
        for topic, markers in TOPIC_MAP.items():
            if any(marker in normalized for marker in markers):
                found.append(topic)

        return found or ["general_feedback"]

    def _duplicate_probability(self, text: str, previous_texts: Iterable[str]) -> float:
        previous = [item.strip() for item in previous_texts if item and item.strip()]
        if not previous:
            return 0.0

        if self._embedder is not None:
            try:
                vectors = self._embedder.encode([text, *previous], normalize_embeddings=True)
                similarities = cosine_similarity([vectors[0]], vectors[1:])[0]
                return float(max(similarities))
            except Exception:
                pass

        target = set(_tokenize(text))
        if not target:
            return 0.0

        best = 0.0
        for item in previous:
            current = set(_tokenize(item))
            if not current:
                continue
            jaccard = len(target.intersection(current)) / len(target.union(current))
            best = max(best, jaccard)

        return best

    def _coaching(self, topics: list[str], sentiment_label: str) -> str:
        if sentiment_label == "positive":
            return "Maintain current performance and share best practices with peers."

        if "waiting_time" in topics:
            return "Improve queue communication and proactively explain waiting times to patients."
        if "communication" in topics:
            return "Use short, clear language and confirm patient understanding before closing consultation."
        if "service_quality" in topics:
            return "Review service workflow and ensure each patient receives complete attention."

        return "Focus on empathy, clarity, and structured follow-up for each complaint."

    def _flags(
        self,
        toxicity_score: float,
        duplicate_probability: float,
        sentiment_label: str,
        sentiment_score: float,
    ) -> list[dict]:
        flags: list[dict] = []

        if toxicity_score >= 0.7:
            flags.append({
                "type": "toxicity",
                "score": round(toxicity_score * 100, 2),
                "reason": "Potential abusive language detected.",
            })
        if duplicate_probability >= 0.85:
            flags.append({
                "type": "ai_anomaly",
                "score": round(duplicate_probability * 100, 2),
                "reason": "High similarity with previous submissions.",
            })
        if sentiment_label == "negative" and sentiment_score <= 0.25:
            flags.append({
                "type": "ai_anomaly",
                "score": round((1 - sentiment_score) * 100, 2),
                "reason": "Strongly negative sentiment.",
            })

        return flags


def explain_flag(fraud_score: float, toxicity_score: float, duplicate_probability: float, anomaly_signals: list[str]) -> dict:
    reasons: list[str] = []

    if fraud_score >= 60:
        reasons.append("Fraud score is above moderation threshold.")
    if toxicity_score >= 0.7:
        reasons.append("Toxicity score indicates abusive content.")
    if duplicate_probability >= 0.85:
        reasons.append("Submission is highly similar to recent submissions.")
    if anomaly_signals:
        reasons.append("Anomaly signals: " + ", ".join(anomaly_signals))

    if not reasons:
        reasons.append("No strong anomaly signals detected.")

    risk = min(100.0, max(fraud_score, toxicity_score * 100, duplicate_probability * 100))

    return {
        "risk_score": round(risk, 2),
        "reasons": reasons,
    }

