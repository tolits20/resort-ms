<?php
include('../../resources/database/config.php');
include("../../admin/includes/system_update.php"); 

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $guest_id = mysqli_real_escape_string($conn, $_POST['guest_id']);
    $room_id = mysqli_real_escape_string($conn, $_POST['room_id']);
    $check_in = mysqli_real_escape_string($conn, $_POST['check_in']);
    $check_out = mysqli_real_escape_string($conn, $_POST['check_out']);
    $created_at = date('Y-m-d H:i:s');
    $status = 'Pending';

    // Calculate number of nights and total amount
    $check_in_obj = new DateTime($check_in);
    $check_out_obj = new DateTime($check_out);
    $interval = $check_in_obj->diff($check_out_obj);
    $nights = $interval->days;

    // Get room price
    $room_query = "SELECT price FROM rooms WHERE id = '$room_id'";
    $room_result = mysqli_query($conn, $room_query);
    $room = mysqli_fetch_assoc($room_result);
    $total_amount = $nights * $room['price'];

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert booking
        $booking_query = "INSERT INTO bookings (guest_id, room_id, check_in, check_out, nights, total_amount, status, created_at) 
                         VALUES ('$guest_id', '$room_id', '$check_in', '$check_out', '$nights', '$total_amount', '$status', '$created_at')";
        
        mysqli_query($conn, $booking_query);

        // Update room status
        $update_room = "UPDATE rooms SET status = 'Booked' WHERE id = '$room_id'";
        mysqli_query($conn, $update_room);

        // Update guest status
        $update_guest = "UPDATE guests SET status = 'Booked' WHERE id = '$guest_id'";
        mysqli_query($conn, $update_guest);

        // Commit transaction
        mysqli_commit($conn);

        $_SESSION['success'] = "Booking created successfully!";
        header("Location: index.php");
        exit;

    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: create.php?guest_id=" . $guest_id);
        exit;
    }
} else {
    header("Location: create.php");
    exit;
}
?>