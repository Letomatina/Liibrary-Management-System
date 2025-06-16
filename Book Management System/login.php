<?php

session_start();
include "db.php";

// Session validation: if user is already logged in, redirect to dashboard or admin dashboard
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT role FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $role = '';

    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $role = $row['role'];
    }
    if ($role === 'admin') {
        header("Location: Admin/Adashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Error! " . mysqli_error($conn);
    } else {
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);


            if ($user['password'] == $password) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // directing based on role
                if ($user['role'] == 'admin') {
                    echo "<script>alert('Logged in successfully.'); window.location.href='Admin/Adashboard.php';</script>";
                } else {
                    echo "<script>alert('Logged in successfully.'); window.location.href='dashboard.php';</script>";
                }
                exit();
            } else {
                echo "<script>alert('Incorrect password.'); window.location.href='login.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('No user found with that email.'); window.location.href='login.php';</script>";
            exit();
        }
    }
}
?>
 
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet"  href="/Book Management System/styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    
</head>
<body>

<div class="register">
   <form action="login.php" method="POST">
   <h2 class="pop">LOG IN</h2>
   
    
        <div class="input-group">
           <i class="fas fa-envelope"></i>
           <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
           <i class="fas fa-lock"></i>
           <input type="password" name="password" placeholder="Password" required>
        </div>


        <button type="submit">Log In</button>
    </form>
</div>

</body>
</html>