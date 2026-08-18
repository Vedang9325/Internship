# FinnServ - Simple Accounting Web Application

FinnServ is a simple PHP-based accounting web application with a dashboard designed to look and feel like the classic **Tally** desktop software.

---

## 1. How the Application Works (The Process)

When you open or click any page in the application, it follows this simple sequence behind the scenes:
1. **Connects to Database**: Connects the code to the MySQL database.
2. **Checks Login Status**: Verifies if you are logged in. If not, it redirects you to the login screen.
3. **Loads Company Info**: Automatically gets the active company name and financial year dates from the database to display at the top of the screen.
4. **Displays the Page**: Combines the navigation layout (sidebar and topbar) with the page content.

---

## 2. Quick Local Setup
1. Create a MySQL database named `finnserv` and import the tables using the SQL query commands in `queries.txt`.
2. Move the project folder to your server's web root (e.g. `htdocs/Internship/FinnServ`).
3. Make sure database settings in `config/database.php` match your local environment credentials.
4. Run the project URL in your browser: `http://localhost/Internship/FinnServ/dashboard/`.

---

## 3. Comprehensive File & Folder Guide

### 📂 Setup & Configurations
- **`queries.txt`**: SQL queries to set up the tables (`companies`, `users`, `financial_years`, `ledgers`, etc.) on database initialization.
- **`config/app.php`**: Holds application constants (`APP_NAME`, `APP_VERSION`, `BASE_URL`, `APP_ENV`) and sets the timezone to `Asia/Kolkata`.
- **`config/database.php`**: Sets up the PDO database handle `$pdo` with error reporting enabled, default fetch set to associative array, and prepared statement emulation disabled.
- **`index.php` (Root)**: Entry router redirecting to `dashboard/` if authenticated, or `auth/login.php` if not.

### 📂 Look, Feel & Interactions
- **`assets/css/style.css`**: CSS stylesheet rules for the Modern Classic layout (sidebar navigation panel, cards, data tables, layout pages).
- **`assets/css/gateway.css`**: CSS stylesheet rules for the retro Tally-inspired layout.
- **`assets/css/company.css`**: CSS stylesheet rules for forms and fields layout.
- **`assets/js/gateway.js`**: Keydown event listener that captures shortcut hotkeys (e.g., `F1` for Help, `F2` for Date, `F3` for Company menu, `G` for Go To) and redirects or triggers alerts accordingly.
- **`assets/js/app.js`**: Script log initializing confirmation on page load.

### 📂 Layout Templates & Helpers (in `includes/`)
- **`includes/init.php`**: Imports app/db configurations and initializes session context.
- **`includes/auth.php`**: Active session validator check. Redirects non-logged-in users to the login screen.
- **`includes/context.php`**: Contains `loadCompanyContext()` which queries company data and active financial years, storing them in `$_SESSION`.
- **`includes/flash.php`**: Helper functions `setFlash()` and `getFlash()` to store and read temporary session feedback alerts.
- **`includes/flash-display.php`**: Outputs active alerts inside standard CSS alert boxes.
- **`includes/header.php`**: Page header shell for Modern Classic layouts, rendering sidebars and top navigation details.
- **`includes/footer.php`**: Page footer shell closing grid layouts and HTML tags.

### 📂 Core Application Modules

#### Authentication Module (`auth/`)
- **`auth/login.php`**: Authenticates username and password via `password_verify()`, initiates active session parameters, and redirects to dashboard.
- **`auth/logout.php`**: Wipes session values, clears active cookies, destroys session context, and redirects back to the login page.

#### Dashboard Module (`dashboard/`)
- **`dashboard/index.php`**: Main Tally-style landing portal showing current periods, current dates, active company summaries, and master action options.

#### Company Module (`company/`)
- **`company/repository.php`**: Database CRUD methods `findCompany()`, `updateCompany()`, and `getAllCompanies()`.
- **`company/index.php`**: Modern classic details view showing active company details.
- **`company/edit.php`**: Modern classic company edit view. Routes updates to `company/update.php`.
- **`company/alter.php`**: Tally-style company update form layout.
- **`company/create.php`**: Tally-style new company registration form layout.
- **`company/save.php`**: Handles new company creation inside a SQL transaction block, creates initial active FY, sets context, and redirects to dashboard.
- **`company/update.php`**: Validates form inputs, runs company table updates, syncs active session variables, and redirects to dashboard.
- **`company/menu.php`**: Change Company action selector panel (create, alter, select).
- **`company/select.php`**: Lists all companies to switch active context.
- **`company/switch.php`**: Fetches new active company details, validates its active FY, and redirects back to gateway.
- **`company/session-test.php`**: Diagnostic view dump displaying all session context values.

#### Financial Year Module (`financial-year/`)
- **`financial-year/repository.php`**: DB actions `getFinancialYears()`, `getFinancialYear()`, `financialYearExists()`, `createFinancialYear()`, `updateFinancialYear()`, and `activateFinancialYear()`.
- **`financial-year/validator.php`**: Checks input names, verify bounds, and validates that start date comes before end date.
- **`financial-year/index.php`**: List view showing accounting years, status indicators, and activation switch links.
- **`financial-year/create.php`**: Setup wizard form to configure a new accounting period.
- **`financial-year/save.php`**: Form submit target processor. Runs validation, duplicate check checks, saves years, sets alert flashes, and redirects back to listing.
- **`financial-year/activate.php`**: Triggers database activation updates and redirects back to index.
- **`financial-year/edit.php`**: Empty placeholder for financial year editor page.
