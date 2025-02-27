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
        $book_status = "pending";

        if (empty($room_id) || empty($check_in)) {
            throw new Exception("Room selection and check-in/out dates are required.");
        }

        $today = date("Y-m-d");
        if ($check_in < $today) {
            throw new Exception("Check-in date cannot be in the past.");
        }

        $sql = "INSERT INTO booking (account_id, room_id, check_in, check_out, book_status, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare booking statement: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "iisss", $account_id, $room_id, $check_in, $check_out, $book_status);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to insert booking: " . mysqli_stmt_error($stmt));
        }

        $last_id = mysqli_insert_id($conn);
        $notif = "INSERT INTO booking_notification(book_id, booking_status, Date) VALUES ($last_id, '$book_status', NOW())";
        mysqli_query($conn, $notif);

        $sql_update = "UPDATE room SET room_status = 'booked', updated_at = NOW() WHERE room_id = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "i", $room_id);
        mysqli_stmt_execute($stmt_update);

        header("location: index.php?success=Booking created successfully");
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("location: create.php");
    exit;
}

?>
