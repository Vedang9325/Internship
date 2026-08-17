# FinnServ

FinnServ is a simple PHP and MySQL accounting web application inspired by the classic Tally-style workflow. It provides a login-protected dashboard, company profile management, and financial year management as the foundation for a broader accounting system.

The project is designed for a local XAMPP/PHP learning environment and keeps the codebase easy to read: plain PHP, PDO database access, session-based authentication, and separate folders for each application module.

## Features

- Session-based user login and logout
- Tally-inspired "Gateway of FinnServ" dashboard
- Active company context shown across private pages
- Active financial year context shown across private pages
- Company profile view and edit screen
- Financial year listing and creation
- Financial year activation
- Flash messages for success and error feedback
- PDO prepared statements for database queries
- Basic server-side validation for company and financial year forms
- Responsive CSS for dashboard, forms, tables, and navigation

## Tech Stack

- PHP 8+
- MySQL / MariaDB
- PDO for database access
- HTML, CSS, and vanilla JavaScript
- XAMPP-compatible local setup

## Project Structure

```text
FinnServ/
├── assets/
│   ├── css/
│   │   ├── gateway.css
│   │   └── style.css
│   └── js/
│       ├── app.js
│       └── gateway.js
├── auth/
│   ├── login.php
│   └── logout.php
├── company/
│   ├── edit.php
│   ├── index.php
│   ├── repository.php
│   ├── save.php
│   └── validator.php
├── config/
│   ├── app.php
│   └── database.php
├── dashboard/
│   └── index.php
├── financial-year/
│   ├── activate.php
│   ├── create.php
│   ├── edit.php
│   ├── index.php
│   ├── repository.php
│   ├── save.php
│   └── validator.php
├── includes/
│   ├── auth.php
│   ├── context.php
│   ├── flash-display.php
│   ├── flash.php
│   ├── footer.php
│   ├── header.php
│   └── init.php
├── index.php
├── queries.txt
└── README.md
```

## How It Works

Most private pages follow this flow:

1. Load application configuration and database connection through `includes/init.php`.
2. Start or resume a PHP session.
3. Verify that the user is logged in through `includes/auth.php`.
4. Load the current company and active financial year through `includes/context.php`.
5. Render the shared layout from `includes/header.php` and `includes/footer.php`.
6. Run module-specific database reads or writes through repository files.

The root `index.php` is currently a database connection smoke test. The main application entry after login is:

```text
http://localhost/Internship/FinnServ/dashboard/
```

## Local Setup

### 1. Move the Project

Place the project inside your XAMPP `htdocs` directory:

```text
C:\xampp\htdocs\Internship\FinnServ
```

### 2. Create the Database

Open phpMyAdmin or a MySQL client and run the SQL from:

```text
queries.txt
```

This creates the `finnserv` database and the core accounting tables.

### 3. Configure Database Credentials

Check `config/database.php` and update these constants if your local setup is different:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'finnserv');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 4. Configure Base URL

Check `config/app.php`:

```php
define('BASE_URL', '/Internship/FinnServ/');
```

If you place the folder somewhere else under `htdocs`, update `BASE_URL` to match that path.

### 5. Add Initial Data

The schema file creates tables, but it does not currently seed a default company, user, or active financial year. Add at least:

- One record in `companies`
- One active record in `financial_years`
- One active record in `users`

Passwords must be stored as PHP password hashes because login uses `password_verify()`.

You can generate a password hash with:

```php
<?php
echo password_hash('admin123', PASSWORD_DEFAULT);
```

Then store the generated hash in the `users.password` column.

### 6. Run the App

Start Apache and MySQL from XAMPP, then open:

```text
http://localhost/Internship/FinnServ/
```

For the login page:

```text
http://localhost/Internship/FinnServ/auth/login.php
```

## Main Modules

### Authentication

Located in `auth/`.

- `login.php` checks username, password hash, account status, and loads session data.
- `logout.php` clears the active session and redirects to login.

### Dashboard

Located in `dashboard/`.

- `index.php` renders the Tally-inspired Gateway of FinnServ screen.
- `assets/js/gateway.js` handles keyboard shortcuts and quick actions.

### Company

Located in `company/`.

- `index.php` displays the active company profile.
- `edit.php` displays the company edit form.
- `save.php` validates and saves company changes.
- `repository.php` contains database functions.
- `validator.php` contains company validation rules.

### Financial Year

Located in `financial-year/`.

- `index.php` lists all financial years for the active company.
- `create.php` displays the new financial year form.
- `save.php` validates and creates financial year records.
- `activate.php` switches the active financial year.
- `repository.php` contains database functions.
- `validator.php` contains financial year validation rules.

## Database Tables

The schema in `queries.txt` includes:

- `companies`
- `users`
- `groups`
- `ledgers`
- `voucher_types`
- `vouchers`
- `voucher_entries`
- `financial_years`

Some accounting tables are already present in the database schema even though their UI screens are not fully implemented yet. This gives the project room to grow into ledgers, groups, voucher entry, reports, GST, stock items, and accounting statements.

## Current Limitations

- `financial-year/edit.php` is currently empty, so the financial year edit link is not implemented yet.
- Financial year activation currently uses a GET request. It should be converted to POST before production use.
- CSRF protection is not implemented yet for form submissions.
- `queries.txt` does not include seed data for first login.
- Role-based permissions are not enforced beyond storing a user role in the session.
- Several dashboard menu items are placeholders for future accounting modules.

## Suggested Roadmap

1. Add seed SQL for default company, active financial year, and admin user.
2. Implement financial year editing.
3. Add CSRF token helpers for all forms and state-changing actions.
4. Add role-based access checks for Admin, Manager, Accountant, and Viewer.
5. Build ledger and group management screens.
6. Add voucher entry workflows for payment, receipt, contra, journal, sales, and purchase.
7. Add accounting reports such as Trial Balance, Profit and Loss, and Balance Sheet.
8. Improve dashboard shortcuts and remove placeholder alerts.

## Development Notes

- Keep database reads and writes inside module `repository.php` files.
- Keep form validation inside module `validator.php` files.
- Escape output with `htmlspecialchars()` when rendering user-controlled values.
- Use prepared statements for all SQL that includes dynamic values.
- Run PHP syntax checks before submitting changes:

```powershell
C:\xampp\php\php.exe -l path\to\file.php
```

## License

This project is currently intended for educational and internship use. Add a license file before distributing it publicly.
