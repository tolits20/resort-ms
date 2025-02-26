<?php
include('../../resources/database/config.php');
include('../../user/bootstrap.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$user_id = $_SESSION['ID'];

$sql = "SELECT * FROM booking WHERE account_id = ? AND book_status = 'confirmed' LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger text-center'>You can only submit feedback if you have a confirmed booking.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
</head>
<body class="container mt-5">
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
            <label class="form-label">Your Feedback:</label>
            <textarea name="comment" class="form-control" rows="4" required></textarea>
        </div>
        <input type="hidden" name="account_id" value="<?php echo $account_id=$_SESSION['ID']; ?>">
        <button type="submit" class="btn btn-primary w-100">Submit Feedback</button>
    </form>
</body>
</html>
