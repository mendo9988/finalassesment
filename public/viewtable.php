<?php
require_once '../config/db.php';

try {
    $sql = "SELECT * FROM tickets WHERE 1";
    $params = [];
    
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
    // Search by subject or description
    if (!empty($_GET['search'])) {
        $sql .= " AND (subject LIKE ? OR description LIKE ?)";
        $searchTerm = "%" . $_GET['search'] . "%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    
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
    <title>Table</title>
</head>
<body>
    
    <div class="navbar">
        <div class="dropdown">
            <button class="dropbtn">Issue Type<i class="fa fa-caret-down">
            </i> </button>
            <div class="dropdown-content">
                <a href="viewtable.php?issue_type=Technical">Technical</a>
                <a href="viewtable.php?issue_type=Billing">Billing</a>
                <a href="viewtable.php?issue_type=Account">Account</a>
                <a href="viewtable.php?issue_type=Other">Other</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Priority<i class="fa fa-caret-down">
            </i> </button>
            <div class="dropdown-content">
                <a href="viewtable.php?priority=Low">Low</a>
                <a href="viewtable.php?priority=Medium">Medium</a>
                <a href="viewtable.php?priority=High">High</a>
            </div>
        </div>
        
        <div class="dropdown">
            <button class="dropbtn">Date<i class="fa fa-caret-down">
            </i> </button>
            <div class="dropdown-content">
                <a href="viewtable.php?date=today">Today</a>
                <a href="viewtable.php?date=week">This week</a>
                <a href="viewtable.php?date=month">This month</a>
            </div>
        </div>
        
        <div>
        <form method="GET" action="viewtable.php">
            <input type="text" name="search" placeholder="Search by title or keyword"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <input type="hidden" name="issue_type" value="<?= $_GET['issue_type'] ?? '' ?>">
            <input type="hidden" name="priority" value="<?= $_GET['priority'] ?? '' ?>">
            <input type="hidden" name="date" value="<?= $_GET['date'] ?? '' ?>">

            <button type="submit">Search</button>
        </form><br>

            
        </div>
    </div><br>
    <main>
        <table border="1">
                    <tr>
                        <th>Ticket Id</th>
                        <th>User Id</th>
                        <th>Email</th>
                        <th>Issue Type</th>
                        <th>Priority</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Modified At</th>
                    </tr>
                    <?php foreach ($data as $rows): ?>
                    <tr>
                        <td><?= htmlspecialchars($rows['ticket_id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($rows['user_id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($rows['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($rows['issue_type'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($rows['priority'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($rows['subject'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($rows['description'], ENT_QUOTES, 'UTF-8') ?></td>
                        <!-- <td><?= htmlspecialchars($rows['status'], ENT_QUOTES, 'UTF-8') ?></td> -->
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
                        <td><a href="edit.php?id=<?= $rows['ticket_id'] ?>">Edit</a> |
                        <a href="delete.php?id=<?= $rows['ticket_id'] ?>" onclick="return confirm('Are you sure?')" >Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
    </main>
<script>

function updateStatus(ticketId, status) {
    fetch('update_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ticket_id: ticketId, status: status })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert(data.message || 'Status update failed');
        }
    })
    .then(response => response.text())
    .then(text => {
        console.log(text);
    })

    // .catch(e => console.error(e.message));
}
</script>
    </body>
    </html>
    <!-- <script>
        function updateStatus(id, status) {
            fetch("update_status.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id=" + id + "&status=" + status
            })
            .then(res => res.text())
            .then(data => {
                console.log(data); // should log "success"
            });
        }
    </script> -->

<!-- <a href="delete.php?id=<?= $rows['TicketId'] ?>"
onclick="return confirm('Are you sure?')">Delete</a> -->