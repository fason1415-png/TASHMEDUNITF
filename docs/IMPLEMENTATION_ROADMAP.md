# ShifoReyting AI MVP Roadmap

## Phase 1 - Foundation
1. Initialize Laravel 12 project and install core packages:
   - Filament (admin panel)
   - Spatie permissions
   - Redis (predis)
   - simple-qrcode
   - Laravel Excel
   - DomPDF
2. Configure multilingual support and tenant context middleware.
3. Configure queue/cache defaults for Redis.

## Phase 2 - Domain and Data Model
1. Build migrations for all required tables:
   clinics, branches, departments, doctors, doctor_profiles, service_points,
   qr_codes, surveys, survey_questions, survey_options, survey_responses, survey_answers,
   comment_analysis, suspicious_flags, rating_snapshots, rewards, reward_rules,
   escalations, subscriptions, invoices, audit_logs, language_strings, qr_scan_events.
2. Build Eloquent models with UUID generation and relationships.
3. Add tenant-aware model behavior and user role integration.

## Phase 3 - Patient Survey Flow
1. QR landing page with multilingual switch and simple mobile UX.
2. Anonymous response submission with optional callback request.
3. Fraud checks on submission:
   - IP hash
   - Device hash
   - Fingerprint hash
   - Burst and duplicate patterns
4. Queue AI job for sentiment/topic/toxicity and explainable flags.

## Phase 4 - AI and Scoring
1. Build FastAPI microservice for NLP:
   - `/analyze`
   - `/batch`
   - `/coach`
   - `/explain-flag`
2. Weighted scoring engine with clinic-level configuration.
3. Confidence-adjusted scoring and minimum sample-size rule.
4. Automatic escalation creation for critical feedback.

## Phase 5 - Admin Operations
1. Filament resources for operational modules:
   clinics, branches, departments, doctors, service points, QR, surveys, responses, flags,
   rewards, escalations, subscriptions, invoices, users.
2. Dashboard widgets:
   - real-time KPIs
   - 30-day trend chart
   - critical complaint table
3. Export center page with Excel and PDF downloads.

## Phase 6 - Production Readiness
1. Seed realistic demo data for two clinics and all roles.
2. Add feature tests for submission flow and widget threshold logic.
3. Add Ubuntu deployment configs:
   - Nginx virtual host
   - Supervisor queue/scheduler/AI service
4. Add environment template and runbook documentation.

