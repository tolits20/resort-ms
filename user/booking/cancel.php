<?php
include('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

if (!isset($_GET['booking_id']) || !is_numeric($_GET['booking_id'])) {
    die("Invalid booking ID.");
}

$book_id = intval($_GET['booking_id']);
$account_id = $_SESSION['ID'];

$sql_check = "SELECT book_status, room_id FROM booking WHERE book_id = ? AND account_id = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "ii", $book_id, $account_id);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);

if ($row = mysqli_fetch_array($result_check)) {
    if ($row['book_status'] != 'pending') {
        die("You can only cancel pending bookings.");
    }

    $room_id = $row['room_id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update booking status to cancelled
        $sql_update = "UPDATE booking SET book_status = 'cancelled', updated_at = NOW() WHERE book_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $book_id);
        $stmt_update->execute();

        if ($stmt_update->affected_rows == 0) {
            throw new Exception("Failed to update booking status.");
        }

        // Update room status to available
        $sql_room = "UPDATE room SET room_status = 'available', updated_at = NOW() WHERE room_id = ?";
        $stmt_room = $conn->prepare($sql_room);
        $stmt_room->bind_param("i", $room_id);
        $stmt_room->execute();

        if ($stmt_room->affected_rows == 0) {
            throw new Exception("Failed to update room status.");
        }

        // Insert booking notification
        $sql_notif = "INSERT INTO booking_notification (book_id, booking_status, Date) VALUES (?, 'cancelled', NOW())";
        $stmt_notif = $conn->prepare($sql_notif);
        $stmt_notif->bind_param("i", $book_id);
        $stmt_notif->execute();

        if ($stmt_notif->affected_rows == 0) {
            throw new Exception("Failed to insert booking notification.");
        }

        // Commit transaction
        $conn->commit();

        header("location: index.php?success=Booking cancelled");
        exit;
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
} else {
    die("Booking not found or unauthorized.");
}
?>