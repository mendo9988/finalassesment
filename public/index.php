<?php
session_start();
// Generate token (server-side)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
    if ($_POST['role']==='user') {
        header('Location: user.php');
        exit;
    }
    if ($_POST['role']==='admin') {
        header('Location: adminlogin.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
</head>
<body>
    <form method="POST">
        <button type="submit" name="role" value="user">User</button><br>
        <button type="submit"name="role" value="admin">Admin</button>
    </form>
</body>
</html>
