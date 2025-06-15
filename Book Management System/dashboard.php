<?php

session_start();
include "db.php";

// Session validation: only allow logged-in users
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT * FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = null;
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    }
} else {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <p>Welcome to Dashboard<br>
    You are logged in as: <?php echo htmlspecialchars($user['role']); ?></p>
    <a href="logout.php?email=<?php echo $email; ?>">Log Out</a>
</body>
</html>
