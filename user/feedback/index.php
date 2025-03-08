<?php
include('../../resources/database/config.php');
include('../bootstrap.php'); // Ensure correct path

if (!isset($_SESSION['ID'])) {
    header("location: ../login.php");
    exit;
}

$account_id = $_SESSION['ID'];

// Prepare and execute the SQL query securely
$sql = "SELECT f.*, a.username, b.account_id 
        FROM feedback f 
        JOIN booking b ON f.book_id = b.book_id 
        JOIN account a ON b.account_id = a.account_id 
        WHERE b.account_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Query preparation failed: " . $conn->error); // Debugging: Show SQL error
}

$stmt->bind_param("i", $account_id);
$stmt->execute();
$result = $stmt->get_result(); // Fetch result from prepared statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f8f9fa;
        }
        .feedback-container {
            max-width: 900px;
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
            <?php if ($result->num_rows > 0): ?>
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['rating']); ?></td>
                                <td><?php echo htmlspecialchars($row['overall_experience']); ?></td>
                                <td><?php echo htmlspecialchars($row['room_cleanliness']); ?></td>
                                <td><?php echo htmlspecialchars($row['staff_service']); ?></td>
                                <td><?php echo htmlspecialchars($row['facilities']); ?></td>
                                <td><?php echo htmlspecialchars($row['comment']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td>
                                    <a href="edit.php?feedback_id=<?php echo $row['feedback_id']; ?>" class="text-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
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
