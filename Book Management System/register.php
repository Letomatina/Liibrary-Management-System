<?php

    include "db.php";
    session_start();

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

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $role = $_POST["role"];

        // Check if email already exists
        $check = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $check);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Email already exists.'); window.location.href='register.php';</script>";
            exit();
        } else {
            $sql = "INSERT INTO users(name, email, password, role) VALUES('$name', '$email', '$password', '$role')";
            $result = mysqli_query($conn, $sql);
            if(!$result){
                echo "<script>alert('Error! " . addslashes(mysqli_error($conn)) . "'); window.location.href='register.php';</script>";
            } else {
                echo "<script>alert('Registered Successfully.'); window.location.href='login.php';</script>";
            }
            exit();
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
   <form action="register.php" method="POST">
   <h2 class="pop">REGISTER</h2>
   
        <div class="input-group">
           <i class="fas fa-user"></i>
           <input type="text" name="name" placeholder="Name" required>
        </div>

        <div class="input-group">
           <i class="fas fa-envelope"></i>
           <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
           <i class="fas fa-lock"></i>
           <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="input-group">
           <i class="fas fa-user-tag"></i>
           <input type="text" name="role" value="user" readonly>
        </div>

        <button type="submit">Sign Up</button>
    </form>
</div>

</body>
</html>