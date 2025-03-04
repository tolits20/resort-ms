<?php
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $account_id = $_SESSION['ID'];
        $room_id = intval($_POST['room_id']);
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];
        $check_in_time = $_POST['checkInTime'];
        $check_out_time = $_POST['checkOutTime'];
        $book_status = "pending";

        if (!$room_id || empty($check_in) || empty($check_in_time) || empty($check_out) || empty($check_out_time)) {
            throw new Exception("All booking fields are required.");
        }

        $check_in_datetime = "$check_in $check_in_time";
        $check_out_datetime = "$check_out $check_out_time";

        $sql = "INSERT INTO booking (account_id, room_id, check_in, check_out, book_status, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iisss", $account_id, $room_id, $check_in_datetime, $check_out_datetime, $book_status);
        mysqli_stmt_execute($stmt);

        $last_id = mysqli_insert_id($conn);
        header("location: payment.php?book_id=$last_id");
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>
