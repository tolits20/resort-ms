<?php
include('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$account_id = $_SESSION['ID'];
$feedback_id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM feedback WHERE feedback_id = ? AND account_id = ?";
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
    <title>Edit Feedback</title>
    <?php include('../bootstrap.php'); ?>
</head>
<body>
<div class="container mt-5">
    <h2>Edit Your Feedback</h2>
    <form action="update.php" method="POST">
        <input type="hidden" name="feedback_id" value="<?php echo $feedback['feedback_id']; ?>">
        <div class="mb-3">
            <label class="form-label">Rating</label>
            <select name="rating" class="form-control" required>
                <option value="1" <?php if ($feedback['rating'] == 1) echo "selected"; ?>>⭐</option>
                <option value="2" <?php if ($feedback['rating'] == 2) echo "selected"; ?>>⭐⭐</option>
                <option value="3" <?php if ($feedback['rating'] == 3) echo "selected"; ?>>⭐⭐⭐</option>
                <option value="4" <?php if ($feedback['rating'] == 4) echo "selected"; ?>>⭐⭐⭐⭐</option>
                <option value="5" <?php if ($feedback['rating'] == 5) echo "selected"; ?>>⭐⭐⭐⭐⭐</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Comment</label>
            <textarea name="comment" class="form-control" required><?php echo htmlspecialchars($feedback['comment']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Update Feedback</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
