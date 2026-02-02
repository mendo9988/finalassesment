<?php
require_once '../config/db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
        if (!$email) {
            $error = 'Invalid email address';
        } else {
            $stmt = $pdo->prepare("SELECT user_id, email FROM user WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                // STORE REAL VALUES FROM DB
                $_SESSION['user_id'] = (int)$user['user_id'];
                $_SESSION['user_email'] = $user['email'];
    
                header('Location: fillform.php');
                exit;
            } else {
                $error = 'Email not found';
            }
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
</head>
<body>

<h2>User Access</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Email</label><br>
    <input type="email" name="email" required>
    <br><br>
    <button type="submit">Continue</button> <br>
    <p>Don't have an account?
        <a href="usersignup.php"><button type="button">Sign up</button></a>
    </p>
</form>

</body>
</html>