from __future__ import annotations

from pydantic import BaseModel, Field


class AnalyzeRequest(BaseModel):
    text: str = Field(..., min_length=1, max_length=5000)
    language: str = Field(default="uz_latn")
    previous_texts: list[str] = Field(default_factory=list)


class SentimentPayload(BaseModel):
    label: str
    score: float


class ToxicityPayload(BaseModel):
    score: float


class AnalyzeResponse(BaseModel):
    sentiment: SentimentPayload
    toxicity: ToxicityPayload
    topics: list[str]
    keywords: list[str]
    summary: str
    coaching_suggestion: str
    duplicate_probability: float
    flags: list[dict]


class BatchAnalyzeRequest(BaseModel):
    items: list[AnalyzeRequest]


class CoachRequest(BaseModel):
    themes: list[str]
    language: str = "uz_latn"


class ExplainFlagRequest(BaseModel):
    fraud_score: float = 0
    toxicity_score: float = 0
    duplicate_probability: float = 0
    anomaly_signals: list[str] = Field(default_factory=list)

