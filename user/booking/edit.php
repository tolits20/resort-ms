<?php
require '../../resources/database/config.php';
include("../../admin/includes/system_update.php");

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
    <title>Paradise Resort | Edit Booking</title>
    
    <!-- Include jQuery & jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #e67e22;
            --light: #f8f9fa;
            --dark: #2c3e50;
            --success: #27ae60;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light);
        }

        .main-container {
            max-width: 1200px;
            margin: 100px auto 2rem;
            padding: 2rem;
        }

        /* Room Info Bar */
        .room-info-bar {
            background: var(--white);
            padding: 1.5rem 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            text-align: center;
        }

        .room-title {
            font-size: 1.8rem;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        /* Container Updates */
        .container {
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Booking Form Updates */
        .booking-form {
            background: var(--light);
            padding: 2rem;
            border-radius: 10px;
        }

        .price-box {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .price-box p {
            font-size: 1.4rem;
            color: var(--primary);
            margin: 0;
        }

        .discounted {
            color: #e74c3c;
            text-decoration: line-through;
            margin-right: 0.5rem;
            font-size: 1.2rem;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            color: var(--primary);
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            background: var(--white);
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--accent);
        }

        input[readonly] {
            background-color: var(--light);
            cursor: pointer;
        }

        button {
            background: var(--success);
            color: var(--white);
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        button:hover {
            background: #219653;
            transform: translateY(-2px);
        }

        button i {
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .container {
                padding: 1rem;
            }

            .booking-form {
                padding: 1rem;
            }

            .room-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include("../view/navbar.php"); ?>

    <div class="main-container">
        <!-- Info Bar -->
        <div class="room-info-bar">
            <div class="room-title">
                Edit Booking for Room <?= htmlspecialchars($booking['room_code']); ?>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container">
            <div class="booking-form">
                <div class="price-box">
                    <p>Price: 
                        <?php if ($booking['discount_percentage'] > 0): ?>
                            <span class="discounted">₱<?= number_format($booking['price'], 2); ?></span>
                            <strong id="dynamic-price">₱<?= number_format($booking['discounted_price'], 2); ?></strong>
                            (<?= htmlspecialchars($booking['discount_percentage']); ?>% off)
                        <?php else: ?>
                            <strong id="dynamic-price">₱<?= number_format($booking['price'], 2); ?></strong>
                        <?php endif; ?>
                    </p>
                </div>

                <form method="POST" action="update.php">
                    <input type="hidden" name="booking_id" value="<?= $booking_id; ?>">
                    <input type="hidden" id="original-price" value="<?= $booking['discount_percentage'] > 0 ? $booking['discounted_price'] : $booking['price']; ?>">

                    <div class="form-group">
                        <label for="room_id">Room:</label>
                        <select name="room_id" id="room_id" required>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?= $room['room_id']; ?>" 
                                        <?= $room['room_id'] == $booking['room_id'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($room['room_code']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="check_in">Check-in Date:</label>
                        <input type="text" id="check_in" name="check_in" readonly required 
                               value="<?= htmlspecialchars(date("Y-m-d", strtotime($booking['check_in']))); ?>">
                    </div>

                    <div class="form-group">
                        <label for="time_slot">Check-in Time Slot:</label>
                        <select name="time_slot" id="time_slot" required>
                            <option value="7:00 AM - 5:00 PM">7:00 AM - 5:00 PM</option>
                            <option value="7:00 PM - 5:00 AM">7:00 PM - 5:00 AM</option>
                            <option value="7:00 PM - 5:00 PM">7:00 PM - 5:00 PM</option>
                            <option value="7:00 AM - 5:00 AM">7:00 AM - 5:00 AM</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="check_out">Check-out Date:</label>
                        <input type="text" id="check_out" name="check_out" readonly disabled 
                               value="<?= htmlspecialchars(date("Y-m-d", strtotime($booking['check_out']))); ?>">
                    </div>

                    <input type="hidden" id="hidden_check_out" name="check_out">
                    <input type="hidden" id="check_in_time" name="checkInTime" value="<?= htmlspecialchars($booking['check_in']); ?>">
                    <input type="hidden" id="check_out_time" name="checkOutTime" value="<?= htmlspecialchars($booking['check_out']); ?>">

                    <button type="submit">
                        <i class="fas fa-save"></i> Update Booking
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php include("../view/footer.php"); ?>

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
            $("#dynamic-price").text(`₱${newPrice.toFixed(2)}`);
        }

        // Initialize the price and check-out date on page load
        if ($("#check_in").val()) {
            updateCheckoutDate($("#check_in").val());
        }
    });
</script>

</body>
</html>