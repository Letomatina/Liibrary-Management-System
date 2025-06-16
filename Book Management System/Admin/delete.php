<?php
session_start();
include "../db.php";

// Session validation: only allow logged-in admin users
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT role FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $role = '';
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $role = $row['role'];
    }
    if ($role != 'admin') {
        header("Location: ../dashboard.php");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}

// Get transaction id
if (!isset($_GET['id'])) {
    echo "<div class='delete-container'><div class='delete-message error'>No transaction selected.</div></div>";
    exit();
}
$transaction_id = $_GET['id'];

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delete_sql = "DELETE FROM transactions WHERE id = '$transaction_id'";
    if (mysqli_query($conn, $delete_sql)) {
        echo "<div class='delete-container'><div class='delete-message success'>Transaction deleted successfully.</div><a href='Adashboard.php' class='delete-link'>Go Back to Dashboard</a></div>";
        exit();
    } else {
        echo "<div class='delete-container'><div class='delete-message error'>Error deleting transaction: ".mysqli_error($conn)."</div></div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Transaction</title>
    <link rel="stylesheet" href="delete.css">
</head>
<body>
    <div class="delete-container">
        <form method="post" class="delete-form">
            <h2>Delete Transaction</h2>
            <p>Are you sure you want to delete this transaction?</p>
            <button type="submit" class="delete-btn">Yes, Delete</button>
            <a href="Adashboard.php" class="delete-link">Cancel</a>
        </form>
    </div>
</body>
</html>
