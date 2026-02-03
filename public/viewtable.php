<?php
session_start();
if (!isset($_SESSION['authenticated'])) {
    header("Location: adminlogin.php");
    exit();
}

require_once '../config/db.php';

try {
    $sql = "SELECT * FROM tickets WHERE 1";
    $params = [];
    
    // Filter by search keyword
    if (!empty($_GET['search'])) {
        $searchTerm = '%' . $_GET['search'] . '%';
        $sql .= " AND (subject LIKE ? OR description LIKE ? OR email LIKE ?)";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    // Filter by issue type
    if (!empty($_GET['issue_type'])) {
        $sql .= " AND issue_type = ?";
        $params[] = $_GET['issue_type'];
    }
    
    // Filter by priority
    if (!empty($_GET['priority'])) {
        $sql .= " AND priority = ?";
        $params[] = $_GET['priority'];
    }
    
    // Filter by date
    if (!empty($_GET['date'])) {
        if ($_GET['date'] === 'today') {
            $sql .= " AND DATE(created_at) = CURDATE()";
        } elseif ($_GET['date'] === 'week') {
            $sql .= " AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        } elseif ($_GET['date'] === 'month') {
            $sql .= " AND created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        }
    }
    
    // Order by most recent first
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
}
catch (PDOException $e) {
    echo  "Error: " . $e->getMessage();
}
?>
   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Ticket Management</title>
</head>
<body>

    <div class="clipboard-tb">
    <a href="viewtable.php" class="title-link">
        <h2 class="page-title">Ticket Management</h2>
    </a>
        <div class="paper">
            <?php include '../includes/header.php'; ?>
            <main>
                
                <table>
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>User ID</th>
                            <th>Email</th>
                            <th>Issue Type</th>
                            <th>Priority</th>
                            <th>Subject</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Modified At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data)): ?>
                            <tr>
                                <td colspan="11" class="empty-state">
                                    No tickets found. <?php if (!empty($_GET['search'])): ?>Try a different search term.<?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data as $rows): ?>
                            <tr>
                                <td><?= htmlspecialchars($rows['ticket_id'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($rows['user_id'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($rows['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($rows['issue_type'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($rows['priority'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($rows['subject'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($rows['description'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <select onchange="updateStatus(<?= (int)$rows['ticket_id'] ?>, this.value)">
                                    <?php
                                    $statuses = ['Open', 'In Progress', 'Resolved', 'Closed'];
                                    foreach ($statuses as $status):
                                    ?>
                                        <option value="<?= htmlspecialchars($status) ?>"
                                            <?= $rows['status'] === $status ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($status) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><?= $rows['created_at']? date('d-m-Y', strtotime($rows['created_at'])) : 'N/A' ?></td>
                                <td><?= $rows['modified_at']? date('d-m-Y', strtotime($rows['modified_at'])) : 'N/A' ?> </td>
                                <td>
                                    <a href="edit.php?id=<?= $rows['ticket_id'] ?>">Edit</a> |
                                    <a href="delete.php?id=<?= $rows['ticket_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>

</body>
</html>