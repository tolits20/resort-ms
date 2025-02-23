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
        $price = $_POST['price'];
        $status = "pending"; // Default booking status

        // Validate required fields
        if (empty($room_id) || empty($check_in) || empty($check_out)) {
            throw new Exception("All fields are required.");
        }

        // Validate that check-in date is before check-out date
        if ($check_in >= $check_out) {
            throw new Exception("Check-out date must be after check-in date.");
        }

        // Insert booking into the booking table
        $sql = "INSERT INTO booking (account_id, room_id, check_in, check_out, status, price) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare booking statement: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "iisssd", $account_id, $room_id, $check_in, $check_out, $status, $price);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to insert booking: " . mysqli_stmt_error($stmt));
        }

        // Optionally update the room status to 'booked'
        $sql_update = "UPDATE room SET status = 'booked', updated_at = NOW() WHERE room_id = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "i", $room_id);
        mysqli_stmt_execute($stmt_update);

        header("location: index.php");
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("location: create.php");
    exit;
}
?>
