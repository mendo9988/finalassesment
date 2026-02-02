<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="navbar">
        <div class="dropdown">
            <button class="dropbtn">Issue Type<i class="fa fa-caret-down">
            </i> </button>
            <div class="dropdown-content">
                <a href="?<?= http_build_query(array_merge($_GET, ['issue_type' => 'Technical'])) ?>">Technical</a>
                <a href="?<?= http_build_query(array_merge($_GET, ['issue_type' => 'Billing'])) ?>">Billing</a>
                <a href="?<?= http_build_query(array_merge($_GET, ['issue_type' => 'Account'])) ?>">Account</a>
                <a href="?<?= http_build_query(array_merge($_GET, ['issue_type' => 'Other'])) ?>">Other</a>
                <a href="?<?= http_build_query(array_diff_key($_GET, ['issue_type' => ''])) ?>">Clear Filter</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Priority<i class="fa fa-caret-down">
            </i> </button>
            <div class="dropdown-content">
                <a href="?<?= http_build_query(array_merge($_GET, ['priority' => 'Low'])) ?>">Low</a>
                <a href="?<?= http_build_query(array_merge($_GET, ['priority' => 'Medium'])) ?>">Medium</a>
                <a href="?<?= http_build_query(array_merge($_GET, ['priority' => 'High'])) ?>">High</a>
                <a href="?<?= http_build_query(array_diff_key($_GET, ['priority' => ''])) ?>">Clear Filter</a>
            </div>
        </div>
        
        <div class="dropdown">
            <button class="dropbtn">Date<i class="fa fa-caret-down">
            </i> </button>
            <div class="dropdown-content">
                <a href="?<?= http_build_query(array_merge($_GET, ['date' => 'today'])) ?>">Today</a>
                <a href="?<?= http_build_query(array_merge($_GET, ['date' => 'week'])) ?>">This week</a>
                <a href="?<?= http_build_query(array_merge($_GET, ['date' => 'month'])) ?>">This month</a>
                <a href="?<?= http_build_query(array_diff_key($_GET, ['date' => ''])) ?>">Clear Filter</a>
            </div>
        </div>
        
        <div>
            <form method="GET">
                <input id="searchBox" type="text" name="search" placeholder="Search by subject, description, or email"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <div id="suggestions"></div>
                <input type="hidden" name="issue_type" value="<?= $_GET['issue_type'] ?? '' ?>">
                <input type="hidden" name="priority" value="<?= $_GET['priority'] ?? '' ?>">
                <input type="hidden" name="date" value="<?= $_GET['date'] ?? '' ?>">
                <button type="submit">Search</button>
                <?php if (!empty($_GET['search'])): ?>
                    <a href="?<?= http_build_query(array_diff_key($_GET, ['search' => ''])) ?>" style="margin-left: 10px;">Clear Search</a>
                <?php endif; ?>
            </form>
        </div>
        
        <div style="margin-left: auto;">
            <?php if (!empty(array_filter($_GET))): ?>
                <a href="viewtable.php" style="padding: 10px 15px; background: #f44336; color: white; text-decoration: none; border-radius: 4px;">Clear All Filters</a>
            <?php endif; ?>
        </div>
    </div>