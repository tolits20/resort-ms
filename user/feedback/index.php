<?php
include('../resources/database/config.php');
include('../user/bootstrap.php');


if (!isset($_SESSION['ID'])) {
    header("location: ../login.php");
    exit;
}

$account_id = $_SESSION['ID'];

$sql = "SELECT f.*, a.username FROM feedback f JOIN account a ON f.account_id = a.account_id";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback List</title>
    <?php include '../bootstrap.php'; ?>
    <style>
        body {
            background: #f8f9fa;
        }
        .feedback-container {
            max-width: 800px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="feedback-container">
            <h2 class="text-center">User Feedback</h2>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Rating</th>
                            <th>Overall Experience</th>
                            <th>Room Cleanliness</th>
                            <th>Staff Service</th>
                            <th>Facilities</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['rating']); ?></td>
                                <td><?php echo htmlspecialchars($row['overall_experience']); ?></td>
                                <td><?php echo htmlspecialchars($row['room_cleanliness']); ?></td>
                                <td><?php echo htmlspecialchars($row['staff_service']); ?></td>
                                <td><?php echo htmlspecialchars($row['facilities']); ?></td>
                                <td><?php echo htmlspecialchars($row['comment']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info text-center">No feedback available.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>