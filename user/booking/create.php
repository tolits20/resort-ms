<?php
require '../../resources/database/config.php';
include("../../admin/includes/system_update.php");


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
$feedbackQuery = "SELECT a.username, f.rating, f.comment, f.created_at, b.book_id, b.account_id 
FROM feedback f 
INNER JOIN booking b ON f.book_id = b.book_id
INNER JOIN account a ON b.account_id = a.account_id 
WHERE b.room_id = ? 
ORDER BY f.created_at DESC";

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

    /* Room Info Bar */
    .room-info-bar {
        background: var(--white);
        padding: 1rem 2rem;
        margin: 80px auto 0;
        max-width: 1200px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .room-title {
        font-size: 1.5rem;
        color: var(--primary);
        font-weight: 600;
    }

    /* .user-info {
        text-align: right;
    }

    .user-info .datetime {
        font-size: 0.9rem;
        color: var(--secondary);
        margin-bottom: 0.3rem;
    }

    .user-info .username {
        color: var(--primary);
        font-weight: 500;
    } */

    /* Container Updates */
    .container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background: var(--white);
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 2rem;
    }

    /* Gallery Updates */
    .room-gallery {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .room-gallery img:first-child {
        grid-column: 1 / -1;
        height: 400px;
        object-fit: cover;
    }

    .room-gallery img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    .room-gallery img:hover {
        transform: scale(1.02);
        cursor: pointer;
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
    }

    /* Form Elements */
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
    }

    button:hover {
        background: #219653;
        transform: translateY(-2px);
    }

    /* Feedback Section Updates */
    .feedback-section {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background: var(--white);
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .feedback-item {
        background: var(--light);
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-radius: 10px;
        border-left: 4px solid var(--accent);
    }

    @media (max-width: 968px) {
        .container {
            grid-template-columns: 1fr;
        }
        
        .room-gallery {
            grid-template-columns: 1fr;
        }

        .room-gallery img:first-child {
            height: 300px;
        }
    }
</style>
</head>
<body>

<!-- Back Button -->
<a href="javascript:history.back()" class="back-button">
    <i class="fas fa-arrow-left"></i> Back
</a>
<?php include("../view/navbar.php")?>
<!-- Add this right after the body tag and navbar include -->

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
                    <span class="discounted">₱<?= number_format($room['price'], 2); ?></span>
                    <strong  id="dynamic-price">₱<?= number_format($room['discounted_price'], 2); ?></strong>
                    (<?= htmlspecialchars($room['discount_percentage']); ?>% off)
                <?php else: ?>
                    <strong id="dynamic-price">₱<?= number_format($room['price'], 2); ?></strong>
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
<?php include("../view/footer.php")?>
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


            $("#dynamic-price").text(`₱${newPrice.toFixed(2)}`);
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