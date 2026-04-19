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
6. [Portals & Feature Reference](#6-portals--feature-reference)
7. [Default Credentials](#7-default-credentials)
8. [Running the Scheduler](#8-running-the-scheduler)
9. [Known Fixes Applied (v1.1)](#9-known-fixes-applied-v11)
10. [Scaling to Cloud / SaaS](#10-scaling-to-cloud--saas)
11. [Security Checklist](#11-security-checklist)

---

## 1. System Overview

| Property      | Detail                                              |
|---------------|-----------------------------------------------------|
| Framework     | Laravel 10 (PHP 8.1+)                               |
| Database      | MySQL 8.0 / MariaDB 10.4                            |
| Frontend      | Blade templates + Bootstrap 5.3 + Chart.js          |
| Auth          | Laravel session auth — 3 separate guards (bcrypt)   |
| Portals       | Staff · Member self-service · Trainer               |
| Offline-first | All storage: local MySQL + file sessions            |
| Target scale  | Up to ~1 000 members comfortably                    |

---

## 2. Project Structure

```
fittrack/
├── app/
│   ├── Console/
│   │   ├── Commands/
│   │   │   └── ExpireSubscriptions.php      ← nightly artisan job
│   │   └── Kernel.php                       ← scheduler config
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php           ← staff/admin login
│   │   │   ├── DashboardController.php
│   │   │   ├── MemberController.php
│   │   │   ├── SubscriptionController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── AttendanceController.php
│   │   │   ├── TrainerController.php
│   │   │   ├── ReportController.php
│   │   │   ├── PortalAccessController.php   ← grant/revoke portal access
│   │   │   ├── Member/
│   │   │   │   ├── MemberAuthController.php
│   │   │   │   └── MemberDashboardController.php
│   │   │   └── Trainer/
│   │   │       ├── TrainerAuthController.php
│   │   │       └── TrainerDashboardController.php
│   │   └── Middleware/
│   │       └── SessionTimeout.php           ← idle session guard
│   ├── Models/
│   │   ├── User.php
│   │   ├── Member.php
│   │   ├── MembershipPlan.php
│   │   ├── Subscription.php
│   │   ├── Payment.php
│   │   ├── Attendance.php                   ← table = 'attendance' (explicit)
│   │   ├── Trainer.php
│   │   ├── MemberCredential.php             ← member portal auth model
│   │   ├── TrainerCredential.php            ← trainer portal auth model
│   │   ├── WorkoutSession.php
│   │   ├── WorkoutExercise.php
│   │   ├── TrainerMemberNote.php
│   │   └── ActivityLog.php
│   └── Services/                            ← all business logic lives here
│       ├── MemberService.php
│       ├── SubscriptionService.php
│       ├── PaymentService.php
│       ├── AttendanceService.php
│       └── ReportService.php
├── config/
│   └── auth.php                             ← 3 guards: web, member, trainer
├── database/
│   ├── schema.sql                           ← core DB schema (run first)
│   ├── portal_additions.sql                 ← v1.1 portal tables (run second)
│   └── seeds_fixed.sql                      ← sample data with valid hashes (run third)
├── resources/views/
│   ├── welcome.blade.php                    ← unified portal landing page
│   ├── layouts/app.blade.php                ← staff master layout
│   ├── auth/login.blade.php                 ← staff login
│   ├── dashboard/index.blade.php
│   ├── members/
│   ├── subscriptions/
│   ├── payments/
│   ├── attendance/
│   ├── trainers/
│   ├── reports/
│   ├── member/                              ← member portal views
│   │   ├── auth/{login,change-password}.blade.php
│   │   ├── dashboard.blade.php
│   │   ├── attendance.blade.php
│   │   ├── payments.blade.php
│   │   ├── subscriptions.blade.php
│   │   ├── workouts.blade.php
│   │   └── workout-show.blade.php
│   ├── trainer-portal/                      ← trainer portal views
│   │   ├── auth/{login,change-password}.blade.php
│   │   ├── dashboard.blade.php
│   │   ├── members.blade.php
│   │   ├── member-show.blade.php
│   │   ├── session-create.blade.php
│   │   └── session-show.blade.php
│   └── admin/
│       └── portal/
│           ├── members.blade.php            ← grant/revoke member portal access
│           └── trainers.blade.php           ← grant/revoke trainer portal access
├── routes/web.php
├── .env.example
└── README.md
```

---

## 3. Installation (XAMPP)

### Prerequisites
- XAMPP with PHP 8.1+, MySQL 8.0 / MariaDB 10.4, Apache
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
# Edit .env — at minimum set DB_PASSWORD if your MySQL root has one

# 4. Generate application key
php artisan key:generate

# 5. Create the database
# Via phpMyAdmin: New → name "fittrack" → Collation: utf8mb4_unicode_ci
# OR via MySQL CLI:
mysql -u root -e "CREATE DATABASE fittrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Run the schema in order
mysql -u root fittrack < database/schema.sql
mysql -u root fittrack < database/portal_additions.sql
mysql -u root fittrack < database/seeds_fixed.sql

# 7. Set permissions (Linux/Mac only)
chmod -R 775 storage bootstrap/cache

# 8. Open in browser
# http://localhost/fittrack/public
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

Add `127.0.0.1 fittrack.local` to your hosts file (`C:\Windows\System32\drivers\etc\hosts`).  
Then access the app at `http://fittrack.local`.

---

## 4. Architecture & Design Decisions

### MVC + Service Layer

```
Request → Route → Controller → Service → Model → Database
                      ↓
                    View
```

**Controllers** are thin — validate HTTP input, call a service, return a view or redirect.  
**Services** own all business rules (subscription creation, attendance validation, receipt numbering).  
**Models** handle relationships and attribute casting only.

This means business rules live in one place, are easy to unit-test, and a future REST API just needs new controllers calling the same services.

### Three-Guard Authentication

```
Guard: web      → table: users             → Admin & Staff portal  (/login)
Guard: member   → table: member_credentials → Member portal         (/member/login)
Guard: trainer  → table: trainer_credentials → Trainer portal        (/trainer/login)
```

Member and trainer credentials are stored in separate tables (not the `users` table) to prevent role confusion and keep permission surfaces small. Each guard has its own session key, so a staff member and a member can be logged in simultaneously in the same browser without conflict.

Members log in with their **phone number** (easy to remember, always unique). Trainers and staff log in with **email**.

### Why Not Laravel Jetstream / Breeze?

Both add React/Vue/Livewire overhead that is unnecessary for an offline, non-SPA system. Plain Blade with Bootstrap loads fast on local hardware with no internet dependency.

### Why File-Based Sessions?

Works without Redis or a database session table — appropriate for a single-server offline gym. To scale to multiple servers later, set `SESSION_DRIVER=database` and run `php artisan session:table && php artisan migrate`.

---

## 5. Database Schema

### Entity Relationships

```
users ─────────────────────────────────────── (created_by / recorded_by on all tables)

trainers ──────────────── trainer_credentials
    │
    │ trainer_id
    ▼
members ─────────────────── member_credentials
    │
    ├── subscriptions ──── membership_plans
    │       │
    ├── payments ──────────┘
    ├── attendance
    └── workout_sessions ── workout_exercises
             │
             └── trainer_member_notes
```

### Key Tables

| Table | Purpose |
|---|---|
| `users` | Admin and staff logins |
| `trainers` | Trainer profiles |
| `members` | Gym member profiles |
| `membership_plans` | Plan catalogue (price, duration) |
| `subscriptions` | One row per membership period |
| `payments` | Payment records with M-Pesa ref support |
| `attendance` | Check-in / check-out log |
| `member_credentials` | Member portal login credentials |
| `trainer_credentials` | Trainer portal login credentials |
| `workout_sessions` | Header record per training session |
| `workout_exercises` | Individual exercises within a session |
| `trainer_member_notes` | Trainer notes on a member (public or private) |
| `activity_log` | Full audit trail of all actions |

### Key Constraints

- `members.trainer_id` → `trainers.id` ON DELETE SET NULL
- `subscriptions.member_id` → `members.id` ON DELETE CASCADE
- `payments.member_id` → `members.id` ON DELETE RESTRICT *(preserve financial records)*
- `attendance.member_id` → `members.id` ON DELETE CASCADE
- All monetary amounts stored as `DECIMAL(10,2)` — never FLOAT

---

## 6. Portals & Feature Reference

### Landing Page

The root URL (`/`) shows a unified portal-selection page. Users pick their role before logging in — no need to remember separate URLs.

### Staff Portal (`/login`)

Full gym management. Accessible to `admin` and `staff` roles.

| Area | Routes | Notes |
|---|---|---|
| Dashboard | `GET /dashboard` | Stats, recent activity, expiring subs |
| Members | `GET /members` | Search, filter by status/trainer |
| Member profile | `GET /members/{id}` | Subs, payments, attendance, trainer |
| Subscriptions | `POST /members/{id}/subscriptions` | Linked to a plan; auto-calculates end date |
| Payments | `GET/POST /payments` | Supports M-Pesa ref, cash, bank transfer, card |
| Payment receipt | `GET /payments/{id}/receipt` | Printable HTML — no PDF library needed |
| Attendance | `GET /attendance/checkin` | Check in by member number or phone |
| Trainers | `GET /trainers` | CRUD for trainer profiles |
| Reports | `GET /reports/*` | Revenue, members, attendance |
| Portal access | `GET /admin/portal-access/*` | Grant/revoke member & trainer portal logins |

Member numbers are auto-generated: `FT-000001`, `FT-000002`, …

**Subscription → Payment flow:**
```
Member profile → "New Subscription" → Select plan
    → Redirects to Record Payment (pre-linked to subscription)
    → Receipt generated automatically
```

**Check-in flow:**
```
Staff opens /attendance/checkin
    → Types member number OR phone number
    → System validates active subscription
    → Checks for duplicate same-day entry
    → Records check-in or shows descriptive error
```

### Member Portal (`/member/login`)

Self-service read-only view for members. Login: **phone number + password**.

| Page | What members can see |
|---|---|
| Dashboard | Name, membership status, days remaining, trainer |
| Subscriptions | Full subscription history |
| Payments | Payment history with receipt details |
| Attendance | Personal check-in history |
| Workouts | Sessions logged by their trainer |
| Workout detail | Exercise list, weights, reps, trainer notes |

Members can also leave feedback on individual workout sessions.

Portal access is **granted by admin** from the Staff portal (`/admin/portal-access/members`). Default temporary password is `fittrack123` — members are prompted to change it on first login.

### Trainer Portal (`/trainer/login`)

Focused view for trainers. Login: **email + password**.

| Page | What trainers can do |
|---|---|
| Dashboard | Summary of assigned members, recent sessions |
| Members list | All members assigned to this trainer |
| Member detail | Profile, active subscription, attendance, workout history |
| Log session | Create a workout session with exercises (sets/reps/weight/duration) |
| Session detail | View and review a past session |
| Notes | Add public or private notes on a member |

Private notes (`is_private = 1`) are visible only to the trainer — not shown in the member portal.

---

## 7. Default Credentials

Run `seeds_fixed.sql` to populate these accounts. All passwords are valid bcrypt hashes.

### Staff Portal (`/login`)

| Email | Password | Role |
|---|---|---|
| admin@fittrack.co.ke | `password` | Admin |
| jane@fittrack.co.ke | `password` | Staff |
| brian@fittrack.co.ke | `password` | Staff |

### Member Portal (`/member/login`)

| Phone | Password | Member |
|---|---|---|
| 0700111222 | `fittrack123` | Alice Wanjiku |
| 0700222333 | `fittrack123` | John Kamau |
| 0700333444 | `fittrack123` | Grace Akinyi |
| 0700444555 | `fittrack123` | David Otieno |

### Trainer Portal (`/trainer/login`)

| Email | Password | Trainer |
|---|---|---|
| moses@fittrack.co.ke | `fittrack123` | Moses Kariuki |
| faith@fittrack.co.ke | `fittrack123` | Faith Njoki |

> **Change all passwords immediately before going live.**

---

## 8. Running the Scheduler

The scheduler marks past-end-date subscriptions as `expired` automatically.

### Windows (XAMPP) — Task Scheduler

```
Program:   C:\xampp\php\php.exe
Arguments: C:\xampp\htdocs\fittrack\artisan schedule:run
Trigger:   Daily at 12:05 AM
```

### Linux/Mac (XAMPP) — Cron

```bash
crontab -e
# Add this line:
* * * * * php /opt/lampp/htdocs/fittrack/artisan schedule:run >> /dev/null 2>&1
```

### Manual (no scheduler)

```bash
php artisan fittrack:expire-subscriptions
```

---

## 9. Known Fixes Applied (v1.1)

These bugs were present in the original v1.0 release and are fixed in the current codebase.

### 1. Login always failing — broken seed hashes

**Symptom:** "Invalid email or password" on every login attempt.

**Cause:** Both `seeds.sql` and `portal_additions.sql` used `$2y$12$92IXUNpkjO0rOQ5byMi…` — a well-known Laravel placeholder hash that does **not** verify against any password. It is intentionally non-functional and should never be used in real seeds.

**Fix:** `seeds_fixed.sql` replaces all hashes with freshly generated valid bcrypt hashes. Use this file instead of the original `seeds.sql` + the credential sections of `portal_additions.sql`.

### 2. `attendances` table not found

**Symptom:**
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'fittrack.attendances' does not exist
```

**Cause:** Laravel auto-pluralizes Eloquent model names to guess the table name. `Attendance` → `attendances`. The actual table is named `attendance` (already the collective noun — no 's').

**Fix:** Added `protected $table = 'attendance';` to `app/Models/Attendance.php`.

### 3. No unified login landing page

**Symptom:** `/` redirected straight to `/dashboard`, then to `/login` — leaving members and trainers to guess their portal URL.

**Fix:** Added `resources/views/welcome.blade.php` — a landing page with three portal cards (Staff, Member, Trainer) each showing the correct URL and login method. Updated `routes/web.php` to serve this view at `/`.

---

## 10. Scaling to Cloud / SaaS

### Phase 1 — Move to a VPS (same codebase)

1. Deploy to DigitalOcean, Hetzner, or AWS EC2
2. Switch `SESSION_DRIVER=database` — run `php artisan session:table && php artisan migrate`
3. Add Redis: `CACHE_STORE=redis`
4. Point to a managed MySQL (PlanetScale, RDS, or self-hosted)
5. Add HTTPS via Let's Encrypt (Certbot)
6. Set up proper cron on the server

**Estimated cost:** ~$6–12/month on a basic VPS.

### Phase 2 — Multi-Gym SaaS

The key addition is **tenant isolation**. Two approaches:

**Option A — Database-per-tenant (recommended)**
- Each gym gets its own database: `fittrack_gymname`
- A central `fittrack_core` DB holds a `tenants` table
- Middleware switches the DB connection per subdomain: `gymname.fittrack.co.ke`
- `stancl/tenancy` (Laravel package) handles this automatically

**Option B — Row-level multi-tenancy**
- Add `gym_id` to every table; scope every query with a global Eloquent scope
- Simpler to build, requires discipline to keep all queries properly scoped

**Additional SaaS work:**
- Gym registration and onboarding flow
- Subscription billing for gyms (Stripe or M-Pesa API)
- Per-gym branding (logo, gym name, colours)
- Owner role above admin
- Trial period logic
- Email notifications (membership expiry reminders via Mailgun or SendGrid)

### Phase 3 — Mobile App

Laravel Sanctum is already in `composer.json`. The Service layer means API controllers are thin wrappers that call the same services as web controllers. Build a React Native or Flutter app consuming a `/api/*` route group.

---

## 11. Security Checklist

### Already Implemented

- [x] bcrypt password hashing (cost 12 via `BCRYPT_ROUNDS=12` in `.env`)
- [x] CSRF protection on all forms (`@csrf`)
- [x] Session regeneration on login and logout
- [x] Input validation via Laravel `validate()` on every controller method
- [x] Mass assignment protection (`$fillable` on all models)
- [x] Foreign key constraints (prevent orphaned records)
- [x] SQL injection prevention (Eloquent parameterised queries throughout)
- [x] Session timeout middleware (120 min idle)
- [x] Separate auth guards per portal (member session can't access staff routes)
- [x] Soft-deactivation (no hard deletes of financial records)
- [x] Private trainer notes hidden from member portal

### Required Before Production

- [ ] Change **all** seed passwords (staff, members, trainers)
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Restrict Apache/Nginx from serving `/app`, `/database`, `/storage` directly
- [ ] Enable HTTPS (even a self-signed cert for LAN-only gyms)
- [ ] Set up daily MySQL backups:
  ```bash
  mysqldump -u root fittrack > backup_$(date +%Y%m%d).sql
  ```
- [ ] Add admin-only middleware to user management and portal-access routes
- [ ] Review and harden role-based authorization (admin vs staff action boundaries)

---

## License

MIT — free for personal and commercial use.