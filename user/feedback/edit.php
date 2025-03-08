<?php
include('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$account_id = $_SESSION['ID'];
$feedback_id = $_GET['feedback_id'] ?? 0;

// Check if the feedback exists and belongs to the user
$sql = "SELECT f.*, b.book_id 
        FROM feedback f 
        JOIN booking b ON f.book_id = b.book_id 
        WHERE f.feedback_id = ? AND b.account_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $feedback_id, $account_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: Feedback not found or you are not authorized.");
}

$feedback = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Feedback</title>
    <?php include('../bootstrap.php'); ?>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Edit Your Feedback</h2>
    <form action="update.php" method="POST">
        <input type="hidden" name="feedback_id" value="<?php echo $feedback['feedback_id']; ?>">
        <input type="hidden" name="book_id" value="<?php echo $feedback['book_id']; ?>">

        <div class="mb-3">
            <label class="form-label">Rating (1-5):</label>
            <select name="rating" class="form-select" required>
                <option value="1" <?php if ($feedback['rating'] == 1) echo "selected"; ?>>1 - Poor</option>
                <option value="2" <?php if ($feedback['rating'] == 2) echo "selected"; ?>>2 - Fair</option>
                <option value="3" <?php if ($feedback['rating'] == 3) echo "selected"; ?>>3 - Good</option>
                <option value="4" <?php if ($feedback['rating'] == 4) echo "selected"; ?>>4 - Very Good</option>
                <option value="5" <?php if ($feedback['rating'] == 5) echo "selected"; ?>>5 - Excellent</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Overall Experience:</label>
            <textarea name="overall_experience" class="form-control" rows="2" required><?php echo htmlspecialchars($feedback['overall_experience']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Room Cleanliness:</label>
            <textarea name="room_cleanliness" class="form-control" rows="2" required><?php echo htmlspecialchars($feedback['room_cleanliness']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Staff Service:</label>
            <textarea name="staff_service" class="form-control" rows="2" required><?php echo htmlspecialchars($feedback['staff_service']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Facilities:</label>
            <textarea name="facilities" class="form-control" rows="2" required><?php echo htmlspecialchars($feedback['facilities']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Your Feedback:</label>
            <textarea name="comment" class="form-control" rows="4" required><?php echo htmlspecialchars($feedback['comment']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-success w-100">Update Feedback</button>
        <a href="index.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
    </form>
</div>
</body>
</html>
