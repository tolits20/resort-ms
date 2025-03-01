<?php
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

if (isset($_POST['submit'])) {
    try {
        $account_id = $_SESSION['ID'];
        $room_id = $_POST['room_id'];
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];
        $check_in_time = $_POST['check_in_time'];
        $check_out_time = $_POST['check_out_time'];
        $price = $_POST['price'];
        $book_status = "pending";

        if (empty($room_id) || empty($check_in) || empty($check_in_time) || empty($check_out) || empty($check_out_time)) {
            throw new Exception("All booking fields are required.");
        }

        $check_in_datetime = $check_in . " " . $check_in_time;
        $check_out_datetime = $check_out . " " . $check_out_time;

        $today = date("Y-m-d H:i:s");
        if ($check_in_datetime < $today) {
            throw new Exception("Check-in date cannot be in the past.");
        }

        $sql = "INSERT INTO booking (account_id, room_id, check_in, check_out, book_status, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "iisss", $account_id, $room_id, $check_in_datetime, $check_out_datetime, $book_status);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to insert booking: " . mysqli_stmt_error($stmt));
        }

        $last_id = mysqli_insert_id($conn);

        $notif = "INSERT INTO booking_notification(book_id, booking_status, Date) VALUES ($last_id, '$book_status', NOW())";
        mysqli_query($conn, $notif);

        header("location: payment.php?book_id=$last_id");
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
