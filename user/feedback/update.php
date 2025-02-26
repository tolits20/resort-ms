<?php
include('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$account_id = $_SESSION['ID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback_id = $_POST['feedback_id'];
    $rating = $_POST['rating'];
    $comment = trim($_POST['comment']);

    if (empty($rating) || empty($comment)) {
        die("Error: Rating and comment cannot be empty.");
    }

    $sql = "UPDATE feedback SET rating = ?, comment = ?, updated_at = NOW() WHERE feedback_id = ? AND account_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isii", $rating, $comment, $feedback_id, $account_id);
    if ($stmt->execute()) {
        header("location: index.php?success=Feedback updated");
        exit;
    } else {
        die("Error updating feedback.");
    }
}
?>
