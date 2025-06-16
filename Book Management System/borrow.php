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
   
    $qty_sql = "SELECT quantity FROM books WHERE id = '$book_id'";
    $qty_result = mysqli_query($conn, $qty_sql);
    $book_qty = 0;

    if ($qty_result && mysqli_num_rows($qty_result) > 0) {
        $qty_row = mysqli_fetch_assoc($qty_result);
        $book_qty = (int)$qty_row['quantity'];
    }
    if ($book_qty <= 0) {
        echo "Sorry, this book is out of stock.";
        echo '<br><a href="index.php">Go Back</a>';
        exit();
    }


    $check_sql = "SELECT * FROM transactions WHERE user_id = '$user_id' AND book_id = '$book_id' AND status = 'borrowed'";
    $check_result = mysqli_query($conn, $check_sql);

    if ($check_result && mysqli_num_rows($check_result) > 0) {
        echo "You have already borrowed this book and not returned it yet.";
    } else {
        // Insert borrow transaction with user_name
        $sql = "INSERT INTO transactions (user_id, user_name, book_id, issue_date, status) VALUES ('$user_id', '$user_name', '$book_id', CURDATE(), 'borrowed')";
        $result = mysqli_query($conn, $sql);
        
        if (!$result) {
            echo "Error! " . mysqli_error($conn);
        } else {
            // Decrease book quantity by 1
            $update_sql = "UPDATE books SET quantity = quantity - 1 WHERE id = '$book_id' AND quantity > 0";
            mysqli_query($conn, $update_sql);
            echo "<script>alert('Request Sent.'); window.location.href='index.php';</script>";
            exit();
        }
    }
     echo '<br><a href="index.php">Go Back</a>';
}

?>