<?php
/*************** SECURE SESSION SETTINGS ***************/
ini_set('session.cookie_httponly', 1);   // Prevent JS access to session cookie
ini_set('session.use_only_cookies', 1);  // Use cookies only
// ini_set('session.cookie_secure', 1);  // Enable if using HTTPS

session_start();

/*************** PASSWORD SETUP (Normally from DB) ***************/
$storedPasswordHash = password_hash("1234", PASSWORD_DEFAULT);

/*************** LOGIN ATTEMPT LIMIT ***************/
$maxAttempts = 5;
$lockTime = 300; // 5 minutes

if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

if (!isset($_SESSION['lock_time'])) {
    $_SESSION['lock_time'] = 0;
}

/*************** CSRF TOKEN ***************/
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/*************** LOGIN HANDLER ***************/
$error = "";

if (isset($_POST['login'])) {

    // Check if user is locked
    if ($_SESSION['attempts'] >= $maxAttempts && time() < $_SESSION['lock_time']) {
        $error = "Too many login attempts. Try again later.";
    } 
    // CSRF validation
    elseif (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Invalid request.";
    } 
    else {
        $password = trim($_POST['password']);

        if (password_verify($password, $storedPasswordHash)) {

            // Successful login
            session_regenerate_id(true);
            $_SESSION['authenticated'] = true;
            $_SESSION['attempts'] = 0;

            header("Location: viewtable.php");
            exit();
        } else {
            $_SESSION['attempts']++;

            if ($_SESSION['attempts'] >= $maxAttempts) {
                $_SESSION['lock_time'] = time() + $lockTime;
            }

            $error = "Invalid login credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Secure Login</title>
</head>
<body>
<div class="clipboard">
    <div class="paper">
        <h2>Admin Login</h2>
        <div class="badge">Secure Access</div>

        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <button type="submit" name="login">Login</button>
        </form>

        <div class="back-link">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>
</div>

</body>
</html>
