# FinnServ - Tally-Style Accounting & Business Management Web Application

FinnServ is a PHP & MySQL accounting application designed strictly to replicate the look, feel, layout, and user experience of classic desktop **Tally ERP / Tally Prime**. 

The application utilizes a dark green topbar header, sky-blue gateway navigation blocks, topbar keyboard shortcut menus, and single-screen data entry forms built with modern strict PHP typing (`declare(strict_types=1)`), PDO database abstraction, and session-driven company/financial year context management.

---

## Table of Contents
1. [Application Architecture & Execution Lifecycle](#1-application-architecture--execution-lifecycle)
2. [Database Schema Overview (`queries.txt`)](#2-database-schema-overview-queriestxt)
3. [Exhaustive File & Folder Guide](#3-exhaustive-file--folder-guide)
   - [📂 Project Root](#-project-root)
   - [📂 Application Configurations (`config/`)](#-application-configurations-config)
   - [📂 Shared Helpers & Guards (`includes/`)](#-shared-helpers--guards-includes)
   - [📂 Visual Assets & Stylesheets (`assets/`)](#-visual-assets--stylesheets-assets)
   - [📂 Authentication Module (`auth/`)](#-authentication-module-auth)
   - [📂 Gateway Dashboard (`dashboard/`)](#-gateway-dashboard-dashboard)
   - [📂 Company Management Module (`company/`)](#-company-management-module-company)
   - [📂 Financial Year Module (`financial-year/`)](#-financial-year-module-financial-year)
4. [Local Environment Setup Instructions](#4-local-environment-setup-instructions)

---

## 1. Application Architecture & Execution Lifecycle

Every user request made within FinnServ follows a strict operational pipeline:

1. **Bootstrap & Environment Setup (`includes/init.php`)**:
   - Loads global constants (`BASE_URL`, `APP_NAME`, `APP_ENV`, timezone) from `config/app.php`.
   - Initializes PDO database handle `$pdo` from `config/database.php`.
   - Starts or resumes PHP session handling safely.
   - Emits strict HTTP headers (`Cache-Control: no-store...`) to ensure secure data handling and prevent stale browser caching of accounting records.

2. **Authentication Guard (`includes/auth.php`)**:
   - Verifies the existence of `$_SESSION['user_id']`.
   - If unauthenticated, immediately redirects the client to `auth/login.php`.

3. **Active Context Guard**:
   - Checks if an active company context (`$_SESSION['company_id']`) is set.
   - If missing or unselected, redirects the user to the Tally-style company selection interface (`company/select.php`).

4. **Keyboard Shortcut Integration (`assets/js/gateway.js`)**:
   - Listens globally for Tally hotkeys:
     - `F1`: Help trigger
     - `F2`: Date change trigger
     - `F3`: Switch / Select Company menu
     - `G` (when not typing in form inputs): Quick "Go To" menu shortcut.

---

## 2. Database Schema Overview (`queries.txt`)

The database schema (`finnserv`) is defined in `queries.txt` and contains 8 primary relational tables:

* **`companies`**: Stores core business metadata (company name, mailing name, street address, state, country, pincode, phone, email, GSTIN).
* **`users`**: Contains system user accounts linked to specific companies with role-based permissions (`Admin`, `Manager`, `Accountant`, `Viewer`) and hashed passwords (`password_hash()`).
* **`financial_years`**: Manages accounting periods (`start_date`, `end_date`, `is_active` flag) for each company.
* **`groups`**: Hierarchical ledger groups (`Assets`, `Liabilities`, `Income`, `Expenses`) with optional parent-child nesting.
* **`ledgers`**: Accounting chart of accounts linked to groups, maintaining opening balances (`Debit` or `Credit`), GST numbers, mailing addresses, and banking configurations.
* **`voucher_types`**: Configurable accounting voucher templates (Payment, Receipt, Contra, Journal, Sales, Purchase) supporting automatic or manual numbering.
* **`vouchers`**: Accounting header records capturing voucher numbers, dates, reference numbers, narrations, and user audit trails.
* **`voucher_entries`**: Double-entry line items for each voucher containing Debit and Credit amounts mapped to ledgers.

---

## 3. Exhaustive File & Folder Guide

---

### 📂 Project Root

#### [`index.php`](file:///c:/xampp/htdocs/Internship/FinnServ/index.php)
* **Purpose**: Primary router and entry point for the root URL (`/Internship/FinnServ/`).
* **Logic**: Evaluates whether `$_SESSION['user_id']` is populated. If authenticated, issues a `302` redirect to `dashboard/`; otherwise, redirects to `auth/login.php`.

#### [`queries.txt`](file:///c:/xampp/htdocs/Internship/FinnServ/queries.txt)
* **Purpose**: Master SQL database creation and table initialization script.
* **Details**: Contains DDL statements for table setup (`companies`, `users`, `groups`, `ledgers`, `voucher_types`, `vouchers`, `voucher_entries`, `financial_years`), constraints, foreign keys, and indexes.

#### [`README.md`](file:///c:/xampp/htdocs/Internship/FinnServ/README.md)
* **Purpose**: Comprehensive technical documentation detailing the codebase, file structures, database schemas, and setup instructions.

---

### 📂 Application Configurations (`config/`)

#### [`config/app.php`](file:///c:/xampp/htdocs/Internship/FinnServ/config/app.php)
* **Purpose**: Global application constants definition.
* **Defined Constants**:
  - `APP_NAME`: Name of the application (`FinnServ`).
  - `APP_VERSION`: Current release version (`1.0.0`).
  - `BASE_URL`: Relative root path for HTTP routes (`/Internship/FinnServ/`).
  - `APP_ENV`: Deployment mode (`development` or `production`).
  - `date_default_timezone_set('Asia/Kolkata')`: Enforces Indian Standard Time across all date/time calculations.

#### [`config/database.php`](file:///c:/xampp/htdocs/Internship/FinnServ/config/database.php)
* **Purpose**: Database connection setup using PHP Data Objects (PDO).
* **Details**:
  - Defines DB host (`localhost`), DB name (`finnserv`), username (`root`), and password.
  - Instantiates `$pdo` with error reporting set to `ERRMODE_EXCEPTION`, default fetch mode set to `FETCH_ASSOC`, and prepared statement emulation disabled (`ATTR_EMULATE_PREPARES => false`).

---

### 📂 Shared Helpers & Guards (`includes/`)

#### [`includes/init.php`](file:///c:/xampp/htdocs/Internship/FinnServ/includes/init.php)
* **Purpose**: Core application bootstrapper.
* **Logic**: Included at the top of every backend endpoint. Requires `config/app.php` and `config/database.php`, initializes `session_start()` if uninitialized, and applies HTTP anti-caching headers.

#### [`includes/auth.php`](file:///c:/xampp/htdocs/Internship/FinnServ/includes/auth.php)
* **Purpose**: Authentication enforcement guard.
* **Logic**: Ensures session is started and verifies `$_SESSION['user_id']`. Immediately redirects unauthenticated requests to `auth/login.php`.

#### [`includes/flash.php`](file:///c:/xampp/htdocs/Internship/FinnServ/includes/flash.php)
* **Purpose**: Session-based temporary message helper.
* **Functions**:
  - `setFlash(string $type, string $message)`: Stores a key-value notification pair (`type` and `message`) inside `$_SESSION['flash']`.
  - `getFlash()`: Reads and unsets `$_SESSION['flash']`, returning the alert message for display.

---

### 📂 Visual Assets & Stylesheets (`assets/`)

#### [`assets/css/gateway.css`](file:///c:/xampp/htdocs/Internship/FinnServ/assets/css/gateway.css)
* **Purpose**: Core CSS stylesheet implementing the Tally visual design system.
* **Style Rules**:
  - Topbar header styles (`.gateway-topbar`, `.gateway-brand`, `.gateway-shortcuts`, `.gateway-logout`) with Tally's signature dark green palette (`#123b38`).
  - Titlebar header styles (`.gateway-titlebar`) in light sky blue (`#8cc8e8`).
  - Gateway main split view grid (`.gateway-main`, `.gateway-information`, `.gateway-menu`) with menu highlight hover state (`#fbbf24`).
  - Tally footer (`.gateway-footer`).

#### [`assets/css/company.css`](file:///c:/xampp/htdocs/Internship/FinnServ/assets/css/company.css)
* **Purpose**: CSS rules for Tally-style input forms, menus, and selection panels.
* **Style Rules**:
  - Tally Company Menu box styling (`.company-menu-panel`, `.company-menu-item`).
  - Tally input form grid rows (`.company-form-row`, `.company-form-row-large`) with focus highlight (`#fff3b0`).
  - Fixed Tally action bar (`.company-form-actions`, `.company-action-button`).
  - Tally company selector listing box (`.company-select-panel`, `.company-select-item`).

#### [`assets/css/style.css`](file:///c:/xampp/htdocs/Internship/FinnServ/assets/css/style.css)
* **Purpose**: Core CSS resets, base HTML typography, card wrappers, and data tables.
* **Style Rules**:
  - Universal CSS box reset (`*`, `:root` variables, `body`, `a`).
  - Table wrappers (`.table-wrapper`, `.data-table`, `th`, `td`).
  - Status badges (`.status-badge`, `.status-active`, `.status-inactive`).
  - Table actions and buttons (`.btn`, `.btn-primary`, `.table-actions`).

#### [`assets/js/gateway.js`](file:///c:/xampp/htdocs/Internship/FinnServ/assets/js/gateway.js)
* **Purpose**: Client-side keyboard interaction and shortcut listener script.
* **Logic**:
  - Binds click listeners to buttons with `data-shortcut` attributes.
  - Listens to global `keydown` events:
    - Pressing `F1` invokes `handleGatewayAction('help')`.
    - Pressing `F2` invokes `handleGatewayAction('date')`.
    - Pressing `F3` redirects the browser to `company/menu.php`.
    - Pressing `G` (when not focused inside input elements) triggers the Go-To menu alert.

---

### 📂 Authentication Module (`auth/`)

#### [`auth/login.php`](file:///c:/xampp/htdocs/Internship/FinnServ/auth/login.php)
* **Purpose**: Handles user login, password verification, and session setup.
* **Workflow**:
  - Checks if user is already logged in (`$_SESSION['user_id']`), redirecting to `dashboard/` if so.
  - On `POST` request, sanitizes username and fetches user account from database.
  - Validates password via `password_verify($password, $user['password'])` and checks `is_active === 1`.
  - Regenerates session ID (`session_regenerate_id(true)`) and sets session context variables (`user_id`, `company_id`, `username`, `user_name`, `role`).
  - Fetches the active financial year for the company and stores `financial_year_id` and `financial_year_name` in `$_SESSION`.
  - Redirects user to `dashboard/`.

#### [`auth/logout.php`](file:///c:/xampp/htdocs/Internship/FinnServ/auth/logout.php)
* **Purpose**: Securely logs out the user and clears session state.
* **Workflow**:
  - Unsets `$_SESSION` array variables.
  - Expires and deletes the session cookie in the user's browser.
  - Destroys session context via `session_destroy()`.
  - Redirects browser to `auth/login.php`.

---

### 📂 Gateway Dashboard (`dashboard/`)

#### [`dashboard/index.php`](file:///c:/xampp/htdocs/Internship/FinnServ/dashboard/index.php)
* **Purpose**: Main Tally Gateway landing dashboard ("Gateway of FinnServ").
* **Workflow**:
  - Includes `includes/init.php` and `includes/auth.php`.
  - Enforces active company check (`$_SESSION['company_id']`).
  - Displays Tally topbar with keyboard shortcut buttons.
  - Displays Tally titlebar with `F2: Date`, `F3: Company`, and `F1: Help` shortcuts.
  - Left Panel: Renders active financial year period bounds, current date, company name, date of last entry, and active session context box.
  - Right Panel: Renders Tally Gateway menu sections:
    - **MASTERS**: Links to Company Menu (`company/menu.php`) and Financial Years (`financial-year/`).
    - **TRANSACTIONS**: Placeholders for Vouchers and Day Book.
    - **UTILITIES**: Placeholder for Banking.
    - **REPORTS**: Placeholders for Balance Sheet, Profit & Loss, Stock Summary, and Ratio Analysis.
    - **Quit**: Redirects to Logout.

---

### 📂 Company Management Module (`company/`)

#### [`company/menu.php`](file:///c:/xampp/htdocs/Internship/FinnServ/company/menu.php)
* **Purpose**: Tally-style "Company Info / Change Company" menu screen.
* **Renders Options**:
  - `Create Company`: Navigates to `company/create.php`.
  - `Alter Company`: Navigates to `company/alter.php`.
  - `Select Company`: Navigates to `company/select.php`.
  - `Delete Company`: Navigates to `company/delete.php`.
  - Renders currently active company status box and back link to Gateway dashboard.

#### [`company/select.php`](file:///c:/xampp/htdocs/Internship/FinnServ/company/select.php)
* **Purpose**: Tally-style panel listing all companies in the system for switching context.
* **Workflow**:
  - Queries all companies via `getAllCompanies($pdo)`.
  - Highlights currently active company with `(ACTIVE)` badge.
  - Clicking any company routes request to `company/switch.php?id={id}`.

#### [`company/delete.php`](file:///c:/xampp/htdocs/Internship/FinnServ/company/delete.php)
* **Purpose**: Tally-style delete company confirmation/execution screen.
* **Workflow**:
  - Lists all companies available in the system.
  - Clicking a company brings up a red styled confirmation dialog.
  - Confirmed deletion runs a transactional execution of `DELETE FROM companies WHERE id = ?`.
  - Clears context session variables if the currently active company is deleted.

#### [`company/switch.php`](file:///c:/xampp/htdocs/Internship/FinnServ/company/switch.php)
* **Purpose**: Processes switching active company context.
* **Workflow**:
  - Validates `GET` parameter `id`.
  - Retrieves company details using `findCompany()`.
  - Queries active financial year for selected company (`WHERE company_id = ? AND is_active = 1`).
  - Updates `$_SESSION['company_id']`, `$_SESSION['company_name']`, `$_SESSION['financial_year_id']`, `$_SESSION['financial_year_name']`, `$_SESSION['financial_year_start']`, and `$_SESSION['financial_year_end']`.
  - Redirects browser back to `dashboard/`.

#### [`company/create.php`](file:///c:/xampp/htdocs/Internship/FinnServ/company/create.php)
* **Purpose**: Tally-style input form for registering a new company and configuring its initial accounting year.
* **Form Inputs**: Company Name, Mailing Name, Address, Financial Year name (e.g. `2026-27`), Start Date, Books Beginning Date, State, Country, Pincode, Telephone, Email, GSTIN, and read-only base currency settings (`₹` / `INR`).
* **Submits To**: `company/save.php`.

#### [`company/save.php`](file:///c:/xampp/htdocs/Internship/FinnServ/company/save.php)
* **Purpose**: Form handler for new company registration.
* **Workflow**:
  - Validates company inputs and checks format of financial year strings and date ranges.
  - Opens a SQL transaction (`$pdo->beginTransaction()`).
  - Inserts new row into `companies` table.
  - Inserts active initial financial year record into `financial_years` table (`is_active = 1`).
  - Commits transaction (`$pdo->commit()`).
  - Updates session variables to point to newly created company and redirects to `dashboard/`.
  - On error, rolls back transaction and redirects back to `company/create.php` displaying flash validation errors.

#### [`company/alter.php`](file:///c:/xampp/htdocs/Internship/FinnServ/company/alter.php)
* **Purpose**: Tally-style company alteration form to update existing company information.
* **Workflow**:
  - Retrieves company ID from `GET` parameter `id` or falls back to active `$_SESSION['company_id']`.
  - Populates form with existing company details from database (`findCompany()`).
  - Submits modified details to `company/update.php`.

#### [`company/update.php`](file:///c:/xampp/htdocs/Internship/FinnServ/company/update.php)
* **Purpose**: Form handler for updating existing company details.
* **Workflow**:
  - Validates `company_id` and input strings.
  - Calls `updateCompany($pdo, $companyId, $data)` to execute SQL `UPDATE companies SET ... WHERE id = ?`.
  - If current active company was updated, syncs updated `$_SESSION['company_name']`.
  - Redirects to `dashboard/`.

#### [`company/validator.php`](file:///c:/xampp/htdocs/Internship/FinnServ/company/validator.php)
* **Purpose**: Input validation helper for company details.
* **Functions**: `validateCompanyData(array $data)` verifies required fields, max lengths, email formats, and 15-character GSTIN patterns.

#### [`company/repository.php`](file:///c:/xampp/htdocs/Internship/FinnServ/company/repository.php)
* **Purpose**: Database abstraction module for company records.
* **Functions**:
  - `findCompany(PDO $pdo, int $id)`: Retrieves company row by ID.
  - `getAllCompanies(PDO $pdo)`: Retrieves all registered companies ordered by name.
  - `updateCompany(PDO $pdo, int $id, array $data)`: Executes UPDATE SQL query for company properties.

---

### 📂 Financial Year Module (`financial-year/`)

#### [`financial-year/index.php`](file:///c:/xampp/htdocs/Internship/FinnServ/financial-year/index.php)
* **Purpose**: Tally-style financial year listing view for active company.
* **Workflow**:
  - Retrieves financial years via `getFinancialYears($pdo, $companyId)`.
  - Displays data table showing Financial Year Name, Start Date, End Date, Status (`Active` / `Inactive`), and action buttons.
  - Provides button `+ New Financial Year` linking to `financial-year/create.php`.
  - Displays activation link for inactive financial years linking to `financial-year/activate.php?id={id}`.

#### [`financial-year/create.php`](file:///c:/xampp/htdocs/Internship/FinnServ/financial-year/create.php)
* **Purpose**: Tally-style form interface for setting up a new accounting period.
* **Form Inputs**: Financial Year Name (e.g. `2027-28`), Start Date, End Date.
* **Submits To**: `financial-year/save.php`.

#### [`financial-year/save.php`](file:///c:/xampp/htdocs/Internship/FinnServ/financial-year/save.php)
* **Purpose**: Form handler for creating a new financial year.
* **Workflow**:
  - Validates input dates using `validateFinancialYear()`.
  - Checks for duplicate financial year names via `financialYearExists()`.
  - Calls `createFinancialYear($pdo, $companyId, $data)`.
  - Sets success flash message and redirects back to `financial-year/index.php`.

#### [`financial-year/activate.php`](file:///c:/xampp/htdocs/Internship/FinnServ/financial-year/activate.php)
* **Purpose**: Endpoint for activating a chosen financial year context.
* **Workflow**:
  - Accepts `GET` parameter `id`.
  - Invokes `activateFinancialYear($pdo, $financialYearId, $companyId)`.
  - Sets all other financial years for company to `is_active = 0` and sets target financial year to `is_active = 1`.
  - Sets flash message and redirects to `financial-year/index.php`.

#### [`financial-year/validator.php`](file:///c:/xampp/htdocs/Internship/FinnServ/financial-year/validator.php)
* **Purpose**: Validation helper for financial year fields.
* **Functions**: `validateFinancialYear(array $data)` verifies presence of name, date formats, and checks that `start_date < end_date`.

#### [`financial-year/repository.php`](file:///c:/xampp/htdocs/Internship/FinnServ/financial-year/repository.php)
* **Purpose**: Database abstraction module for financial year SQL operations.
* **Functions**:
  - `getFinancialYears(PDO $pdo, int $companyId)`: Fetches list of financial years.
  - `getFinancialYear(PDO $pdo, int $id, int $companyId)`: Fetches a single financial year record.
  - `financialYearExists(PDO $pdo, int $companyId, string $name)`: Checks for duplicate financial year names.
  - `createFinancialYear(PDO $pdo, int $companyId, array $data)`: Inserts new row.
  - `updateFinancialYear(PDO $pdo, int $id, int $companyId, array $data)`: Updates financial year row.
  - `activateFinancialYear(PDO $pdo, int $id, int $companyId)`: Executes transactional SQL query deactivating all periods for the company and activating the selected period.

---

## 4. Local Environment Setup Instructions

1. **Start Apache & MySQL**: Ensure XAMPP or your local PHP/MySQL environment is running.
2. **Setup Database**:
   - Create a MySQL database named `finnserv`.
   - Import the schema by executing the SQL statements in [`queries.txt`](file:///c:/xampp/htdocs/Internship/FinnServ/queries.txt).
3. **Verify Configuration**:
   - Ensure the project folder is located at `c:\xampp\htdocs\Internship\FinnServ`.
   - Ensure credentials in [`config/database.php`](file:///c:/xampp/htdocs/Internship/FinnServ/config/database.php) match your local MySQL settings (Default: `host=localhost`, `user=root`, `pass=`).
4. **Access Application**:
   - Open browser and navigate to `http://localhost/Internship/FinnServ/`.
   - Default login credentials:
     - **Username**: `admin`
     - **Password**: `password`
