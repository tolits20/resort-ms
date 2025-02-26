<?php
include('../../resources/database/config.php');
if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $account_id = $_SESSION['ID'];
    $rating = $_POST['rating'];
    $comment = trim($_POST['comment']);

    if (!empty($rating) && !empty($comment)) {
        $sql = "INSERT INTO feedback (account_id, rating, comment, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'iis', $account_id, $rating, $comment);
        if (mysqli_stmt_execute($stmt)) {
            header("location: ../thankyou.php"); 
            exit;
        } else {
            echo "<div class='alert alert-danger'>Error submitting feedback.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>All fields are required.</div>";
    }
} else {
    header("location: create.php");
    exit;
}
