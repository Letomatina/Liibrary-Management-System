<?php
session_start();
include "db.php";

$sql = "SELECT * FROM books";
$result = mysqli_query($conn, $sql);


$is_logged_in = isset($_SESSION['email']);

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="top-right-btns">
        <?php if (!$is_logged_in) { ?>
            <a href="login.php" class="top-btn">Log In</a>
        <?php } else { ?>
            <a href="logout.php?email=<?php echo urlencode($_SESSION['email']); ?>" class="top-btn">Log Out</a>
        <?php } ?>
        <a href="<?php echo $is_logged_in ? 'dashboard.php' : 'login.php'; ?>" class="top-btn">Go to Dashboard</a>
    </div>
    <header>
        <h1>Library Home Page</h1>
    </header>

<section>
  <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <div>
        <img src="Image/<?php echo $row['image']; ?>" alt="Book Image" height="100">     
        <p>Book Title: <?php echo $row['title']; ?></p>
        <p>Author Name: <?php echo $row['author']; ?></p>
        <p>ISBN: <?php echo $row['isbn']; ?></p>
        <p>Quantity: <?php echo $row['quantity']; ?></p>

        <?php if ($is_logged_in) { ?>
            <a href="borrow.php?book_id=<?php echo $row['id']; ?>">Borrow</a>
            <a href="return.php?book_id=<?php echo $row['id']; ?>" style="margin-left:10px;">Return</a>
        <?php } else { ?>
            <a href="login.php" onclick="alert('Please log in to borrow books.');">Borrow</a>
        <?php } ?>
    </div> 
   <?php } ?>
</section>
<footer>copyright@webproject</footer>
</body>
</html>


