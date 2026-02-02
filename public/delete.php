<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../config/db.php";

if (
    !isset($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    die("Invalid CSRF token");
}

$id = $_GET['id'] ?? null;
if ($id === null) {
    die("Invalid ID");
}
$sql = "DELETE FROM tickets WHERE ticket_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

header("Location: viewtable.php");
exit;
?>