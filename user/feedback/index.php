<?php
include('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$account_id = $_SESSION['ID'];

$sql = "SELECT f.feedback_id, f.account_id, a.username, f.rating, f.comment, f.created_at 
        FROM feedback f
        JOIN account a ON f.account_id = a.account_id
        ORDER BY f.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Feedbacks</title>
    <?php include('../bootstrap.php'); ?> 
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">User Feedbacks</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo str_repeat("⭐", $row['rating']); ?></td>
                    <td><?php echo htmlspecialchars($row['comment']); ?></td>
                    <td><?php echo date("F j, Y", strtotime($row['created_at'])); ?></td>
                    <td>
                        <?php if ($row['account_id'] == $account_id): ?>
                            <a href="edit.php?id=<?php echo $row['feedback_id']; ?>" class="btn btn-primary">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </a>
                        <?php else: ?>
                            <span class="text-muted"></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
