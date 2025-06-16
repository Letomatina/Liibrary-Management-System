<?php
session_start();
include "../db.php";


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

$users_sql = "SELECT * FROM users";
$users_result = mysqli_query($conn, $users_sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="manage_user.css">
</head>
<body>
    <div class="manage-users-container">
        <h2 class="manage-users-title">Manage Users</h2>
        <table class="manage-users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = mysqli_fetch_assoc($users_result)) { ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <a href="update.php?id=<?php echo $user['id']; ?>" class="manage-users-btn">Update</a>
                        <a href="delete.php?id=<?php echo $user['id']; ?>" class="manage-users-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
