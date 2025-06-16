<?php
session_start();
include "db.php";

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT id, name, role FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user_id = '';
    $user_name = '';
    $role = '';

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['id'];
        $user_name = $row['name'];
        $role = $row['role'];
    }

    if ($role === 'admin') {
        header("Location: Admin/Adashboard.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}

if(isset($_GET['book_id'])){
    $book_id = $_GET['book_id'];

    // Check if the user has borrowed this book and not yet returned it
    $check_sql = "SELECT * FROM transactions WHERE user_id = '$user_id' AND book_id = '$book_id' AND status = 'borrowed'";
    $check_result = mysqli_query($conn, $check_sql);

    if ($check_result && mysqli_num_rows($check_result) > 0) {
        // Mark the transaction as returned
        $update_sql = "UPDATE transactions SET status = 'returned', return_date = CURDATE() WHERE user_id = '$user_id' AND book_id = '$book_id' AND status = 'borrowed'";
        $update_result = mysqli_query($conn, $update_sql);
        if ($update_result) {
            // Increase book quantity by 1
            $qty_sql = "UPDATE books SET quantity = quantity + 1 WHERE id = '$book_id'";
            mysqli_query($conn, $qty_sql);
            echo "<script>alert('Book returned successfully.'); window.location.href='index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error updating transaction: " . addslashes(mysqli_error($conn)) . "'); window.location.href='index.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('No borrowed record found for this book.'); window.location.href='index.php';</script>";
        exit();
    }
}
?>
