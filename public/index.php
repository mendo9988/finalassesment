<?php
session_start();

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
    <link rel="stylesheet" href="../assets/style.css">
    <title>Welcome</title>
</head>
<body>
    <div class="container">
        <div class="vintage-sign">
            <div class="sign-content">
                <div class="decorative-top">⚜</div>
                <h2 class="sign-title">CUSTOMER SUPPORT</h2>
                <h1 class="sign-main">TICKET</h1>
                <div class="decorative-line">
                    <span class="left-ornament">❦</span>
                    <span class="center-ornament">✦</span>
                    <span class="right-ornament">❦</span>
                </div>
            </div>
        </div><br>
            <div class="clipboard">
                <div class="paper">
                    <div class="badge">Welcome Portal</div>
                    
                    <form method="POST" class="button-container">
                        <button type="submit" name="role" value="user">
                             User Login
                        </button>
                        <button type="submit" name="role" value="admin">
                             Admin Login
                        </button>
                    </form>
                </div>
            </div>
    </div>
</body>
</html>
