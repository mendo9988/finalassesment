<?php
require_once '../config/db.php';

try {
 $sql = "Select * from tickets";
 $stmt = $pdo->prepare($sql);
 $stmt->execute();
 $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    echo  "Error: " . $e->getMessage();
}
?>
<div>
    <form action="">

    <h3>Search</h3>
    <input type="text" placeholder="search..">
    <button type="submit" name="search">Search</button>
    </form>
    
</div>
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
                    <!-- Status dropdown for AJAX -->
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
                <td><a href="edit.php?id=<?= $rows['ticket_id'] ?>">Edit</a> |
                <a href="delete.php?id=<?= $rows['ticket_id'] ?>" onclick="return confirm('Are you sure?')" >Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
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