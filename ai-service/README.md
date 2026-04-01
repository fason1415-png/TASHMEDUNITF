# ShifoReyting AI Microservice

FastAPI service for NLP analysis used by Laravel queue jobs.

## Run locally

```bash
cd ai-service
python -m venv .venv
source .venv/bin/activate  # or .venv\Scripts\activate on Windows
pip install -r requirements.txt
uvicorn app.main:app --host 0.0.0.0 --port 8001
```

## Endpoints

- `GET /health`
- `POST /analyze`
- `POST /batch`
- `POST /coach`
- `POST /explain-flag`

## Example analyze request

```json
{
  "text": "Doctor was polite but waiting was too long",
  "language": "en",
  "previous_texts": [
    "Waiting time was too long"
  ]
}
```

