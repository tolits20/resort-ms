<?php
include('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $booking_id = $_POST['booking_id'];
        $account_id = $_SESSION['ID'];
        $room_id = $_POST['room_id'];
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];
        $price = $_POST['price'];

        if (empty($room_id) || empty($check_in) || empty($check_out)) {
            throw new Exception("All fields are required.");
        }

        $sql_old = "SELECT room_id FROM booking WHERE booking_id = ? AND account_id = ?";
        $stmt_old = $conn->prepare($sql_old);
        $stmt_old->bind_param("ii", $booking_id, $account_id);
        $stmt_old->execute();
        $result_old = $stmt_old->get_result();
        
        if ($result_old->num_rows == 0) {
            throw new Exception("Booking not found or unauthorized.");
        }

        $old_booking = $result_old->fetch_assoc();
        $old_room_id = $old_booking['room_id'];

        $sql_update = "UPDATE booking SET room_id = ?, check_in = ?, check_out = ?, price = ?, updated_at = NOW()
                       WHERE booking_id = ? AND account_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("issdii", $room_id, $check_in, $check_out, $price, $booking_id, $account_id);
        
        if (!$stmt_update->execute()) {
            throw new Exception("Failed to update booking.");
        }

        $sql_new_room = "UPDATE room SET room_status = 'booked', updated_at = NOW() WHERE room_id = ?";
        $stmt_new_room = $conn->prepare($sql_new_room);
        $stmt_new_room->bind_param("i", $room_id);
        $stmt_new_room->execute();

        if ($old_room_id != $room_id) {
            $sql_old_room = "UPDATE room SET room_status = 'available', updated_at = NOW() WHERE room_id = ?";
            $stmt_old_room = $conn->prepare($sql_old_room);
            $stmt_old_room->bind_param("i", $old_room_id);
            $stmt_old_room->execute();
        }

        $sql_notif = "UPDATE booking_notification SET booking_status = 'updated', Date = NOW() WHERE book_id = ?";
        $stmt_notif = $conn->prepare($sql_notif);
        $stmt_notif->bind_param("i", $booking_id);
        $stmt_notif->execute();

        header("location: index.php?success=Booking updated");
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("location: edit.php?id=$booking_id");
    exit;
}
?>
