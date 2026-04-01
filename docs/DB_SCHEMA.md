# Database Schema (MVP)

## Core Tenant Structure
- `clinics`
- `branches` -> `clinics`
- `departments` -> `clinics`, `branches`
- `doctors` -> `clinics`, `branches`, `departments`
- `doctor_profiles` -> `clinics`, `doctors`
- `service_points` -> `clinics`, `branches`, `departments`
- `users` -> `clinics`, `branches`, `doctors`

## QR and Survey Definitions
- `qr_codes` -> `clinics`, `branches`, `departments`, `doctors`, `service_points`
- `surveys` -> `clinics`
- `survey_questions` -> `clinics`, `surveys`
- `survey_options` -> `clinics`, `survey_questions`

## Survey Execution and AI
- `survey_responses` -> `clinics`, `branches`, `departments`, `doctors`, `service_points`, `qr_codes`, `surveys`
- `survey_answers` -> `clinics`, `survey_responses`, `survey_questions`
- `comment_analysis` -> `clinics`, `survey_responses`
- `suspicious_flags` -> `clinics`, `survey_responses`, `users(reviewer)`
- `qr_scan_events` -> `clinics`, `qr_codes`, `survey_responses`

## Ratings, Incentives, Escalation
- `rating_snapshots` -> `clinics`, `branches`, `departments`, `doctors`
- `reward_rules` -> `clinics`, `users(creator)`
- `rewards` -> `clinics`, `reward_rules`, `rating_snapshots`, `doctors`, `branches`, `departments`, `users(approver)`
- `escalations` -> `clinics`, `survey_responses`, `doctors`, `branches`, `departments`, `users(assignee)`

## Billing and Governance
- `subscriptions` -> `clinics`, `users(creator)`
- `invoices` -> `clinics`, `subscriptions`
- `audit_logs` -> `clinics`, `users`
- `language_strings` -> `clinics`
- `roles/permissions` (Spatie package tables)

## Critical Survey Response Fields
- `channel`: `qr|shortlink|kiosk|telegram|sms`
- `language`: `uz_latn|uz_cyrl|ru|en`
- `ip_hash`, `device_hash`, `fingerprint_hash`
- `fraud_score`, `anomaly_score`
- `sentiment_score`, `severity_score`
- `quality_score`, `confidence_score`
- `is_flagged`, `moderation_status`

## Scoring Notes
- Weighted score dimensions are stored in `clinics.scoring_weights` JSON.
- Public visibility is gated by `clinics.min_public_samples`.
- Confidence-adjusted score is stored in `rating_snapshots.confidence_adjusted_score`.

