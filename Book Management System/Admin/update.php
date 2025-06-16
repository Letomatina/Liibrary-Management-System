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
    echo "No transaction selected.";
    exit();
}
$transaction_id = $_GET['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $return_date = $_POST['return_date'];
    $status = isset($_POST['status']) ? $_POST['status'] : $trans['status'];
    $update_sql = "UPDATE transactions SET return_date = '" . mysqli_real_escape_string($conn, $return_date) . "', status = '" . mysqli_real_escape_string($conn, $status) . "' WHERE id = '$transaction_id'";
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Return date and status updated successfully.'); window.location.href='Adashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating: ".addslashes(mysqli_error($conn))."'); window.location.href='Adashboard.php';</script>";
        exit();
    }
}

// Fetch current transaction info
$trans_sql = "SELECT * FROM transactions WHERE id = '$transaction_id'";
$trans_result = mysqli_query($conn, $trans_sql);
$trans = mysqli_fetch_assoc($trans_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Return Date</title>
    <link rel="stylesheet" href="update.css">
</head>
<body>
    <div class="update-container">
        <form method="post" class="update-form">
            <h2>Update Return Date & Status</h2>

            <div class="input-group">
                <input type="date" id="return_date" name="return_date" value="<?php echo htmlspecialchars($trans['return_date']); ?>" required>
            </div>
            <div class="input-group">
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="borrowed" <?php if($trans['status']==='borrowed') echo 'selected'; ?>>Borrowed</option>
                    <option value="returned" <?php if($trans['status']==='returned') echo 'selected'; ?>>Returned</option>
                </select>
            </div>

            <button type="submit">Update</button>
            <a href="Adashboard.php" class="cancel-link" style="margin-top:18px;">Cancel</a>
        </form>
    </div>
</body>
</html>
