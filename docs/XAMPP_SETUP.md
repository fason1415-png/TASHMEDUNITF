# XAMPP Setup (Windows)

Project path: `D:\XAMPP\htdocs\dashboard\1`

## 1. XAMPP services
1. Open XAMPP Control Panel.
2. Start `Apache`.
3. Start `MySQL`.

## 2. Create database
Use phpMyAdmin (`http://localhost/phpmyadmin`) and create:
- Database: `shiforeyting_ai`
- Collation: `utf8mb4_unicode_ci`

## 3. Verify `.env`
Already configured for XAMPP:
- `APP_URL=http://localhost/dashboard/1/public`
- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=shiforeyting_ai`
- `DB_USERNAME=root`
- `DB_PASSWORD=`
- `QUEUE_CONNECTION=sync`
- `CACHE_STORE=file`
- `SESSION_DRIVER=file`

## 4. Run migrations and seeders
```powershell
cd D:\XAMPP\htdocs\dashboard\1
php artisan migrate:fresh --seed
```

## 5. Build frontend assets
```powershell
npm install
npm run build
```

## 6. Open app
- Admin login: `http://localhost/dashboard/1/public/admin/login`
- Survey endpoint format: `http://localhost/dashboard/1/f/{token}`

## 7. Demo login
- Super Admin: `super.admin@shiforeyting.local`
- Password: `Password123!`

## Optional: Enable AI microservice locally
```powershell
cd D:\XAMPP\htdocs\dashboard\1\ai-service
python -m venv .venv
.venv\Scripts\activate
pip install -r requirements.txt
uvicorn app.main:app --host 127.0.0.1 --port 8001
```

Then set in `.env`:
```env
SHIFOREYTING_AI_URL=http://127.0.0.1:8001
```
