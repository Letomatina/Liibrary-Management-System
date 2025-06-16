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

// Get user id and name
$sql_user = "SELECT id, name FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "' LIMIT 1";
$result_user = mysqli_query($conn, $sql_user);
$user = mysqli_fetch_assoc($result_user);
$user_id = $user['id'];
$user_name = $user['name'];

// Fetch only this user's transactions
$sql = "SELECT t.id, b.title as book_title, t.issue_date, t.return_date, t.status FROM transactions t JOIN books b ON t.book_id = b.id WHERE t.user_id = '$user_id' ORDER BY t.issue_date DESC";
$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="Udash.css">
</head>
<body>
    <div class="user_dashboard_layout">
        <div class="user_nav">
            <button class="nav-btn" onclick="document.getElementById('requests-table').scrollIntoView({behavior: 'smooth'});">View My Requests</button>
            <a href="index.php?email=<?php echo urlencode($email); ?>" class="logout-btn">Log Out</a>
        </div>
        <div class="user_main_content">
            <h2 class="head">User Dashboard</h2>
            <div id="requests-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Book Title</th>
                            <th>Issue Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                            <td><?php echo $row['issue_date']; ?></td>
                            <td><?php echo $row['return_date']; ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
