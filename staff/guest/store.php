<?php
include('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_id = $_SESSION['ID'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO guest (account_id, fname, lname, gender, contact, email, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("isssss", $account_id, $fname, $lname, $gender, $contact, $email);

    if ($stmt->execute()) {
        echo "New record created successfully";
        header("location: ../dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
