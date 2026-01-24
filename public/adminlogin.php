<?php
session_start();

$correctPassword = "1234";
if (isset($_POST['login'])) {
    if ($_POST['password'] === $correctPassword) {
        header("Location: viewtable.php");
        exit();
    } else {
        echo "Invalid password";
    }
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
        <form method ="POST">
            <label for="password">password</label>
            <input type="password" name="password"><br>
            <button type="submit" name="login">Log In</button>
        </form>
    </main>
</body>
</html>