<?php
    
session_start();
include "../db.php";

// Session validation: only allow logged-in admin users
if (isset($_SESSION['email'])) {
    // Get user role from database
    $email = $_SESSION['email'];
    $sql = "SELECT role FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $role = '';
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $role = $row['role'];
    }
    // Check if user is admin
    if ($role != 'admin') {
        // Redirect non-admin to user dashboard
        header("Location: ../dashboard.php");
        exit();
    }
} else {
    // If not logged in, send to login
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $isbn = $_POST["isbn"];
    $quantity = $_POST["quantity"];
    $image = $_FILES['image']['name'];
    $image_location = $_FILES['image']['tmp_name'];
    $upload_location = "../Image/";

    $sql = "INSERT INTO books(title, author, isbn, image, quantity) 
            VALUES('$title', '$author', '$isbn', '$image', '$quantity')";
    $result = mysqli_query($conn, $sql);

    /* 
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
          
         if (in_array($_FILES['image']['type'], $allowed_types)) {
                move_uploaded_file($image_location, $upload_location . $image);
          } else {
                echo "Only JPG and PNG files are allowed.";
          }   
          
          */

    if (!$result) {
        echo "Error! " . mysqli_error($conn);
    } else {
        move_uploaded_file($image_location, $upload_location . $image);
        echo "Book added successfully.";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <link rel="stylesheet" href="/Book Management System/Admin/Adm_style.css">
</head>
<body>
    <div class="admin_add_books">

        <form action="add_books.php" method="post" enctype="multipart/form-data">
         <h2 class="head">ADD BOOKS</h2>
            <input type="text" name="title" placeholder="Title"><br>
            <input type="text" name="author" placeholder="Author"><br>
            <input type="text" name="isbn" placeholder="ISBN"><br> 
            <input type="text" name="quantity" placeholder="Quantity"><br>
            <input class="file" type="file" name="image"><br>
           
            <button type="submit">Add Book</button>

        </form>
    </div> 
</body>
</html>
