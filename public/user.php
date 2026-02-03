<?php
require_once '../config/db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
        if (!$email) {
            $error = 'Invalid email address';
        } else {
            $sql = "SELECT user_id, email FROM user WHERE email = ?";
            $stmt = $pdo->prepare($sql);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="clipboard">
        <div class="paper">
            <div class="badge">User Portal</div>
            <h2>User Access</h2>
            <p class="subtitle">Enter your email to continue</p>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required placeholder="Enter your email">
                </div>
                
                <button type="submit">Continue</button>
                
                <div class="signup-section">
                    <p>Don't have an account?</p>
                    <a href="usersignup.php">
                        <button type="button">Sign Up</button>
                    </a>
                </div>
            </form>

            <div class="back-link">
                <a href="index.php">← Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
