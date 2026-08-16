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

## 2. File & Folder Guide

### Setup & Configurations
- **`queries.txt`**: Database tables setup commands.
- **`config/app.php`**: Settings like site title and website path URL.
- **`config/database.php`**: Code to connect to the MySQL database.
- **`index.php` (Root)**: The landing page that checks if the database is successfully connected.

### Look, Feel & Interactions
- **`assets/css/`**: Styling sheets (`style.css` and `gateway.css`) for layout, inputs, and dashboard look.
- **`assets/js/gateway.js`**: Handles keyboard shortcut events on the dashboard (like `F1` for help, `F3` for company page).
- **`assets/js/app.js`**: Standard page loading alert setup.

### Layout Templates & Tools (in `includes/`)
- **`includes/init.php`**: Starts application settings and sessions.
- **`includes/auth.php`**: Verification check that redirects logged-out users to the login screen.
- **`includes/header.php`**: Renders the top menu, sidebar navigation, and user login information.
- **`includes/footer.php`**: Closes visual containers at the bottom of the pages.
- **`includes/context.php`**: Code that retrieves active company and financial year names.
- **`includes/flash.php` & `flash-display.php`**: Code that displays success or error alert banners.

### Core Modules
- **`auth/`**:
  - `login.php`: The login screen.
  - `logout.php`: Log out screen that clears active sessions.
- **`dashboard/`**:
  - `index.php`: The primary Tally-style "Gateway of FinnServ" screen.
  - Controls views of the current period, dates, and shortcut options.
- **`company/`**:
  - `index.php`: Renders the company profile page.
  - `edit.php`: Form to edit company profile.
  - `save.php`: Saves edited details.
  - `validator.php` & `repository.php`: Basic data checks (email, pincode formats) and database queries.
- **`financial-year/`**:
  - `index.php`: Lists all accounting years.
  - `create.php`: Form to add a new accounting period.
  - `activate.php`: Activates a specific accounting year.
  - `save.php`: Saves a new year.
  - `validator.php` & `repository.php`: Basic date checks (start date before end date) and database queries.

---

## 3. Quick Local Setup
1. Create a MySQL database named `finnserv` and import the tables using the SQL query commands in `queries.txt`.
2. Move the project folder to your server's web root (e.g. `htdocs/Internship/FinnServ`).
3. Make sure database settings in `config/database.php` match your local environment credentials.
4. Run the project URL in your browser: `http://localhost/Internship/FinnServ/`.
