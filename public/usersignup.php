<?php
require_once '../config/db.php';

$message = '';
$errors = [];


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// user input is used directly in sql
        $name=filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email=filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        if (empty($name)) {
        $errors[] = "Name is required.";
        } 
        if (empty($email)){
            $errors[] = "Email is required";
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email is invalid.<br>";
        }
        if(empty($errors)){
            $sql = "INSERT INTO user 
            (`name`, `email`)
            VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            try {
                $stmt->execute([
                    $name, $email]);
                $message = "Sign up successful";
            } catch (PDOException $e) {
                $message = $e->getMessage();
            }
        } else {
            $message = implode('<br>', $errors);
        }
        header('Location: fillform.php');
        exit;
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
    <?php if ($message): ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <?php if ($errors): ?>
            <ul style="color: red;">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="name">Name</label>
        <input type="text" name="name"><br>
        <label for="email">Email</label>
        <input type="email" name="email"><br>
        <button type="submit">Next</button>
    </form>
</body>
</html>