<?php
include('../../resources/database/config.php');


if (!isset($_SESSION['ID'])) {
    header("location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $rating = $_POST['rating'];
    $overall_experience = $_POST['overall_experience'];
    $room_cleanliness = $_POST['room_cleanliness'];
    $staff_service = $_POST['staff_service'];
    $facilities = $_POST['facilities'];
    $comment = $_POST['comment'];

    $sql = "INSERT INTO feedback (book_id, rating, overall_experience, room_cleanliness, staff_service, facilities, comment, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iisssss', $book_id, $rating, $overall_experience, $room_cleanliness, $staff_service, $facilities, $comment);
    
    if (mysqli_stmt_execute($stmt)) {
        header("location: index.php?");
        exit;
    } else {
        echo "<div class='alert alert-danger text-center'>Error submitting feedback. Please try again later.</div>";
    }
}
?>