<?php
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("Location: ../../login.php");
    exit();
}

$user_id = $_SESSION['ID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];
    $check_in_date = $_POST['check_in'];
    $check_in_time = $_POST['check_in_time'];
    $check_out = $_POST['check_out'];
    // $price = $_POST['price']; 

    $check_in = $check_in_date . ' ' . $check_in_time;

    $check_in_hour = (int)substr($check_in_time, 0, 2);
    if (($check_in_hour >= 7 && $check_in_hour < 17) || ($check_in_hour >= 19 || $check_in_hour < 5)) {
        $sql = "SELECT * FROM room WHERE room_id = ? AND status = 'available'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $sql = "INSERT INTO booking (account_id, room_id, check_in, check_out, status) 
                    VALUES (?, ?, ?, ?, 'pending')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiss", $user_id, $room_id, $check_in, $check_out);

            if ($stmt->execute()) {
                $sql = "UPDATE room SET status = 'confirmed' WHERE room_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $room_id);
                $stmt->execute();

                echo "Reservation successful. Please wait for confirmation.";
            } else {
                echo "Error: Could not make the reservation.";
            }
        } else {
            echo "Sorry, the room is not available for the selected dates.";
        }
    } else {
        echo "Invalid check-in time. Please select a time between 7:00 AM to 5:00 PM or 7:00 PM to 5:00 AM.";
    }
}
?>


</form>
