<?php
require_once '../config/db.php';

if (!isset($_GET['q']) || strlen($_GET['q']) < 2) {
    echo json_encode([]);
    exit;
}

$q = '%' . $_GET['q'] . '%';

$sql = "SELECT subject, email, description
        FROM tickets 
        WHERE subject LIKE ? OR email LIKE ?
        LIMIT 5";

$stmt = $pdo->prepare($sql);
$stmt->execute([$q, $q]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
