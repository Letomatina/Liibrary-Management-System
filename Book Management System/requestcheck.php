<?php
session_start();
include "db.php";

// Session validation: only allow logged-in users
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
$email = $_SESSION['email'];

// Get user id
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
    <title>My Book Requests</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <header>
        <h1>My Book Requests</h1>
    </header>
    <section>
        <table style="width:100%;background:#fff;border-radius:8px;box-shadow:0 4px 8px rgba(0,0,0,0.08);">
            <thead style="background:#4B0082;color:#fff;">
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
    </section>
</body>
</html>
