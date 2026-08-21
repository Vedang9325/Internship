# FinnServ – Complete Project Walkthrough

## What is FinnServ?

FinnServ is a **PHP-based Accounting & Business Management System** inspired by Tally ERP. It runs on XAMPP (Apache + MySQL + PHP), uses **PDO** for database access, and is organized as a **multi-company, multi-financial-year** accounting platform.

---

## 🗂️ Project Structure at a Glance

```
FinnServ/
│
├── index.php                  ← Entry point / smart redirect
│
├── config/
│   ├── app.php                ← App constants (name, base URL, timezone)
│   └── database.php           ← PDO MySQL connection
│
├── includes/
│   ├── init.php               ← Bootstrap: loads config + starts session
│   ├── auth.php               ← Auth guard: redirects if not logged in
│   └── flash.php              ← Flash message helpers (set/get)
│
├── auth/
│   ├── login.php              ← Login form + login logic
│   └── logout.php             ← Session destroy + redirect
│
├── dashboard/
│   └── index.php              ← Gateway / Home screen (Tally-style)
│
├── company/
│   ├── menu.php               ← Company section menu
│   ├── create.php             ← Create Company form (UI)
│   ├── save.php               ← Create Company handler (POST logic)
│   ├── alter.php              ← Edit Company form (UI)
│   ├── update.php             ← Edit Company handler (POST logic)
│   ├── select.php             ← Select/Switch active company (list UI)
│   ├── switch.php             ← Switch company into session (GET redirect)
│   ├── delete.php             ← Delete Company (confirm + POST handler)
│   ├── repository.php         ← DB functions: find/update/getAll companies
│   └── validator.php          ← Validation rules for company data
│
├── financial-year/
│   ├── index.php              ← List financial years (table view)
│   ├── create.php             ← Create Financial Year form (UI)
│   ├── save.php               ← Create Financial Year handler (POST logic)
│   ├── activate.php           ← Activate a financial year (GET handler)
│   ├── repository.php         ← DB functions: getAll/get/create/update/activate
│   └── validator.php          ← Validation rules for financial year data
│
├── chart-of-accounts/
│   └── index.php              ← Menu: Groups / Ledgers (Ledgers = disabled)
│
├── groups/
│   ├── index.php              ← Groups menu (Create/Alter/Display/Delete)
│   ├── create.php             ← Create Group form (UI) — complex Tally-like form
│   ├── alter.php              ← Edit Group form (UI)
│   ├── display.php            ← Display Group details
│   ├── edit.php               ← Edit Group form (detailed version)
│   ├── save.php               ← Create Group handler (POST logic)
│   ├── update.php             ← Edit Group handler (POST logic)
│   ├── delete.php             ← Delete Group (confirm + POST handler)
│   ├── repository.php         ← DB function: getAllGroups
│   └── validator.php          ← Validation rules for group data
│
└── assets/
    ├── css/                   ← Stylesheets (style.css, gateway.css, company.css)
    └── js/                    ← JavaScript (gateway.js)
```

---

## 🔁 Overall Application Flow (How it works step by step)

```
User visits /FinnServ/
        │
        ▼
   index.php
   ├── Session has user_id? → Redirect to dashboard/
   └── No session?          → Redirect to auth/login.php
        │
        ▼
   auth/login.php
   ├── GET  → Show login form
   └── POST → Validate credentials
              ├── Wrong?     → Show error on same page
              └── Correct?   → Set session + redirect to dashboard/
        │
        ▼
   dashboard/index.php   (requires auth + active company)
   ├── No company?        → Redirect to company/select.php
   └── Has company?       → Show Gateway (Tally-style home screen)
        │
        ├──→ company/menu.php        (Company section)
        ├──→ financial-year/         (Financial Years)
        ├──→ chart-of-accounts/      (Chart of Accounts menu)
        │       └──→ groups/         (Groups CRUD)
        └──→ auth/logout.php         (Logout)
```

---

## 📄 File-by-File Explanation

---

### `index.php` — Entry Point
```
Checks if user is logged in via $_SESSION['user_id']
→ If yes: send to dashboard/
→ If no:  send to auth/login.php
```
This is the **root redirect** file. It never renders HTML itself.

---

### `config/app.php` — App Configuration
Defines **global constants**:
- `APP_NAME` = `'FinnServ'`
- `BASE_URL` = `'/Internship/FinnServ/'`
- `APP_ENV` = `'development'`
- Sets timezone to `Asia/Kolkata`

---

### `config/database.php` — Database Connection
- Creates a **PDO connection** to MySQL (`finnserv` database)
- Uses `root` / no password (XAMPP defaults)
- Sets error mode to EXCEPTIONS so errors throw catchable exceptions
- If connection fails in development, shows the error message

---

