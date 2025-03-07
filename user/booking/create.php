<?php
require '../../resources/database/config.php';

// Ensure a room is selected


if (!isset($_GET['room_id']) || empty($_GET['room_id'])) {
    die("Room not selected.");
}

$room_id = intval($_GET['room_id']);

$discounted_price = 0;
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
$bookingQuery = "SELECT check_in FROM booking WHERE book_status = 'confirmed' && room_id = ?";
$stmt = $conn->prepare($bookingQuery);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$bookingResult = $stmt->get_result();

while ($row = $bookingResult->fetch_assoc()) {
    $bookedDates[] = date("Y-m-d", strtotime($row['check_in']));
}

// Fetch room images
$imageQuery = "SELECT room_img FROM room_gallery WHERE room_id = ?";
$stmt = $conn->prepare($imageQuery);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$imageResult = $stmt->get_result();
$room_images = $imageResult->fetch_all(MYSQLI_ASSOC);

// Fetch feedback for the room
$feedbackQuery = "SELECT a.username, f.rating, f.created_at, b.book_id
FROM feedback f 
INNER JOIN account a ON f.account_id = a.account_id
INNER JOIN booking b ON f.book_id = b.book_id
WHERE b.room_id = ? 
ORDER BY f.created_at DESC;
";
$stmt = $conn->prepare($feedbackQuery);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$feedbackResult = $stmt->get_result();
$feedbacks = $feedbackResult->fetch_all(MYSQLI_ASSOC);
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
            display: flex;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .room-gallery {
            flex: 1;
            margin-right: 20px;
        }
        .room-gallery img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .booking-form {
            flex: 1;
            padding: 20px;
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
        .feedback-section {
            margin-top: 40px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .feedback-item {
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-radius: 5px;
            box-shadow: 0px 2px 4px rgba(0,0,0,0.1);
        }
        .feedback-item strong {
            color: #333;
        }
    </style>
</head>
<body>

<!-- Back Button -->
<a href="javascript:history.back()" class="back-button">
    <i class="fas fa-arrow-left"></i> Back
</a>

<div class="container">
    <!-- Room Images -->
    <div class="room-gallery">
        <?php if (!empty($room_images)): ?>
            <?php foreach ($room_images as $image): ?>
                <img src="../../resources/assets/room_images/<?php echo $image['room_img']; ?>" alt="Room Image">
            <?php endforeach; ?>
        <?php else: ?>
            <p>No images available for this room.</p>
        <?php endif; ?>
    </div>

    <!-- Booking Form -->
    <div class="booking-form">
        <h2>Book <?= htmlspecialchars($room['room_code']); ?></h2>

        <div class="price-box">
            <p>Price: 
                <?php if ($room['discount_percentage'] > 0): ?>
                    <span class="discounted">$<?= number_format($room['price'], 2); ?></span>
                    <strong  id="dynamic-price">$<?= number_format($room['discounted_price'], 2); ?></strong>
                    (<?= htmlspecialchars($room['discount_percentage']); ?>% off)
                <?php else: ?>
                    <strong id="dynamic-price">$<?= number_format($room['price'], 2); ?></strong>
                <?php endif; ?>
            </p>
        </div>
        <input type="hidden" id="original-price" value="<?= $room['discount_percentage'] > 0 ? $room['discounted_price'] : $room['price']; ?>">
        <form method="POST" action="store.php?price=<?php echo $discounted_price; ?>">
        <input type="hidden" id="updated-price" name="updated_price" value="<?= $room['discount_percentage'] > 0 ? $room['discounted_price'] : $room['price']; ?>">
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
    </div>
</div>

<!-- Feedback Section -->
<div class="feedback-section">
    <h3>Customer Feedback</h3>
    <?php if (!empty($feedbacks)): ?>
        <?php foreach ($feedbacks as $feedback): ?>
            <div class="feedback-item">
                <strong><?= htmlspecialchars($feedback['username']); ?></strong>
                <hr>
                <small>Rating: <?= htmlspecialchars($feedback['rating']); ?>/5</small>
                <p><?= htmlspecialchars($feedback['comment']); ?></p>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No feedback available for this room.</p>
    <?php endif; ?>
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
            console.log("updateCheckoutDate called"); // Debugging line

            let timeSlot = $("#time_slot").val();
            console.log("Selected Time Slot:", timeSlot); // Debugging line

            if (!timeSlot) return;

            let checkInDateTime = new Date(checkInDate);
            let checkOutDateTime = new Date(checkInDate);
            let checkInTime, checkOutTime;

            // Get the original price from the hidden input
            const originalPrice = parseFloat($("#original-price").val());
            console.log("Original Price:", originalPrice); // Debugging line

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
                    newPrice = originalPrice * 2;
                    // Update the discounted price in PHP
                    // Double the price for this time slot
                    break;
            }

            // console.log("New Price:", newPrice); // Debugging line

            // Update the check-out date and time
            let checkOutFormatted = $.datepicker.formatDate("yy-mm-dd", checkOutDateTime);
            $("#check_out").val(checkOutFormatted);
            $("#hidden_check_out").val(checkOutFormatted);
            $("#check_in_time").val(checkInTime);
            $("#check_out_time").val(checkOutTime);


            $("#dynamic-price").text(`$${newPrice.toFixed(2)}`);
            $("#updated-price").val(newPrice);
            console.log("Updated #dynamic-price element:", $("#dynamic-price").text()); // Debugging line
        }

        // Initialize the price and check-out date on page load
        if ($("#check_in").val()) {
            updateCheckoutDate($("#check_in").val());
        }
    });
</script>

</body>
</html>