<?php
require '../../resources/database/config.php';

// Ensure all required POST data is provided
if (!isset($_POST['booking_id']) || !isset($_POST['room_id']) || !isset($_POST['check_in']) || !isset($_POST['check_out']) || !isset($_POST['checkInTime']) || !isset($_POST['checkOutTime'])) {
    die("Missing required data.");
}

$booking_id = intval($_POST['booking_id']);
$room_id = intval($_POST['room_id']);
$check_in = $_POST['check_in'] . ' ' . $_POST['checkInTime'];
$check_out = $_POST['check_out'] . ' ' . $_POST['checkOutTime'];

// Start transaction
$conn->begin_transaction();

try {
    // Update the booking in the database
    $updateQuery = "UPDATE booking 
                    SET room_id = ?, check_in = ?, check_out = ?, updated_at = NOW()
                    WHERE book_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("issi", $room_id, $check_in, $check_out, $booking_id);

    if ($stmt->execute()) {
        // Insert a record into booking_notification
        $notifQuery = "INSERT INTO booking_notification (book_id, booking_status) VALUES (?, ?)";
        $notifStmt = $conn->prepare($notifQuery);
        $booking_status = 'updated'; // assuming you want to log the status as 'updated'
        $notifStmt->bind_param("is", $booking_id, $booking_status);
        
        if ($notifStmt->execute()) {
            // Commit transaction
            $conn->commit();
            echo "Booking updated and notification logged successfully.";
        } else {
            throw new Exception("Error logging notification: " . $notifStmt->error);
        }
    } else {
        throw new Exception("Error updating booking: " . $stmt->error);
    }
} catch (Exception $e) {
    // Rollback transaction if any query fails
    $conn->rollback();
    echo $e->getMessage();
}

// Redirect back to the bookings page
header("Location: index.php");
exit;
?>
