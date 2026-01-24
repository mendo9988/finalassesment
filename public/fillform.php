<?php 
session_start();

// after verifying email
$_SESSION['user_id'] = $user['user_id'];
$email = $_SESSION['email']; 
require_once '../config/db.php';
$message = "";
if ($_SERVER['REQUEST_METHOD']=='POST') {
    // $name=filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    // $email=filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $issue_type=filter_input(INPUT_POST, 'issue_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $priority=filter_input(INPUT_POST, 'priority', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $subject=filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description=filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $errors = [];

    // if (empty($name)) {
    //     $errors[] = "Name is required.";
    // } 
    // // Email: validate format
    // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //     $errors[] = "Email is invalid.<br>";
    // } 
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
    <!-- <label>Name</label>
    <input type="text" name="name" required placeholder="Your Name"><br> -->

    <!-- <label>Email</label>
    <input type="email" name="email" required placeholder="Email"><br> -->

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

    <button type="submit">Submit Ticket</button>
</form>

</body>
</html>

