from __future__ import annotations

from fastapi import FastAPI

from .nlp_engine import NLPEngine, explain_flag
from .schemas import (
    AnalyzeRequest,
    AnalyzeResponse,
    BatchAnalyzeRequest,
    CoachRequest,
    ExplainFlagRequest,
)

app = FastAPI(
    title="ShifoReyting AI Service",
    version="1.0.0",
    description="NLP microservice for sentiment, topic extraction, toxicity, and anomaly explanations.",
)

engine = NLPEngine()


@app.get("/health")
def health() -> dict:
    return {"status": "ok", "service": "shiforeyting-ai", "version": "1.0.0"}


@app.post("/analyze", response_model=AnalyzeResponse)
def analyze(payload: AnalyzeRequest) -> dict:
    return engine.analyze(
        text=payload.text,
        language=payload.language,
        previous_texts=payload.previous_texts,
    )


@app.post("/batch")
def batch_analyze(payload: BatchAnalyzeRequest) -> dict:
    results = [engine.analyze(item.text, item.language, item.previous_texts) for item in payload.items]
    return {"items": results, "count": len(results)}


@app.post("/coach")
def coach(payload: CoachRequest) -> dict:
    themes = {theme.lower() for theme in payload.themes}

    if "waiting_time" in themes:
        suggestion = "Set clear wait-time expectations and provide proactive status updates."
    elif "communication" in themes:
        suggestion = "Use simple language and ask patients to repeat key instructions."
    elif "service_quality" in themes:
        suggestion = "Standardize appointment workflow and reinforce consultation checklist."
    else:
        suggestion = "Review repeated negative patterns and run targeted peer coaching sessions."

    return {
        "coaching_suggestion": suggestion,
        "themes": sorted(themes),
        "language": payload.language,
    }


@app.post("/explain-flag")
def explain_flag_endpoint(payload: ExplainFlagRequest) -> dict:
    return explain_flag(
        fraud_score=payload.fraud_score,
        toxicity_score=payload.toxicity_score,
        duplicate_probability=payload.duplicate_probability,
        anomaly_signals=payload.anomaly_signals,
    )

