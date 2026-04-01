# ShifoReyting AI (MVP)

AI-based anonymous patient feedback, doctor rating, and incentive management platform for clinics and hospitals.

## Stack
- Backend: Laravel 12 / PHP 8.2
- Admin Panel: Filament 5 (Laravel 12-compatible)
- Frontend: Blade + Tailwind
- DB: MySQL (SQLite supported for local testing)
- Queue/Cache: Redis
- AI service: FastAPI + transformers + scikit-learn + sentence-transformers

## Core Features Included
- Multi-tenant clinic hierarchy (clinic -> branch -> department -> doctor/service point)
- Role-based access (super admin, clinic admin, branch manager, doctor, analyst, support moderator)
- QR + shortlink anonymous survey flow
- Multilingual survey UI (`uz_latn`, `uz_cyrl`, `ru`, `en`)
- Fraud controls: IP/device/fingerprint hash + burst/duplicate detection
- AI queue pipeline for sentiment, toxicity, topics, summary, coaching hints
- Confidence-adjusted scoring and minimum sample threshold logic
- Dashboard widgets (realtime KPIs, trend chart, complaint alerts)
- Reward rules and reward eligibility model
- Escalations for critical complaints
- Billing module (subscriptions, invoices)
- Export center (Excel + PDF)
- Demo seed data and automated feature tests

## Docs
- Roadmap: [docs/IMPLEMENTATION_ROADMAP.md](docs/IMPLEMENTATION_ROADMAP.md)
- Database schema: [docs/DB_SCHEMA.md](docs/DB_SCHEMA.md)
- Project structure: [docs/PROJECT_STRUCTURE.md](docs/PROJECT_STRUCTURE.md)
- XAMPP setup: [docs/XAMPP_SETUP.md](docs/XAMPP_SETUP.md)

## XAMPP Quick Start
For your current path `D:\XAMPP\htdocs\dashboard\1`, use:
```powershell
cd D:\XAMPP\htdocs\dashboard\1
php artisan migrate:fresh --seed
npm install
npm run build
```
Open:
- `http://localhost/dashboard/1/public/admin/login`

## Quick Start (Local)
1. Install PHP dependencies:
   ```bash
   composer install
   ```
2. Install JS dependencies:
   ```bash
   npm install
   npm run build
   ```
3. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Run migrations + seed:
   ```bash
   php artisan migrate:fresh --seed
   ```
5. Run Laravel:
   ```bash
   php artisan serve
   ```
6. Run queue worker:
   ```bash
   php artisan queue:work redis --queue=default,ai
   ```
7. Run scheduler:
   ```bash
   php artisan schedule:work
   ```
8. Start AI service (separate process):
   ```bash
   cd ai-service
   python -m venv .venv
   .venv\Scripts\activate  # Windows
   pip install -r requirements.txt
   uvicorn app.main:app --host 127.0.0.1 --port 8001
   ```

## Demo Credentials
- Super Admin: `super.admin@shiforeyting.local` / `Password123!`
- Clinic Admin: `clinic.admin@shiforeyting.local` / `Password123!`
- Other seeded users: `*@shiforeyting.local` / `Password123!`

## Main Routes
- Admin: `/admin/login`
- QR survey: `/f/{token}`
- Shortlink survey: `/s/{slug}`
- API submit by token: `POST /api/v1/survey/submit/{token}`
- API submit by shortlink: `POST /api/v1/survey/submit-shortlink/{slug}`
- Public widget doctor: `GET /api/v1/widgets/doctor/{doctor}`
- Public widget clinic: `GET /api/v1/widgets/clinic/{clinic}`

## Export Center
- Filament page: `Export Center`
- Routes (auth required):
  - `/exports/doctor-monthly`
  - `/exports/department-ranking`
  - `/exports/complaint-categories`
  - `/exports/clinic-summary-pdf`

## Ubuntu 22.04 Deployment (Nginx + PHP-FPM + Supervisor + Redis)

### 1. Install system packages
```bash
sudo apt update
sudo apt install -y nginx mysql-server redis-server supervisor git unzip
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-intl
```

### 2. Deploy app
```bash
sudo mkdir -p /var/www/shiforeyting
sudo chown -R $USER:www-data /var/www/shiforeyting
cd /var/www/shiforeyting
git clone <your-repo-url> .
composer install --no-dev --optimize-autoloader
npm install
npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

### 3. Configure Nginx
```bash
sudo cp deploy/nginx/shiforeyting-ai.conf /etc/nginx/sites-available/shiforeyting-ai.conf
sudo ln -s /etc/nginx/sites-available/shiforeyting-ai.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 4. Configure Supervisor
```bash
sudo cp deploy/supervisor/laravel-worker.conf /etc/supervisor/conf.d/
sudo cp deploy/supervisor/laravel-scheduler.conf /etc/supervisor/conf.d/
sudo cp deploy/supervisor/ai-service.conf /etc/supervisor/conf.d/
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status
```

### 5. Runtime permissions
```bash
sudo chown -R www-data:www-data /var/www/shiforeyting/storage /var/www/shiforeyting/bootstrap/cache
sudo chmod -R ug+rwx /var/www/shiforeyting/storage /var/www/shiforeyting/bootstrap/cache
```

## AI Service Notes
- Configured via:
  - `SHIFOREYTING_AI_URL`
  - `SHIFOREYTING_AI_TIMEOUT`
- Endpoints:
  - `GET /health`
  - `POST /analyze`
  - `POST /batch`
  - `POST /coach`
  - `POST /explain-flag`

## Tests
```bash
php artisan test
```

Current baseline:
- `PublicSurveySubmissionTest`
- `ApiWidgetTest`
- Root redirect feature test
