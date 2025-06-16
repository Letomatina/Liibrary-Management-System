<?php
include "../db.php";
// Fetch all transactions with user and book info
$sql = "SELECT t.id, u.name as user_name, b.title as book_title, t.issue_date, t.return_date, t.status FROM transactions t JOIN users u ON t.user_id = u.id JOIN books b ON t.book_id = b.id ORDER BY t.issue_date DESC";
$result = mysqli_query($conn, $sql);
?>
<div class="transaction-table-container">
    <div class="transaction-table">
        <h2>All Transactions</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Book Title</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                    <td><?php echo $row['issue_date']; ?></td>
                    <td><?php echo $row['return_date']; ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <a href="./update.php?id=<?php echo $row['id']; ?>" class="action-btn">Update</a>
                        <a href="./delete.php?id=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this transaction?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