### `includes/init.php` — Bootstrap File
Every page starts with `require_once 'includes/init.php'`. It:
1. Loads `config/app.php` (constants)
2. Loads `config/database.php` (creates `$pdo`)
3. Starts the PHP session (if not started)
4. Sets headers to **prevent browser caching** of pages

---

### `includes/auth.php` — Auth Guard
Simple one-purpose file:
```
If $_SESSION['user_id'] is NOT set → redirect to login page
```
Every protected page includes this after `init.php`.

---

### `includes/flash.php` — Flash Messages
Two functions:
- `setFlash($type, $message)` — stores a message in session
- `getFlash()` — retrieves and **deletes** the message (one-time use)

Used to pass success/error messages across redirects (e.g., "Financial year created successfully").

---

### `auth/login.php` — Login Page

**GET request:** Renders the HTML login form.

**POST request:**
1. Trims and reads `username` + `password` from POST
2. Queries `users` table WHERE `username = ?`
3. Uses `password_verify()` to check the hashed password
4. Checks `is_active = 1`
5. If valid:
   - Calls `session_regenerate_id(true)` (security – prevents session fixation)
   - Sets session: `user_id`, `company_id`, `username`, `user_name`, `role`
   - Also queries `financial_years` to get the active financial year for the company
   - Sets session: `financial_year_id`, `financial_year_name`
   - Redirects to dashboard
6. If invalid: shows error message

> **Key security point:** `password_verify()` is used (bcrypt), and session is regenerated on login.

---

### `auth/logout.php` — Logout

1. Clears `$_SESSION = []`
2. Expires the session cookie
3. Calls `session_destroy()`
4. Redirects to login page

---

### `dashboard/index.php` — The Gateway (Home Screen)

This is the **Tally-style main screen**. It:
- Requires auth + active company (redirects to `company/select.php` if no company)
- Displays:
  - **Current Period** (financial year start → end dates)
  - **Current Date**
  - **Company Name**
  - **Financial Year name**, logged-in **User**, and **Role**
- Shows a **left menu** (Masters / Transactions / Utilities / Reports)
  - Most menu items are currently **disabled** (placeholders for future features)
  - Active links: Company, Financial Years
- Top nav has shortcut buttons (K, Y, Z, G, O, E, M, P) — Tally-style

---

## 🏢 Company Module (`company/`)

### Pattern used across the module:
| File | Purpose |
|------|---------|
| `menu.php` | Navigation menu listing all company actions |
| `create.php` | HTML form to create a new company |
| `save.php` | Handles the POST from create form (validation + DB insert) |
| `alter.php` | HTML form to edit existing company (pre-filled) |
| `update.php` | Handles the POST from alter form (validation + DB update) |
| `select.php` | Lists all companies, lets user click one to switch |
| `switch.php` | Handles company switch (sets session + redirect) |
| `delete.php` | List + confirm + handle company deletion |
| `repository.php` | Pure DB functions: `findCompany()`, `updateCompany()`, `getAllCompanies()` |
| `validator.php` | `validateCompany()` — checks name, email, GSTIN, pincode |

### `company/save.php` — Create Company Logic (detailed)
1. Reads all POST fields
2. Validates:
   - Name required, max 150 chars
   - Financial year format must be `YYYY-YY` (e.g., 2026-27)
   - Start date must be valid and within the financial year
   - Books beginning cannot be before start date
   - Email format validated
   - GSTIN must be exactly 15 alphanumeric characters
3. If errors: saves errors + old values to session → redirects back to form
4. If valid:
   - **Begins a DB transaction**
   - Inserts into `companies` table
   - Automatically calculates `end_date` = start_date + 1 year - 1 day
   - Inserts into `financial_years` table with `is_active = 1`
   - **Commits** transaction
   - Sets full session context (company + financial year)
   - Redirects to dashboard
5. If DB error: rolls back + shows error

### `company/switch.php` — Switch Active Company
1. Reads `id` from GET
2. Finds company in DB
3. Finds the **active financial year** for that company
4. If no active FY exists → shows error and sends back to select
5. Sets all session variables and redirects to dashboard

### `company/delete.php` — Delete Company (Two-step)
- **Step 1 (GET `?action=confirm&id=X`):** Shows confirmation screen with a warning
- **Step 2 (POST):** Actually deletes from DB (uses transaction)
  - If the deleted company was the active one → clears company session variables

---

## 📅 Financial Year Module (`financial-year/`)

| File | Purpose |
|------|---------|
| `index.php` | Lists all financial years for the active company in a table |
| `create.php` | Form to create a new financial year |
| `save.php` | POST handler (validate → check duplicate → insert) |
| `activate.php` | GET handler to set a financial year as active |
| `repository.php` | DB functions: get, getAll, create, update, activate |
| `validator.php` | `validateFinancialYear()` — checks name, dates, end > start |

