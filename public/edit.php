<?php 
require_once "../config/db.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die("Invalid ticket ID");
}

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
    <label>Email</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($ticket['email']) ?>" required>
    <br>
    <label>Issue Type</label>
    <!-- <input type="text" name="issue_type" value="<?= htmlspecialchars($ticket['issue_type']) ?>" required> -->
    <select name="issue_type" required>
        <option value="Technical" <?= ($ticket['issue_type'] === 'Technical') ? 'selected' : '' ?>>Technical</option>
        <option value="Billing"   <?= ($ticket['issue_type'] === 'Billing')   ? 'selected' : '' ?>>Billing</option>
        <option value="Account"   <?= ($ticket['issue_type'] === 'Account')   ? 'selected' : '' ?>>Account</option>
        <option value="Other"     <?= ($ticket['issue_type'] === 'Other')     ? 'selected' : '' ?>>Other</option>
    </select><br>
    
    <label>Priority</label>
    <!-- <input type="text" name="priority" value="<?= htmlspecialchars($ticket['priority']) ?>" required> -->
    <select name="priority" required>
        <option value="Low" <?= ($ticket['priority'] === 'Low') ? 'selected' : '' ?>>Low</option>
        <option value="Medium"   <?= ($ticket['priority'] === 'Medium')   ? 'selected' : '' ?>>Medium</option>
        <option value="High"   <?= ($ticket['priority'] === 'High')   ? 'selected' : '' ?>>High</option>
    </select><br>
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
        $sql = "UPDATE tickets SET email=?, issue_type=?, priority=?, subject=?, description=?, status=? WHERE ticket_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['email'],
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