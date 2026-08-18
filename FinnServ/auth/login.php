<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';

// Redirect user if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'dashboard/');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } else {
        // Fetch user from DB
        $stmt = $pdo->prepare("SELECT id, company_id, name, username, password, role, is_active FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        // Verify credentials and status
        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'Invalid username or password.';
        } elseif ((int)$user['is_active'] !== 1) {
            $error = 'This account is inactive.';
        } else {
            // Establish session context
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['company_id'] = (int)$user['company_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Fetch active financial year for company context
            $fyStmt = $pdo->prepare("SELECT id, name, start_date, end_date FROM financial_years WHERE company_id = :company_id AND is_active = 1 LIMIT 1");
            $fyStmt->execute(['company_id' => $user['company_id']]);
            $financialYear = $fyStmt->fetch();

            if ($financialYear) {
                $_SESSION['financial_year_id'] = (int)$financialYear['id'];
                $_SESSION['financial_year_name'] = $financialYear['name'];
            }

            header('Location: ' . BASE_URL . 'dashboard/');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?= htmlspecialchars(APP_NAME) ?></title>
</head>
<body>
    <main>
        <h1>FinnServ</h1>
        <h2>Login</h2>
        <?php if ($error !== ''): ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>