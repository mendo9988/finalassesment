<?php 
require_once "../config/db.php";
$id = $_GET['id'];
if (!$id) die("Invalid ID");
try {
$sql = "Select * from tickets where ticket_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    echo "Error" . $e->getMessage();
}
if(!$id) {
    echo "id not found";
}
?>

<h2>Edit Ticket</h2>

<form method="POST">

    <label>Issue Type</label>
    <input type="text" name="issue_type" value="<?= htmlspecialchars($ticket['issue_type']) ?>" required>
    
    <label>Priority</label>
    <input type="text" name="priority" value="<?= htmlspecialchars($ticket['priority']) ?>" required>

    <label>Subject</label>
    <input type="text" name="subject" value="<?= htmlspecialchars($ticket['subject']) ?>" required>

    <label>Description</label>
    <textarea name="description"><?= htmlspecialchars($ticket['description']) ?></textarea>

    <label>Status</label>
    <input type="text" name="status" value="<?= htmlspecialchars($ticket['status']) ?>" required>

    <button type="submit">Update</button>
</form>
<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $sql = "UPDATE tickets SET issue_type=?, priority=?, subject=?, description=?, status=? WHERE ticket_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['issue_type'],
            $_POST['priority'],
            $_POST['subject'],
            $_POST['description'],
            $_POST['status'],
            $id
        ]);

        header("Location: viewtable.php");
        exit;
    }
?>