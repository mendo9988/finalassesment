<?php 
require_once "../config/db.php";
session_start();

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Edit Ticket</title>
</head>
<body>

<div class="clipboard">
    <div class="paper">
        <h2>Edit Ticket</h2>
        <div class="badge">Update Ticket Information</div>

        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($ticket['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="issue_type">Issue Type</label>
                <select id="issue_type" name="issue_type" required>
                    <option value="Technical" <?= ($ticket['issue_type'] === 'Technical') ? 'selected' : '' ?>>Technical</option>
                    <option value="Billing" <?= ($ticket['issue_type'] === 'Billing') ? 'selected' : '' ?>>Billing</option>
                    <option value="Account" <?= ($ticket['issue_type'] === 'Account') ? 'selected' : '' ?>>Account</option>
                    <option value="Other" <?= ($ticket['issue_type'] === 'Other') ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority" required>
                    <option value="Low" <?= ($ticket['priority'] === 'Low') ? 'selected' : '' ?>>Low</option>
                    <option value="Medium" <?= ($ticket['priority'] === 'Medium') ? 'selected' : '' ?>>Medium</option>
                    <option value="High" <?= ($ticket['priority'] === 'High') ? 'selected' : '' ?>>High</option>
                </select>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($ticket['subject']) ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($ticket['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Open" <?= ($ticket['status'] === 'Open') ? 'selected' : '' ?>>Open</option>
                    <option value="In Progress" <?= ($ticket['status'] === 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                    <option value="Resolved" <?= ($ticket['status'] === 'Resolved') ? 'selected' : '' ?>>Resolved</option>
                    <option value="Closed" <?= ($ticket['status'] === 'Closed') ? 'selected' : '' ?>>Closed</option>
                </select>
            </div>
            
            <button type="submit">Update Ticket</button>
        </form>

        <div class="back-link">
            <a href="viewtable.php">← Back to Tickets</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>


<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $sql = "UPDATE tickets SET email=?, issue_type=?, priority=?, subject=?, description=?, status=?, modified_at = NOW() WHERE ticket_id=?";
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