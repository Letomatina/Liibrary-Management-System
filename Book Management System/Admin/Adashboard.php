<?php

session_start();
include "../db.php";

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

// Get user role from database
$email = $_SESSION['email'];
$sql = "SELECT role FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "' LIMIT 1";
$result = mysqli_query($conn, $sql);
$role = '';


if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $role = $row['role'];
}

if ($role !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Admin_dash.css">
    <link rel="stylesheet" href="transaction.css">

    
<body>
    <div class="admin_dashboard_layout">
        <div class="admin_nav">
            <button class="nav-btn" onclick="document.getElementById('transactions-table').scrollIntoView({behavior: 'smooth'});">View Transactions</button>
            <a href="add_books.php" class="nav-btn">Add Books</a>
            <a href="view_books.php" class="nav-btn">View Books</a>
            <a href="manage_users.php" class="nav-btn">Manage Users</a>
            <a href="../logout.php?email=<?php echo urlencode($email); ?>" class="logout-btn">Log Out</a>
        </div>
        <div class="admin_main_content">
            <h2 class="head">Admin Dashboard</h2>
            <div id="transactions-table">
                <?php include 'view_transactions.php'; ?>
            </div>
        </div>
    </div>
</body>
</html>