<?php
include ('../../resources/database/config.php');
include("../../admin/includes/system_update.php");

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}



echo $_POST['updated_price'];
echo $amount = floatval($_POST['updated_price']);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $account_id = $_SESSION['ID'];
        $room_id = intval($_POST['room_id']);
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];
        $check_in_time = $_POST['checkInTime'];
        $check_out_time = $_POST['checkOutTime'];
        // $price = floatval($_POST['price']);
        $book_status = "pending";


        if (!$room_id || empty($check_in) || empty($check_in_time) || empty($check_out) || empty($check_out_time)) {
            throw new Exception("All booking fields are required.");
        }

        $check_in_datetime = "$check_in $check_in_time";
        $check_out_datetime = "$check_out $check_out_time";

        // Start transaction
        $conn->begin_transaction();

        // Insert booking
        $sql = "INSERT INTO booking (account_id, room_id, check_in, check_out, book_status, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $account_id, $room_id, $check_in_datetime, $check_out_datetime, $book_status);
        $stmt->execute();

        if ($stmt->affected_rows == 0) {
            throw new Exception("Failed to insert booking.");
        }

        $last_id = $stmt->insert_id;

        // Insert booking notification
        $message="New Booking";
        $sql_notif = "INSERT INTO booking_notification (book_id, message, Date) VALUES (?, ?, NOW())";
        $stmt_notif = $conn->prepare($sql_notif);
        $booking_status = 'pending';
        $stmt_notif->bind_param("is", $last_id, $message);
        $stmt_notif->execute();

        if ($stmt_notif->affected_rows == 0) {
            throw new Exception("Failed to insert booking notification.");
        }

        // Commit transaction
        $conn->commit();

        // Redirect to payment page
        header("location: ../payment/create.php?book_id=$last_id&amount=$amount");
        exit;
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>