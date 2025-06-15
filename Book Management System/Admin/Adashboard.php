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
</head>
<body>
     <a href="../logout.php?email=<?php echo urlencode($email); ?>">Log Out</a>
</body>
</html>