# FitTrack GMS

**FitTrack** is a Gym Management System (GMS) designed for the **Dedan Kimathi University of Technology (DeKUT) Sports & Fitness Centre**. It provides a clean, modern interface for managing gym members, tracking attendance, processing payments, and generating reports — all from a single web-based admin panel.

---

## 📁 Project Structure

```
fittrack/
├── pages/
│   ├── login.html               # Admin login page
│   ├── dashboard.html           # Main dashboard with stats & overview
│   ├── member-registration.html # Register & manage members
│   ├── attendance.html          # Attendance tracking
│   ├── payment.html             # Payment recording & history
│   └── reports.html             # Reports & analytics
├── assets/
│   ├── css/                     # (Future) Extracted stylesheets
│   ├── js/                      # (Future) Extracted scripts
│   └── images/                  # (Future) Static assets
└── README.md
```

---

## 🚀 Features

- **Login** — Secure admin-only access with role information
- **Dashboard** — At-a-glance stats: active members, revenue, attendance, and alerts
- **Member Registration** — Add, edit, and manage gym member profiles and subscriptions
- **Attendance** — Log and view member check-ins and activity history
- **Payments** — Record dues, view payment history, and track outstanding balances
- **Reports** — Analytics and exportable summaries for management

---

## 🛠️ Tech Stack

- Pure **HTML5 & CSS3** — no frameworks required
- **Barlow / Barlow Condensed** fonts via Google Fonts
- Fully **offline-ready** — all pages are self-contained
- Designed for **1200px+** screen widths (desktop admin panel)

---

## 🏫 About

FitTrack GMS v1.0  
**Dedan Kimathi University of Technology** · 2026  
Built for the DeKUT Sports & Fitness Centre to replace manual record-keeping with a streamlined digital system.

---

## 📌 Getting Started

No build tools needed. Simply open any page in a browser:

```bash
# Clone the repo
git clone https://github.com/your-username/fittrack.git

# Open the login page
open fittrack/pages/login.html
```

Or serve locally with any static file server:

```bash
npx serve fittrack/
```

---

## 📄 License

This project is for academic and institutional use at DeKUT.
