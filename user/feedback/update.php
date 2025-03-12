<?php
session_start();
include('../../resources/database/config.php');
include("../../admin/includes/system_update.php");

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$account_id = $_SESSION['ID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback_id = isset($_POST['feedback_id']) ? (int) $_POST['feedback_id'] : 0;
    $book_id = isset($_POST['book_id']) ? (int) $_POST['book_id'] : 0;
    $rating = isset($_POST['rating']) ? (int) $_POST['rating'] : 0;
    $overall_experience = trim($_POST['overall_experience']);
    $room_cleanliness = trim($_POST['room_cleanliness']);
    $staff_service = trim($_POST['staff_service']);
    $facilities = trim($_POST['facilities']);
    $comment = trim($_POST['comment']);

    if ($feedback_id <= 0 || $book_id <= 0 || $rating < 1 || $rating > 5) {
        header("Location: index.php?error=Invalid input data.");
        exit;
    }

    // Validate that the feedback exists and belongs to the logged-in user
    $sql = "SELECT f.feedback_id 
            FROM feedback f 
            JOIN booking b ON f.book_id = b.book_id 
            WHERE f.feedback_id = ? AND b.account_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing query (validation): " . $conn->error);
    }

    $stmt->bind_param("ii", $feedback_id, $account_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        header("Location: index.php?error=Feedback not found or unauthorized.");
        exit;
    }

    // Update feedback in the database
    $sql = "UPDATE feedback SET 
                rating = ?, 
                overall_experience = ?, 
                room_cleanliness = ?, 
                staff_service = ?, 
                facilities = ?, 
                comment = ? 
            WHERE feedback_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing query (update): " . $conn->error);
    }

    $stmt->bind_param("isssssi", $rating, $overall_experience, $room_cleanliness, $staff_service, $facilities, $comment, $feedback_id);

    if ($stmt->execute()) {
        header("Location: index.php?success=Feedback updated successfully");
        exit;
    } else {
        die("Error updating feedback: " . $stmt->error);
    }
} else {
    header("Location: index.php");
    exit;
}
?>
