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
    <title></title>
</head>
<body>
<div class="message"><?php echo htmlspecialchars($message ?? ''); ?></div>

<form method="POST">
    
    <label>Issue type</label>
    <select name="issue_type">
        <option>Technical</option>
        <option>Billing</option>
        <option>Account</option>
        <option>Other</option>
    </select><br>
    
    <label>Priority</label>
    <select name="priority">
        <option>Low</option>
        <option>Medium</option>
        <option>High</option>
    </select><br>
    
    <label>Subject</label>
    <input type="text" name="subject" required placeholder="Subject"><br>

    <label>Description</label>
    <textarea name="description" required></textarea><br>

<!-- csrf  -->
    <input type="hidden" name="csrf_token"
       value="<?= $_SESSION['csrf_token'] ?>">

    <button type="submit">Submit Ticket</button>
</form>

</body>
</html>

