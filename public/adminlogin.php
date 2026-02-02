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
    <title>Secure Login</title>
</head>
<body>

<h2>Login</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
    <label for="password">Password</label><br>
    <input type="password" name="password" id="password" required><br><br>

    <!-- CSRF Token -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <button type="submit" name="login">Login</button>
</form>

</body>
</html>
