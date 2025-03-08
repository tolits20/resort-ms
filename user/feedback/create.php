<?php
include('../../resources/database/config.php');
include('../bootstrap.php');


if (!isset($_SESSION['ID'])) {
    header("location: ../login.php");
    exit;
}

$account_id = $_SESSION['ID'];

// Check if the user has any completed bookings
$sql = "SELECT * FROM booking WHERE account_id = ? AND book_status = 'completed' LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $account_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_array($result);


if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger text-center'>You can only submit feedback if you have a completed booking.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <?php include '../bootstrap.php'; ?>
    <style>
        body {
            background: #f8f9fa;
        }
        .feedback-container {
            max-width: 600px;
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
            <h2 class="text-center">Submit Your Feedback</h2>
            <form action="store.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Rating (1-5):</label>
                    <select name="rating" class="form-select" required>
                        <option value="">Select Rating</option>
                        <option value="1">1 - Poor</option>
                        <option value="2">2 - Fair</option>
                        <option value="3">3 - Good</option>
                        <option value="4">4 - Very Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Overall Experience:</label>
                    <textarea name="overall_experience" class="form-control" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Room Cleanliness:</label>
                    <textarea name="room_cleanliness" class="form-control" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Staff Service:</label>
                    <textarea name="staff_service" class="form-control" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Facilities:</label>
                    <textarea name="facilities" class="form-control" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Your Feedback:</label>
                    <textarea name="comment" class="form-control" rows="4" required></textarea>
                </div>
                <input type="hidden" name="book_id" value="<?php echo $booking['book_id'] ?>">
                <button type="submit" class="btn btn-primary w-100">Submit Feedback</button>
            </form>
        </div>
    </div>
</body>
</html>