### Key logic in `repository.php`:
- `activateFinancialYear()` uses a **transaction** to:
  1. First set ALL financial years of that company to `is_active = 0`
  2. Then set the target FY to `is_active = 1`
  - This ensures only ONE financial year is active at a time

### Key logic in `save.php`:
- First validates with `validateFinancialYear()`
- Then checks if a FY with the same name already exists (`financialYearExists()`)
- Uses **flash messages** to communicate success/failure

---

## 📊 Chart of Accounts (`chart-of-accounts/`)

### `index.php` — Menu Page
A simple menu page with two options:
- **Groups** → links to `groups/`
- **Ledgers** → currently **disabled** (not yet implemented)

---

## 👥 Groups Module (`groups/`)

Groups in accounting = categories under which Ledgers are organized (e.g., "Bank Accounts", "Sundry Debtors").

| File | Purpose |
|------|---------|
| `index.php` | Menu: Create / Alter / Display / Delete |
| `create.php` | Complex Tally-like form with many accounting fields |
| `alter.php` | Edit an existing group |
| `display.php` | View group details (read-only) |
| `edit.php` | Alternative edit form (detailed) |
| `save.php` | POST handler for create |
| `update.php` | POST handler for alter |
| `delete.php` | Delete group (with dependency checks) |
| `repository.php` | `getAllGroups()` — fetches all groups with all columns |
| `validator.php` | Validation rules for group data |

### `groups/create.php` — Tally-Style Group Form
This is the most complex form. It collects:
- Name, Alias
- **Parent Group** (dropdown of all existing groups)
- **Nature** (Assets, Liabilities, Income, Expenses)
- `affects_gross_profit`, `behaves_like_subledger`, `net_debit_credit_reporting`, `used_for_calculation`
- `allocation_method`
- **HSN/SAC details** (for GST)
- **GST rate details** (taxability type, GST rate)

### `groups/delete.php` — Delete with Dependency Checks
Before allowing deletion:
1. Checks if it's a **system group** (`is_system = 1`) → system groups cannot be deleted
2. Checks if it has **child groups** → cannot delete if children exist
3. Checks if it has **ledgers** associated → cannot delete if used by ledgers
4. Shows the appropriate error message at the confirmation screen

---

## 🔑 Session Variables (The "State" of the App)

| Variable | Set Where | Meaning |
|----------|-----------|---------|
| `$_SESSION['user_id']` | login.php | Logged-in user's ID |
| `$_SESSION['company_id']` | login.php / switch.php / save.php | Active company |
| `$_SESSION['company_name']` | switch.php / save.php | Active company name |
| `$_SESSION['username']` | login.php | Login username |
| `$_SESSION['user_name']` | login.php | Display name |
| `$_SESSION['role']` | login.php | User role |
| `$_SESSION['financial_year_id']` | login.php / switch.php | Active FY ID |
| `$_SESSION['financial_year_name']` | login.php / switch.php | Active FY name |
| `$_SESSION['financial_year_start']` | switch.php | FY start date |
| `$_SESSION['financial_year_end']` | switch.php | FY end date |

---

## 🗄️ Database Tables (from context)

| Table | Purpose |
|-------|---------|
| `users` | Login accounts (id, company_id, name, username, password, role, is_active) |
| `companies` | Company profiles (name, address, GSTIN, etc.) |
| `financial_years` | Accounting periods per company (is_active flag) |
| `groups` | Account group hierarchy (parent_id, nature, is_system) |
| `ledgers` | Individual accounts (belongs to a group) |

---

## 🔄 Common Patterns Used Throughout the Code

### 1. Post-Redirect-Get (PRG) Pattern
Every form submission:
1. POST → validates
2. If errors: saves errors to session → **redirect** back to form → form reads from session
3. If success: does DB work → **redirect** to success page

This prevents duplicate form submissions on refresh.

### 2. Repository Pattern
Database queries are separated into `repository.php` files. Pages call functions like `findCompany()`, `getAllCompanies()`, `getFinancialYears()` — keeping SQL out of view files.

### 3. Flash Messages
The `includes/flash.php` `setFlash()` / `getFlash()` pair allows messages to survive a redirect and display exactly once.

### 4. `declare(strict_types=1)`
Every file starts with this — enforces PHP strict type checking for all function calls.

### 5. `htmlspecialchars()` on all output
All user data displayed in HTML is escaped via `htmlspecialchars()` to prevent XSS attacks.

### 6. PDO Prepared Statements
All DB queries use `:named` or `?` placeholders — never string concatenation — preventing SQL injection.

---

## 🚧 Work in Progress (Disabled Features)
- Ledgers (under Chart of Accounts)
- Vouchers
- Day Book
- Banking
- Balance Sheet
- Profit & Loss
- Stock Summary
- Ratio Analysis
- Dashboard reports

These exist as placeholder links in the dashboard menu but are not yet implemented.
