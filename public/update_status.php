
<?php
require_once '../config/db.php';
header('Content-Type: application/json');
echo json_encode([
    'success' => true
]);

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (
    !isset($data['ticket_id'], $data['status']) ||
    !is_numeric($data['ticket_id'])
) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid input"
    ]);
    exit;
}

$ticketId = (int)$data['ticket_id'];
$status   = $data['status'];

try {
    $sql = "UPDATE tickets 
         SET status = ?, modified_at = NOW() 
         WHERE ticket_id = ?";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([$status, $ticketId]);

    echo json_encode([
        "success" => true
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
