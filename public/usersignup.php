<?php
require_once '../config/db.php';
session_start(); // Add this to store user info

$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    // check name
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors[] = "Name can contain only letters and spaces";
    } elseif (strlen($name) < 3) {
        $errors[] = "Name must be at least 3 characters long";
    }
    // checkname
    if (empty($email)){
        $errors[] = "Email is required";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is invalid.";
    } 
    
    // CHECK IF EMAIL ALREADY EXISTS
    if (empty($errors)) {
        $checkStmt = $pdo->prepare("SELECT email FROM user WHERE email = ?");
        $checkStmt->execute([$email]);
        
        if ($checkStmt->fetch()) {
            $errors[] = "This email is already registered. Please login instead.";
        }
    }
    
    // Insert if no errors
    if(empty($errors)){
        $sql = "INSERT INTO user (`name`, `email`) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([$name, $email]);
            
            // Store user info in session
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_email'] = $email;
            
            header('Location: fillform.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h2>Sign Up</h2>
    
    <?php if ($errors): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <form method="POST" action="">
        <label for="name">Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"><br>
        <label for="email">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"><br>
        <button type="submit">Sign Up</button>
    </form>
    
    <p>Already have an account? <a href="user.php">Login here</a></p>
</body>
</html>