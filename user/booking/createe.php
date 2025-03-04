You can help me by making some designs for my code for a create.php and the code is about booking a resort room. Make some appropriate design for it.

<?php
require '../../resources/database/config.php';

// Ensure a room is selected
if (!isset($_GET['room_id']) || empty($_GET['room_id'])) {
    die("Room not selected.");
}

$room_id = intval($_GET['room_id']);

// Fetch room details including discount
$roomQuery = "SELECT 
    room.room_id,
    room.room_code,
    room.room_type,
    room.price,
    discount.discount_name,
    discount.discount_percentage,
    ROUND(room.price - (room.price * (discount.discount_percentage / 100)), 2) AS discounted_price
FROM room
LEFT JOIN discount 
    ON room.room_type = discount.applicable_room 
    AND discount.discount_status = 'activate'
    AND NOW() BETWEEN discount.discount_start AND discount.discount_end
WHERE room.room_id = ?";

$stmt = $conn->prepare($roomQuery);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$roomResult = $stmt->get_result();
$room = $roomResult->fetch_assoc();

if (!$room) {
    die("Room not found.");
}

// Fetch booked dates for this room
$bookedDates = [];
$bookingQuery = "SELECT check_in FROM booking WHERE room_id = ?";
$stmt = $conn->prepare($bookingQuery);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$bookingResult = $stmt->get_result();

while ($row = $bookingResult->fetch_assoc()) {
    $bookedDates[] = date("Y-m-d", strtotime($row['check_in']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?= htmlspecialchars($room['room_code']); ?></title>
    
    <!-- Include jQuery & jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        .discounted { text-decoration: line-through; color: red; }
    </style>
</head>
<body>
    <h2>Book <?= htmlspecialchars($room['room_code']); ?></h2>

    <p>Price: 
        <?php if ($room['discount_percentage'] > 0): ?>
            <span class="discounted">$<?= number_format($room['price'], 2); ?></span>
            <strong>$<?= number_format($room['discounted_price'], 2); ?></strong>
            (<?= htmlspecialchars($room['discount_percentage']); ?>% off)
        <?php else: ?>
            <strong>$<?= number_format($room['price'], 2); ?></strong>
        <?php endif; ?>
    </p>

    <form method="POST" action="store.php">
        <input type="hidden" name="room_id" value="<?= $room_id; ?>">

        <label for="check_in">Check-in Date:</label>
        <input type="text" id="check_in" name="check_in" readonly required>

        <label for="time_slot">Check-in Time Slot:</label>
        <select name="time_slot" id="time_slot" required>
            <option value="7:00 AM - 5:00 PM">7:00 AM - 5:00 PM</option>
            <option value="7:00 PM - 5:00 AM">7:00 PM - 5:00 AM</option>
            <option value="7:00 PM - 5:00 PM">7:00 PM - 5:00 PM</option>
            <option value="7:00 AM - 5:00 AM">7:00 AM - 5:00 AM</option>
        </select>

        <label for="check_out">Check-out Date:</label>
        <input type="text" id="check_out" name="check_out" readonly disabled>
        <input type="hidden" id="hidden_check_out" name="check_out">
        <input type="hidden" id="check_in_time" name="checkInTime">
        <input type="hidden" id="check_out_time" name="checkOutTime">

        <button type="submit">Confirm Booking</button>
    </form>

    <script>
        $(document).ready(function () {
            let bookedDates = <?= json_encode($bookedDates); ?>;

            $("#check_in").datepicker({
                dateFormat: "yy-mm-dd",
                minDate: 0,
                beforeShowDay: function (date) {
                    let formattedDate = $.datepicker.formatDate("yy-mm-dd", date);
                    return [bookedDates.indexOf(formattedDate) === -1];
                },
                onSelect: function (selectedDate) {
                    updateCheckoutDate(selectedDate);
                }
            });

            $("#time_slot").change(function () {
                let checkInDate = $("#check_in").val();
                if (checkInDate) {
                    updateCheckoutDate(checkInDate);
                }
            });

            function updateCheckoutDate(checkInDate) {
                let timeSlot = $("#time_slot").val();
                if (!timeSlot) return;

                let checkInDateTime = new Date(checkInDate);
                let checkOutDateTime = new Date(checkInDate);
                let checkInTime, checkOutTime;

                switch (timeSlot) {
                    case "7:00 AM - 5:00 PM":
                        checkInTime = "07:00:00";
                        checkOutTime = "17:00:00";
                        break;
                    case "7:00 PM - 5:00 AM":
                        checkInTime = "19:00:00";
                        checkOutTime = "05:00:00";
                        checkOutDateTime.setDate(checkOutDateTime.getDate() + 1);
                        break;
                    case "7:00 PM - 5:00 PM":
                        checkInTime = "19:00:00";
                        checkOutTime = "17:00:00";
                        checkOutDateTime.setDate(checkOutDateTime.getDate() + 1);
                        break;
                    case "7:00 AM - 5:00 AM":
                        checkInTime = "07:00:00";
                        checkOutTime = "05:00:00";
                        checkOutDateTime.setDate(checkOutDateTime.getDate() + 1);
                        break;
                }

                let checkOutFormatted = $.datepicker.formatDate("yy-mm-dd", checkOutDateTime);
                $("#check_out").val(checkOutFormatted);
                $("#hidden_check_out").val(checkOutFormatted);
                $("#check_in_time").val(checkInTime);
                $("#check_out_time").val(checkOutTime);
            }
        });
    </script>
</body>
</html>