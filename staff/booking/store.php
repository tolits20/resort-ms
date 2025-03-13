<?php
include ('../../resources/database/config.php');
include("../../admin/includes/system_update.php");

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $staff_id = $_SESSION['ID'];
        $guest_id = intval($_POST['guest_id']);
        $room_id = intval($_POST['room_id']);
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];
        $check_in_time = $_POST['checkInTime'];
        $check_out_time = $_POST['checkOutTime'];
        $amount = floatval($_POST['updated_price']);
        $book_status = "confirmed";
        $payment_status = "paid";

        if (!$room_id || !$guest_id || empty($check_in) || empty($check_in_time) || empty($check_out) || empty($check_out_time)) {
            throw new Exception("All booking fields are required.");
        }

        $check_in_datetime = "$check_in $check_in_time";
        $check_out_datetime = "$check_out $check_out_time";

        $conn->begin_transaction();

        $sql = "INSERT INTO booking (guest_id, room_id, check_in, check_out, book_status, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $guest_id, $room_id, $check_in_datetime, $check_out_datetime, $book_status);
        $stmt->execute();

        if ($stmt->affected_rows == 0) {
            throw new Exception("Failed to insert booking.");
        }

        $last_id = $stmt->insert_id;
        $payment_type = "cash";
        $sql2 = "INSERT INTO payment (book_id, amount, pay_amount, payment_type, payment_status, created_at)
                 VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("iddss", $last_id, $amount, $amount, $payment_type, $payment_status);
        $stmt2->execute();

        if ($stmt2->affected_rows == 0) {
            throw new Exception("Failed to insert payment.");
        }

        $conn->commit();

        header("location: http://localhost/resort-ms/staff/booking/index.php?switch=guest");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>
