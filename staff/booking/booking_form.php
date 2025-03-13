<?php
require '../../resources/database/config.php';
include("../../admin/includes/system_update.php");
include("../includes/template.php");

if (!isset($_GET['room_id']) || !isset($_GET['guest_id'])) {
    die("Room or Guest not selected.");
}

$room_id = intval($_GET['room_id']);
$guest_id = intval($_GET['guest_id']);

// Fetch guest details
$guestQuery = "SELECT * FROM guest WHERE guest_id = ?";
$stmt = $conn->prepare($guestQuery);
$stmt->bind_param("i", $guest_id);
$stmt->execute();
$guest = $stmt->get_result()->fetch_assoc();

if (!$guest) {
    die("Guest not found.");
}

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
$bookingQuery = "SELECT check_in FROM booking 
                WHERE book_status = 'confirmed' AND room_id = ?";
                

$stmt = $conn->prepare($bookingQuery);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$bookingResult = $stmt->get_result();

while ($row = $bookingResult->fetch_assoc()) {
    $bookedDates[] = date("Y-m-d", strtotime($row['check_in']));
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Book Room <?= htmlspecialchars($room['room_code']); ?></h4>
                    <a href="create.php" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Back to Rooms
                    </a>
                </div>
                <div class="card-body">
                    <!-- Guest Info Box -->
                    <div class="guest-info-box mb-4">
                        <h5>Guest Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> <?= htmlspecialchars($guest['fname'] . ' ' . $guest['lname']); ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($guest['email']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Phone:</strong> <?= htmlspecialchars($guest['contact']); ?></p>
                                <p><strong>Guest ID:</strong> <?= $guest['guest_id']; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="booking-form">
                        <div class="price-box">
                            <p>Price: 
                                <?php if ($room['discount_percentage'] > 0): ?>
                                    <span class="discounted">₱<?= number_format($room['price'], 2); ?></span>
                                    <strong id="dynamic-price">₱<?= number_format($room['discounted_price'], 2); ?></strong>
                                    (<?= htmlspecialchars($room['discount_percentage']); ?>% off)
                                <?php else: ?>
                                    <strong id="dynamic-price">₱<?= number_format($room['price'], 2); ?></strong>
                                <?php endif; ?>
                            </p>
                        </div>

                        <input type="hidden" id="original-price" value="<?= $room['discount_percentage'] > 0 ? $room['discounted_price'] : $room['price']; ?>">
                        <form method="POST" action="store.php">
                            <input type="hidden" id="updated-price" name="updated_price" value="<?= $room['discount_percentage'] > 0 ? $room['discounted_price'] : $room['price']; ?>">
                            <input type="hidden" name="room_id" value="<?= $room_id; ?>">
                            <input type="hidden" name="guest_id" value="<?= $guest_id; ?>">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="firstname">First Name:</label>
                                        <input type="text" id="firstname" name="firstname" class="form-control" 
                                               value="<?= htmlspecialchars($guest['fname']); ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="lastname">Last Name:</label>
                                        <input type="text" id="lastname" name="lastname" class="form-control" 
                                               value="<?= htmlspecialchars($guest['lname']); ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email:</label>
                                        <input type="email" id="email" name="email" class="form-control" 
                                               value="<?= htmlspecialchars($guest['email']); ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Phone Number:</label>
                                        <input type="tel" id="phone" name="phone" class="form-control" 
                                               value="<?= htmlspecialchars($guest['contact']); ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="check_in">Check-in Date:</label>
                                        <input type="text" id="check_in" name="check_in" class="form-control" readonly required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="time_slot">Check-in Time Slot:</label>
                                        <select name="time_slot" id="time_slot" class="form-control" required>
                                            <option value="7:00 AM - 5:00 PM">7:00 AM - 5:00 PM</option>
                                            <option value="7:00 PM - 5:00 AM">7:00 PM - 5:00 AM</option>
                                            <option value="7:00 PM - 5:00 PM">7:00 PM - 5:00 PM</option>
                                            <option value="7:00 AM - 5:00 AM">7:00 AM - 5:00 AM</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="check_out">Check-out Date:</label>
                                        <input type="text" id="check_out" name="check_out" class="form-control" readonly disabled>
                                        <input type="hidden" id="hidden_check_out" name="check_out">
                                        <input type="hidden" id="check_in_time" name="checkInTime">
                                        <input type="hidden" id="check_out_time" name="checkOutTime">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create Booking
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
                    newPrice = originalPrice * 2;
                    break;

                case "7:00 AM - 5:00 AM":
                    checkInTime = "07:00:00";
                    checkOutTime = "05:00:00";
                    checkOutDateTime.setDate(checkOutDateTime.getDate() + 1);
                    newPrice = originalPrice * 2;
                    break;
            }

            let checkOutFormatted = $.datepicker.formatDate("yy-mm-dd", checkOutDateTime);
            $("#check_out").val(checkOutFormatted);
            $("#hidden_check_out").val(checkOutFormatted);
            $("#check_in_time").val(checkInTime);
            $("#check_out_time").val(checkOutTime);

            $("#dynamic-price").text(`₱${newPrice.toFixed(2)}`);
            $("#updated-price").val(newPrice);
        }

        if ($("#check_in").val()) {
            updateCheckoutDate($("#check_in").val());
        }
    });
</script>

<style>
.price-box {
    background: var(--white);
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.price-box p {
    font-size: 1.4rem;
    color: #2c3e50;
    margin: 0;
}

.discounted {
    color: #e74c3c;
    text-decoration: line-through;
    margin-right: 0.5rem;
}

.form-control {
    border-radius: 8px;
}

.ui-datepicker {
    z-index: 1000 !important;
}

.guest-info-box {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    border: 1px solid #dee2e6;
}

.guest-info-box p {
    margin-bottom: 0.5rem;
}

input[readonly] {
    background-color: #f8f9fa;
    cursor: default;
}
</style>