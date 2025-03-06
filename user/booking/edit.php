<?php
require '../../resources/database/config.php';

// Ensure a booking ID is provided
if (!isset($_GET['booking_id']) || empty($_GET['booking_id'])) {
    die("Booking ID not provided.");
}

$booking_id = intval($_GET['booking_id']);

// Fetch booking details
$bookingQuery = "SELECT 
    b.book_id,
    b.room_id,
    b.check_in,
    b.check_out,
    r.room_code,
    r.room_type,
    r.price,
    d.discount_name,
    d.discount_percentage,
    ROUND(r.price - (r.price * (d.discount_percentage / 100)), 2) AS discounted_price
FROM booking b
JOIN room r ON b.room_id = r.room_id
LEFT JOIN discount d
    ON r.room_type = d.applicable_room 
    AND d.discount_status = 'activate'
    AND NOW() BETWEEN d.discount_start AND d.discount_end
WHERE b.book_id = ?";

$stmt = $conn->prepare($bookingQuery);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$bookingResult = $stmt->get_result();
$booking = $bookingResult->fetch_assoc();

if (!$booking) {
    die("Booking not found.");
}

$room_id = $booking['room_id'];

// Fetch all rooms
$roomListQuery = "SELECT room_id, room_code FROM room";
$roomListResult = $conn->query($roomListQuery);
$rooms = $roomListResult->fetch_all(MYSQLI_ASSOC);

// Fetch booked dates for this room
$bookedDates = [];
$bookingQuery = "SELECT check_in FROM booking WHERE book_status = 'confirmed' AND room_id = ?";
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
    <title>Edit Booking for <?= htmlspecialchars($booking['room_code']); ?></title>
    
    <!-- Include jQuery & jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom Styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .back-button {
            display: inline-block;
            margin: 20px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #218838;
        }
        .back-button i {
            margin-right: 5px;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .price-box {
            text-align: center;
            margin-bottom: 15px;
        }
        .discounted {
            text-decoration: line-through;
            color: red;
            font-size: 14px;
        }
        .discounted strong {
            color: green;
            font-size: 18px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            font-weight: bold;
            color: #555;
        }
        input, select, button {
            padding: 10px;
            font-size: 16px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        input[readonly] {
            background: #e9ecef;
            cursor: text;
        }
        button {
            margin-top: 20px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<!-- Back Button -->
<a href="javascript:history.back()" class="back-button">
    <i class="fas fa-arrow-left"></i> Back
</a>

<div class="container">
    <!-- Booking Form -->
    <div class="booking-form">
        <h2>Edit Booking for <?= htmlspecialchars($booking['room_code']); ?></h2>

        <div class="price-box">
            <p>Price: 
                <?php if ($booking['discount_percentage'] > 0): ?>
                    <span class="discounted">$<?= number_format($booking['price'], 2); ?></span>
                    <strong id="dynamic-price">$<?= number_format($booking['discounted_price'], 2); ?></strong>
                    (<?= htmlspecialchars($booking['discount_percentage']); ?>% off)
                <?php else: ?>
                    <strong id="dynamic-price">$<?= number_format($booking['price'], 2); ?></strong>
                <?php endif; ?>
            </p>
        </div>
        <input type="hidden" id="original-price" value="<?= $booking['discount_percentage'] > 0 ? $booking['discounted_price'] : $booking['price']; ?>">
        <form method="POST" action="update.php">
            <input type="hidden" name="booking_id" value="<?= $booking_id; ?>">

            <label for="room_id">Room:</label>
            <select name="room_id" id="room_id" required>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?= $room['room_id']; ?>" <?= $room['room_id'] == $booking['room_id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($room['room_code']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="check_in">Check-in Date:</label>
            <input type="text" id="check_in" name="check_in" readonly required value="<?= htmlspecialchars(date("Y-m-d", strtotime($booking['check_in']))); ?>">

            <label for="time_slot">Check-in Time Slot:</label>
            <select name="time_slot" id="time_slot" required>
                <option value="7:00 AM - 5:00 PM" <?= $booking['check_in'] == "07:00:00" && $booking['check_out'] == "17:00:00" ? 'selected' : ''; ?>>7:00 AM - 5:00 PM</option>
                <option value="7:00 PM - 5:00 AM" <?= $booking['check_in'] == "19:00:00" && $booking['check_out'] == "05:00:00" ? 'selected' : ''; ?>>7:00 PM - 5:00 AM</option>
                <option value="7:00 PM - 5:00 PM" <?= $booking['check_in'] == "19:00:00" && $booking['check_out'] == "17:00:00" ? 'selected' : ''; ?>>7:00 PM - 5:00 PM</option>
                <option value="7:00 AM - 5:00 AM" <?= $booking['check_in'] == "07:00:00" && $booking['check_out'] == "05:00:00" ? 'selected' : ''; ?>>7:00 AM - 5:00 AM</option>
            </select>

            <label for="check_out">Check-out Date:</label>
            <input type="text" id="check_out" name="check_out" readonly disabled value="<?= htmlspecialchars(date("Y-m-d", strtotime($booking['check_out']))); ?>">
            <input type="hidden" id="hidden_check_out" name="check_out">
            <input type="hidden" id="check_in_time" name="checkInTime" value="<?= htmlspecialchars($booking['check_in']); ?>">
            <input type="hidden" id="check_out_time" name="checkOutTime" value="<?= htmlspecialchars($booking['check_out']); ?>">

            <button type="submit">Update Booking</button>
        </form>
    </div>
</div>

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

            // Get the original price from the hidden input
            const originalPrice = parseFloat($("#original-price").val());

            let newPrice = originalPrice;

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
                    newPrice = originalPrice * 2; // Double the price for this time slot
                    break;

                case "7:00 AM - 5:00 AM":
                    checkInTime = "07:00:00";
                    checkOutTime = "05:00:00";
                    checkOutDateTime.setDate(checkOutDateTime.getDate() + 1);
                    newPrice = originalPrice * 2; // Double the price for this time slot
                    break;
            }

            // Update the check-out date and time
            let checkOutFormatted = $.datepicker.formatDate("yy-mm-dd", checkOutDateTime);
            $("#check_out").val(checkOutFormatted);
            $("#hidden_check_out").val(checkOutFormatted);
            $("#check_in_time").val(checkInTime);
            $("#check_out_time").val(checkOutTime);

            // Update the displayed price
            $("#dynamic-price").text(`$${newPrice.toFixed(2)}`);
        }

        // Initialize the price and check-out date on page load
        if ($("#check_in").val()) {
            updateCheckoutDate($("#check_in").val());
        }
    });
</script>

</body>
</html>