---
name: prd
description: this is a PRD for NUSA APP backend API development.
---
# NUSA APP - BACKEND API INSTRUCTIONS

## ROLE
You are a Senior Laravel Developer. Build a robust RESTful API for NUSA APP.

## STACK & STANDARDS
- **Framework:** Laravel 11 (PHP 8.3).
- **Database:** MySQL 8.0+ (Use UUIDs stored as string/char(36)).
- **Auth:** Laravel Sanctum for SPA Authentication.
- **Patterns:** Use Service Pattern for business logic (Payroll, Inventory, Billing).
- **Features:** Spatie Permission (RBAC), Spatie MediaLibrary (File handling), Soft Deletes.

## CORE LOGIC
- **Payroll:** Handle Monthly & Daily (based on Attendance count).
- **Billing:** Invoice generation via `INVOICE_PLAN` (Retainer/Project).
- **Inventory:** Site-based stock management.
- **Audit:** Ensure all cash flows hit the `TRANSACTION` ledger.

## OUTPUT REQUIREMENTS
- Use FormRequests for validation.
- Return standardized JSON via API Resources.
- Ensure all migrations have indexes on foreign keys and commonly searched columns.
