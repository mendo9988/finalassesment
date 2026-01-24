<?php

require '../config/db.php';

$message = '';

try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// user input is used directly in sql
    $name=filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email=filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        if (empty($name)) {
        $message[] = "Name is required.";
        } 
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email is invalid.<br>";
        }
        if(empty($message)){
            $sql = "INSERT INTO user 
            (`email`, `issue_type`, `priority`, `subject`, `description`)
            VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            try {
                $stmt->execute([$email, $issue_type, $priority, $subject, $description]);
                $message = "Addition successful";
            } catch (PDOException $e) {
                $message = $e->getMessage();
            }
        } else {
            $message = implode('<br>', $errors);
        }
// Passwords are stored without hashing and compared as plain text
        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $stmt->close();
        $message = "User signed up successfully";
        header('refresh: 2; url=login.php');
    }

} catch (Exception $e) {
    $message = "Something went wrong.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <main>
        <form action="POST">
            <label for="email"> Email</label>
            <input type="email" name="email"> <br>
            <button >Go</button>
        </form>
    </main>
    <button>Sign up</button>
</body>
</html>