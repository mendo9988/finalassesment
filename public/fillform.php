<?php 
session_start();
// after verifying email
if (!isset($_SESSION['user_email'])) {
    header('Location: user.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$email = $_SESSION['user_email'];

require_once '../config/db.php';
$message = "";
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $issue_type=filter_input(INPUT_POST, 'issue_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $priority=filter_input(INPUT_POST, 'priority', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $subject=filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description=filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $errors = [];

    if (empty($issue_type)) {
        $errors[] = "Issue type is required.";
    } 
    if (empty($priority)) {
        $errors[] = "Priority is required.";

    } 
    if (empty($subject)) {
        $errors[] = "Subject is required.";
    }
    if (empty($description)) {
        $errors[] = "Description is required.";
    } 
    echo "<hr>";
    if(empty($errors)){
        $sql = "INSERT INTO tickets
        (user_id, email, issue_type, priority, subject, description)
        VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([$user_id, $email, $issue_type, $priority, $subject, $description]);
            $message = "Addition successful";
        } catch (PDOException $e) {
            $message = $e->getMessage();
        }
    } else {
        $message = implode('<br>', $errors);
    }

        
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/style.css">
    <title></title>
</head>
<body>
<div class="clipboard">
        <div class="paper">
            <div class="badge">Support Ticket</div>
            <h2>Submit a Ticket</h2>
            <p class="subtitle">Fill out the form below to submit your request</p>
            
            <?php if (isset($message) && $message): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Issue Type</label>
                    <select name="issue_type" required>
                        <option value="">Select issue type</option>
                        <option>Technical</option>
                        <option>Billing</option>
                        <option>Account</option>
                        <option>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Priority</label>
                    <select name="priority" required>
                        <option value="">Select priority</option>
                        <option>Low</option>
                        <option>Medium</option>
                        <option>High</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="subject" required placeholder="Brief description of the issue">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" required placeholder="Please provide detailed information about your issue"></textarea>
                </div>

                <!-- CSRF token -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <button type="submit">Submit Ticket</button>

                <div class="back-link">
                    <a href="user.php">← Back</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

