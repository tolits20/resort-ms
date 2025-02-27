<?php
include('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid booking ID.");
}

$book_id = $_GET['id'];
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

    $sql_update = "UPDATE booking SET book_status = 'cancelled', updated_at = NOW() WHERE book_id = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "i", $book_id);
    mysqli_stmt_execute($stmt_update);

    $sql_room = "UPDATE room SET room_status = 'available', updated_at = NOW() WHERE room_id = ?";
    $stmt_room = mysqli_prepare($conn, $sql_room);
    mysqli_stmt_bind_param($stmt_room, "i", $room_id);
    mysqli_stmt_execute($stmt_room);

    $sql_notif = "UPDATE booking_notification SET booking_status = 'cancelled', Date = NOW() WHERE book_id = ?";
    $stmt_notif = mysqli_prepare($conn, $sql_notif);
    mysqli_stmt_bind_param($stmt_notif, "i", $book_id);
    mysqli_stmt_execute($stmt_notif);

    header("location: index.php?success=Booking cancelled");
    exit;
} else {
    die("Booking not found or unauthorized.");
}
?>
