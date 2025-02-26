<?php
include('../../resources/database/config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $account_id = $_POST['account_id'];
    $rating = $_POST['rating'];
    $feedback = trim($_POST['feedback']);

    if (!empty($rating) && !empty($feedback)) {
        $sql = "INSERT INTO feedback (account_id, rating, comment, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'iis', $account_id, $rating, $feedback);
        if (mysqli_stmt_execute($stmt)) {
            header("location: ../thankyou.php"); // Redirect to a thank you page
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
