<?php
session_start();
include "../db.php";

// Session validation: only allow logged-in admin users
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

// Check if the logged-in user is admin
$email = $_SESSION['email'];

$sql_admin = "SELECT role FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "' LIMIT 1";
$result_admin = mysqli_query($conn, $sql_admin);
$role = '';


if ($result_admin && mysqli_num_rows($result_admin) > 0) {
    $row_admin = mysqli_fetch_assoc($result_admin);
    $role = $row_admin['role'];
}
if ($role !== 'admin') {
    echo "<script>alert('You are not admin!'); window.location.href='../dashboard.php';</script>";
    exit();
}


$sql = "SELECT * FROM books";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error! " . mysqli_error($conn); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <link rel="stylesheet" href="/Book Management System/Admin/Adm_style.css">
</head>
<body>
    <div class="add-books-nav">
        <a href="Adashboard.php">Back to Admin Dashboard</a>
        <a href="../logout.php">Log Out</a>
    </div>
    <table class="">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Quantity</th>
                <th>Image</th> 
            </tr>
        </thead>

        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['author']; ?></td>
                <td><?php echo $row['isbn']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>
                    <img src="../image/<?php echo $row['image']; ?>" alt="Book Image" height="100">
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</body>
</html>
