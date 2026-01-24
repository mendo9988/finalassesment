
<?php 

$server = 'localhost';
$username = 'root';
$password = '';
$database = 'final';

try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    $pdo = new PDO(
        "mysql:host=$server;dbname=$database;charset=utf8mb4",
        $username,
        $password,
        $options
    );

    // echo "<h3 style='color:green;'>Welcome to Student Database!</h3>";

} catch (PDOException $e) {
    die("Connection Failed: " . $e->getMessage());
}
?>

<a href="../public/fillform.php">fillform</a><br>
<a href="../public/index.html">index html</a> <br>
<a href="../public/login.php">login</a> <br>