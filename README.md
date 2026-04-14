# FitTrack – Gym Management System

**Production-ready gym management for small-to-medium Kenyan gyms.**  
Built with Laravel 10, MySQL, Bootstrap 5 — runs fully offline on XAMPP.

---

## Table of Contents

1. [System Overview](#1-system-overview)
2. [Project Structure](#2-project-structure)
3. [Installation (XAMPP)](#3-installation-xampp)
4. [Architecture & Design Decisions](#4-architecture--design-decisions)
5. [Database Schema](#5-database-schema)
6. [Feature Reference](#6-feature-reference)
7. [Default Credentials](#7-default-credentials)
8. [Running the Scheduler](#8-running-the-scheduler)
9. [Scaling to Cloud / SaaS](#9-scaling-to-cloud--saas)
10. [Security Checklist](#10-security-checklist)

---

## 1. System Overview

| Property          | Detail                                      |
|-------------------|---------------------------------------------|
| Framework         | Laravel 10 (PHP 8.1+)                       |
| Database          | MySQL 8.0 / MariaDB 10.4                    |
| Frontend          | Blade templates + Bootstrap 5.3 + Chart.js  |
| Auth              | Laravel session auth (bcrypt, role-based)   |
| Offline-first     | All storage: local MySQL + file sessions    |
| Target scale      | Up to ~1 000 members comfortably            |

---

## 2. Project Structure

```
fittrack/
├── app/
│   ├── Console/
│   │   ├── Commands/
│   │   │   └── ExpireSubscriptions.php   ← nightly artisan job
│   │   └── Kernel.php                    ← scheduler config
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── MemberController.php
│   │   │   ├── SubscriptionController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── AttendanceController.php
│   │   │   ├── TrainerController.php
│   │   │   └── ReportController.php
│   │   └── Middleware/
│   │       └── SessionTimeout.php        ← idle session guard
│   ├── Models/
│   │   ├── User.php
│   │   ├── Member.php
│   │   ├── MembershipPlan.php
│   │   ├── Subscription.php
│   │   ├── Payment.php
│   │   ├── Attendance.php
│   │   └── Trainer.php
│   └── Services/                         ← business logic layer
│       ├── MemberService.php
│       ├── SubscriptionService.php
│       ├── PaymentService.php
│       ├── AttendanceService.php
│       └── ReportService.php
├── database/
│   ├── schema.sql                        ← full DB schema (run first)
│   └── seeds.sql                         ← sample data (run second)
├── resources/views/
│   ├── layouts/app.blade.php             ← master layout
│   ├── auth/login.blade.php
│   ├── dashboard/index.blade.php
│   ├── members/{index,create,edit,show}.blade.php
│   ├── subscriptions/create.blade.php
│   ├── payments/{index,create,receipt}.blade.php
│   ├── attendance/{index,checkin}.blade.php
│   ├── trainers/{index,create,edit}.blade.php
│   └── reports/{index,revenue,members,attendance}.blade.php
├── routes/web.php
├── composer.json
├── .env.example
└── README.md
```

---

## 3. Installation (XAMPP)

### Prerequisites
- XAMPP with PHP 8.1+, MySQL, Apache
- Composer installed globally
- Git (optional)

### Step-by-Step

```bash
# 1. Place project in XAMPP's web root
cp -r fittrack/ C:/xampp/htdocs/fittrack    # Windows
# or
cp -r fittrack/ /opt/lampp/htdocs/fittrack  # Linux/Mac

# 2. Install PHP dependencies
cd C:/xampp/htdocs/fittrack
composer install

# 3. Copy and configure environment
cp .env.example .env
# Edit .env — set DB_PASSWORD if you have one set on root

# 4. Generate application key
php artisan key:generate

# 5. Create the database
# Open phpMyAdmin → New → Create database "fittrack" (utf8mb4_unicode_ci)
# OR via MySQL CLI:
mysql -u root -e "CREATE DATABASE fittrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Run the schema
mysql -u root fittrack < database/schema.sql

# 7. Load seed data
mysql -u root fittrack < database/seeds.sql

# 8. Set permissions (Linux/Mac only)
chmod -R 775 storage bootstrap/cache

# 9. Access the application
# Open browser: http://localhost/fittrack/public
```

### Virtual Host (Optional — cleaner URLs)
Add to `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
```apache
<VirtualHost *:80>
    ServerName fittrack.local
    DocumentRoot "C:/xampp/htdocs/fittrack/public"
    <Directory "C:/xampp/htdocs/fittrack/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
Add `127.0.0.1 fittrack.local` to your hosts file.  
Then access via `http://fittrack.local`

---

## 4. Architecture & Design Decisions

### MVC + Service Layer
```
Request → Route → Controller → Service → Model → Database
                      ↓
                    View
```

**Controllers** are thin — they validate HTTP input and delegate to services.  
**Services** contain all business logic (subscription creation, attendance validation, receipt generation).  
**Models** handle relationships and attribute helpers only.

This means:
- Business rules are in one place — easy to unit test
- Controllers are easy to read and audit
- Adding a future API is just new controllers calling the same services

### Why not Laravel Jetstream / Breeze?
Kept minimal. Both add React/Vue/Livewire overhead unnecessary for an offline, non-SPA app. Plain Blade with Bootstrap loads fast on local hardware with no internet.

### Why file-based sessions?
Works without Redis or a database session table. Appropriate for a single-server, offline gym system. If you scale to multiple servers later, switch `SESSION_DRIVER=database` and run `php artisan session:table`.

---

## 5. Database Schema

### Entity Relationships (simplified)

```
users ──────────────────────────────────────────────┐
                                                     │ created_by / recorded_by
trainers ──┐
           │ trainer_id
members ───┴──────────────────────────────────────────┤
           │                                          │
           ├── subscriptions ── membership_plans      │
           │        │                                 │
           ├── payments ────────┘                     │
           └── attendance ───────────────────────────┘
```

### Key Constraints
- `members.trainer_id` → `trainers.id` ON DELETE SET NULL
- `subscriptions.member_id` → `members.id` ON DELETE CASCADE
- `payments.member_id` → `members.id` ON DELETE RESTRICT (preserve financial records)
- `attendance.member_id` → `members.id` ON DELETE CASCADE
- All financial amounts stored as `DECIMAL(10,2)` — never FLOAT

---

## 6. Feature Reference

### Authentication
| Route | Description |
|-------|-------------|
| GET /login | Login page |
| POST /login | Authenticate |
| POST /logout | Destroy session |

Roles: `admin` (full access) and `staff` (operational access).  
Add role gates in `routes/web.php` using `->middleware('can:admin')` as needed.

### Member Management
| Route | Description |
|-------|-------------|
| GET /members | Paginated member list with search/filter |
| GET /members/create | New member form |
| POST /members | Store new member |
| GET /members/{id} | Member profile with subs, payments, attendance |
| GET /members/{id}/edit | Edit form |
| PUT /members/{id} | Update |
| DELETE /members/{id} | Deactivate (soft — never hard deletes) |

Member numbers are auto-generated: `FT-000001`, `FT-000002`, ...

### Subscription Flow
```
Member profile → "New Sub" → Select plan → Confirm
    → Redirects to Record Payment (pre-linked to subscription)
    → Receipt generated
```

### Check-In Flow
```
Staff opens /attendance/checkin
→ Types member number OR phone number
→ System validates active subscription
→ Checks for duplicate same-day entry
→ Success or descriptive error message
```

### Payment Receipt
Every payment generates a printable HTML receipt at:  
`/payments/{id}/receipt`

Use `window.print()` button — no PDF library required. Browser print-to-PDF works perfectly.

### Reports
| Report | Route | Data |
|--------|-------|------|
| Revenue | /reports/revenue | Bar chart + table by month |
| Members | /reports/members | Status summary + expired list |
| Attendance | /reports/attendance | Line chart + daily counts |

---

## 7. Default Credentials

After running seeds.sql:

| Email | Password | Role |
|-------|----------|------|
| admin@fittrack.co.ke | password | Admin |
| jane@fittrack.co.ke | password | Staff |
| brian@fittrack.co.ke | password | Staff |

**Change all passwords immediately in production.**

---

## 8. Running the Scheduler

The scheduler auto-expires past subscriptions nightly.

### Windows (XAMPP)
Use Windows Task Scheduler:
```
Program: C:\xampp\php\php.exe
Arguments: C:\xampp\htdocs\fittrack\artisan schedule:run
Trigger: Daily at 12:05 AM
```

### Linux/Mac (XAMPP)
Add to crontab (`crontab -e`):
```cron
* * * * * php /opt/lampp/htdocs/fittrack/artisan schedule:run >> /dev/null 2>&1
```

### Manual (no scheduler setup)
Run this command each morning:
```bash
php artisan fittrack:expire-subscriptions
```

---

## 9. Scaling to Cloud / SaaS

### Phase 1 — Move to Cloud (same codebase)
1. Deploy to a VPS (DigitalOcean, Hetzner, or AWS EC2)
2. Switch `SESSION_DRIVER=database` — run `php artisan session:table && artisan migrate`
3. Add Redis for cache: `CACHE_DRIVER=redis`
4. Set up MySQL on RDS or managed DB
5. Add HTTPS via Let's Encrypt (Certbot)
6. Set up proper cron on the server

**Estimated cost**: ~$6–12/month on a basic VPS

### Phase 2 — Multi-Gym SaaS Conversion
The key change is adding **tenant isolation**. Options:

**Option A – Schema-per-tenant (recommended for gyms)**
- Each gym gets its own MySQL database: `fittrack_gymname`
- Add a `tenants` table to a central `fittrack_core` DB
- Use a middleware to switch DB connection per subdomain: `gymname.fittrack.co.ke`
- Library: `stancl/tenancy` (Laravel package) handles this automatically

**Option B – Row-level multi-tenancy**
- Add `gym_id` column to every table
- Scope every query with `where('gym_id', auth()->user()->gym_id)`
- Simpler to implement, harder to keep secure without global scopes

**Additional SaaS features to build**:
```
- Gym registration / onboarding flow
- Subscription billing (Stripe or M-Pesa API for gyms)
- Per-gym branding (logo, gym name)
- Owner role above admin
- Usage analytics per gym
- Trial period logic
- Email notifications (membership expiry reminders)
```

### Phase 3 — Mobile App
- Extract business logic into a JSON API (Laravel Sanctum is already in composer.json)
- Build React Native or Flutter app consuming the API
- The Service layer design means the API controllers just call the same services

---

## 10. Security Checklist

### Already Implemented
- [x] bcrypt password hashing (Laravel default, cost 12)
- [x] CSRF protection on all forms (`@csrf`)
- [x] Session regeneration on login
- [x] Input validation via Laravel's `validate()` on every controller method
- [x] Mass assignment protection via `$fillable` on all models
- [x] Foreign key constraints (prevent orphaned records)
- [x] SQL injection prevention (Eloquent parameterized queries throughout)
- [x] Session timeout middleware (120 min idle)
- [x] Soft-deactivation (no hard deletes of financial records)

### Recommended Before Production
- [ ] Change all seed user passwords
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_ENV=production`
- [ ] Configure Apache/Nginx to block direct access to `/app`, `/database`, etc.
- [ ] Enable HTTPS (even self-signed for LAN-only gyms)
- [ ] Set up daily MySQL backups:
  ```bash
  mysqldump -u root fittrack > backup_$(date +%Y%m%d).sql
  ```
- [ ] Add admin-only middleware to sensitive routes (user management, etc.)
- [ ] Review and add role-based authorization policies for admin vs staff actions

---

## License

MIT — free for personal and commercial use.
