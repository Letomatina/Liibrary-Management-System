<?php
session_start();
include "../db.php";

header('Content-Type: application/json');

// Only allow logged-in admin users
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit();
}
$email = $_SESSION['email'];
$sql = "SELECT role FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "' LIMIT 1";
$result = mysqli_query($conn, $sql);
$role = '';
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $role = $row['role'];
}
if ($role != 'admin') {
    echo json_encode(['success' => false, 'message' => 'Not authorized.']);
    exit();
}

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'No transaction ID provided.']);
    exit();
}
$transaction_id = $_POST['id'];
$delete_sql = "DELETE FROM transactions WHERE id = '" . mysqli_real_escape_string($conn, $transaction_id) . "'";
if (mysqli_query($conn, $delete_sql)) {
    echo json_encode(['success' => true, 'message' => 'Transaction deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting transaction: ' . mysqli_error($conn)]);
}
