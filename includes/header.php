<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
<div class="navbar">
    <div class="dropdown">
        <button class="dropbtn">Issue Type</button>
        <div class="dropdown-content">
            <a href="?<?= http_build_query(array_merge($_GET, ['issue_type' => 'Technical'])) ?>">Technical</a>
            <a href="?<?= http_build_query(array_merge($_GET, ['issue_type' => 'Billing'])) ?>">Billing</a>
            <a href="?<?= http_build_query(array_merge($_GET, ['issue_type' => 'Account'])) ?>">Account</a>
            <a href="?<?= http_build_query(array_merge($_GET, ['issue_type' => 'Other'])) ?>">Other</a>
            <a href="?<?= http_build_query(array_diff_key($_GET, ['issue_type' => ''])) ?>">Clear Filter</a>
        </div>
    </div>
    
    <div class="dropdown">
        <button class="dropbtn">Priority</button>
        <div class="dropdown-content">
            <a href="?<?= http_build_query(array_merge($_GET, ['priority' => 'Low'])) ?>">Low</a>
            <a href="?<?= http_build_query(array_merge($_GET, ['priority' => 'Medium'])) ?>">Medium</a>
            <a href="?<?= http_build_query(array_merge($_GET, ['priority' => 'High'])) ?>">High</a>
            <a href="?<?= http_build_query(array_diff_key($_GET, ['priority' => ''])) ?>">Clear Filter</a>
        </div>
    </div>
    
    <div class="dropdown">
        <button class="dropbtn">Date</button>
        <div class="dropdown-content">
            <a href="?<?= http_build_query(array_merge($_GET, ['date' => 'today'])) ?>">Today</a>
            <a href="?<?= http_build_query(array_merge($_GET, ['date' => 'week'])) ?>">This Week</a>
            <a href="?<?= http_build_query(array_merge($_GET, ['date' => 'month'])) ?>">This Month</a>
            <a href="?<?= http_build_query(array_diff_key($_GET, ['date' => ''])) ?>">Clear Filter</a>
        </div>
    </div>
    
    <div class="searchBox">
        <form method="GET">
            <input id="searchBox" type="text" name="search" placeholder="Search by subject, description, or email"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            
            <input type="hidden" name="issue_type" value="<?= $_GET['issue_type'] ?? '' ?>">
            <input type="hidden" name="priority" value="<?= $_GET['priority'] ?? '' ?>">
            <input type="hidden" name="date" value="<?= $_GET['date'] ?? '' ?>">
            <button type="submit">Search</button>
            <?php if (!empty($_GET['search'])): ?>
                <a href="?<?= http_build_query(array_diff_key($_GET, ['search' => ''])) ?>" 
                 class="clear-search-btn">Clear Search</a>
            <?php endif; ?>
        </form>
        <div id="suggestions"></div>
    </div>
    
    <div style="margin-left: auto;">
        <?php if (!empty(array_filter($_GET))): ?>
            <a href="viewtable.php" class="clear-all-btn">Clear All Filters</a>
        <?php endif; ?>
    </div>
</div>
</body>

</html>