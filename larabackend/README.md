# LegalPro - Backend API (Laravel 11)

**LegalPro** is an enterprise-grade Legal Case Management SaaS backend built with **Laravel 11**. It provides a robust, secure, and scalable RESTful API that powers the Next.js frontend, handling complex legal workflows, AI integrations, automated billing, and real-time notifications.

## 🌟 Key Backend Features

### 🤖 1. AI Document Summarizer
- Integrates with **OpenAI API** to process lengthy legal documents.
- Generates concise summaries highlighting key parties, dates, legal issues, and next actions.
- Supports dynamic language processing (English/Nepali).

### 📄 2. Dynamic Document Templates (Auto-Fill)
- Stores legal templates (e.g., Vakalatnama, Legal Notice) with `{{variable}}` placeholders.
- Automatically extracts variables using Regex and auto-fills them with real-time **Client** and **Case** data.
- Supports instant PDF generation via `barryvdh/laravel-dompdf`.

### ⏰ 3. Automated Cron Jobs (Daily Reminders)
- **Hearing Reminders:** Automatically emails clients and assigned lawyers about hearings scheduled for *tomorrow*.
- **Invoice Reminders:** Automatically emails clients with *overdue invoices* (past due date, unpaid/partial status).
- Managed seamlessly via Laravel Task Scheduling (`routes/console.php`).

### 🛡️ 4. Automated Daily Backups
- Powered by `spatie/laravel-backup`.
- Automatically backs up the Database and uploaded files (`storage/app/public`) daily.
- Includes smart cleanup strategies (keeps 7 days of all backups, 16 days of daily, 8 weeks of weekly, 4 months of monthly, 2 years of yearly).

### 🔔 5. Dynamic Notification System
- Database-driven notifications for Hearings, Overdue Tasks, Paid Invoices, and System updates.
- User-specific scoping (each user only sees their own notifications).
- Endpoints for fetching, marking as read, and bulk marking as read.

### ⚙️ 6. Global Dynamic Settings
- Centralized settings management for System Name, Logo, Favicon, Social Media, SMTP, and OpenAI API Keys.
- **Secure Public Endpoint:** Allows the Login page and Sidebar to fetch branding dynamically without authentication, while strictly hiding sensitive data (`$model->makeHidden()`).

### 📊 7. Interconnectivity & Automation
- **Hearing → Task:** Creating or updating a hearing automatically generates/updates a "Hearing Prep" task for the assigned lawyer.
- **Time Entry → Invoice:** Billable time entries can be automatically aggregated and pushed into client invoices.

### 🔐 8. Security & Role-Based Access Control (RBAC)
- **Laravel Sanctum** for secure API token authentication.
- Granular Role-Based Access Control (Super Admin, Lawyer, Staff, Client).
- **Rate Limiting:** API routes protected against brute-force attacks (`throttle:60,1`).
- **Client Portal:** Separate authentication scope for clients to view only their own cases, documents, and invoices.

---

## 🛠️ Tech Stack & Packages

| Category | Technology / Package |
|----------|----------------------|
| **Framework** | Laravel 12.x |
| **Language** | PHP 8.2+ |
| **Database** | SQLite (Local Dev) / MySQL (Production) |
| **Authentication** | Laravel Sanctum |
| **PDF Generation** | `barryvdh/laravel-dompdf` |
| **AI Integration** | `openai-php/client` |
| **Backups** | `spatie/laravel-backup` |
| **Architecture** | Clean Architecture (Controller → Service → Form Request → Model) |

---

## 📂 Architecture Pattern

This backend strictly follows the **Service Layer Pattern** to keep controllers thin and business logic reusable:

1. **Form Requests** (`app/Http/Requests/`): Handles all input validation and authorization.
2. **Controllers** (`app/Http/Controllers/Api/V1/`): Handles HTTP requests/responses and delegates logic.
3. **Services** (`app/Services/`): Contains core business logic (e.g., `TaskService`, `DocumentTemplateService`, `NotificationService`).
4. **Models** (`app/Models/`): Eloquent models with relationships, scopes, and accessors.

---

## ⚙️ Installation & Local Setup

### 1. Install Dependencies
```bash
composer install