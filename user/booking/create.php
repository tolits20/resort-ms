<?php
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$rooms = [];
$result = mysqli_query($conn, "SELECT 
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
WHERE room.room_status = 'available' || room.room_status = 'pending'");

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row;
    }
} else {
    die("Error fetching rooms: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Book a Room</title>
  <?php include '../bootstrap.php'; ?>
</head>
<body>
  <div class="container">
    <div class="booking-form">
      <h2>Book a Room</h2>
      <form action="store.php" method="POST">
        <div class="mb-3">
          <label for="room_id" class="form-label">Select Available Room</label>
          <select name="room_id" id="room_id" class="form-select" required>
            <option value="">Choose a room</option>
            <?php foreach ($rooms as $room): ?>
              <option value="<?php echo $room['room_id']; ?>" 
        data-price="<?php echo $room['price']; ?>"
        data-discount="<?php echo $room['discount_percentage'] ?? 0; ?>"
        data-discounted-price="<?php echo $room['discounted_price'] ?? $room['price']; ?>">
    Room <?php echo $room['room_code']; ?> - <?php echo ucfirst($room['room_type']); ?>
    <?php if (!empty($room['discount_percentage']) && $room['discount_percentage'] > 0): ?>
        (<?php echo $room['discount_percentage']; ?>% Off - 
        <?php echo number_format($room['price'], 2); ?> → 
        <?php echo number_format($room['discounted_price'], 2); ?>)
    <?php else: ?>
        (<?php echo number_format($room['price'], 2); ?>)
    <?php endif; ?>
</option>

            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="price_display" class="form-label">Price</label>
          <input type="text" id="price_display" class="form-control" readonly>
          <input type="hidden" name="price" id="price">
        </div>
        <div class="mb-3">
          <label for="check_in" class="form-label">Check-in Date</label>
          <input type="date" name="check_in" id="check_in" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="time_slot" class="form-label">Select Time Slot</label>
          <select name="time_slot" id="time_slot" class="form-select" required>
            <option value="">Choose a time slot</option>
            <option value="7:00 AM - 5:00 PM">7:00 AM - 5:00 PM (10 hrs)</option>
            <option value="7:00 PM - 5:00 AM">7:00 PM - 5:00 AM (10 hrs)</option>
            <option value="7:00 PM - 5:00 PM">7:00 PM - 5:00 PM (22 hrs)</option>
            <option value="7:00 AM - 5:00 AM">7:00 AM - 5:00 AM (22 hrs)</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="check_out" class="form-label">Check-out Date</label>
          <input type="text" id="check_out" class="form-control" readonly>
        </div>
        
        <input type="hidden" name="check_out" id="check_out_hidden">
        <input type="hidden" name="check_in_time" id="check_in_time">
        <input type="hidden" name="check_out_time" id="check_out_time">
        
        <div class="d-grid">
          <button type="submit" name="submit" class="btn btn-primary">Book Now</button>
        </div>
      </form>
    </div>
  </div>

  <script>
  document.getElementById("room_id").addEventListener("change", function () {
      var selectedOption = this.options[this.selectedIndex];
      var discountedPrice = selectedOption.getAttribute("data-discounted-price") || 0;
      document.getElementById("price_display").value = discountedPrice;
      document.getElementById("price").value = discountedPrice;
  });

  document.getElementById("time_slot").addEventListener("change", updateCheckOut);
  document.getElementById("check_in").addEventListener("change", updateCheckOut);

  function updateCheckOut() {
      var checkInDate = document.getElementById("check_in").value;
      var timeSlot = document.getElementById("time_slot").value;
      var checkOutField = document.getElementById("check_out");
      var checkOutHidden = document.getElementById("check_out_hidden");
      var checkInTimeField = document.getElementById("check_in_time");
      var checkOutTimeField = document.getElementById("check_out_time");

      if (checkInDate && timeSlot) {
          var checkInDateTime = new Date(checkInDate);
          var checkInTime, checkOutTime;

          switch (timeSlot) {
              case "7:00 AM - 5:00 PM":
                  checkInTime = "07:00:00";
                  checkOutTime = "17:00:00";
                  break;
              case "7:00 PM - 5:00 AM":
                  checkInTime = "19:00:00";
                  checkInDateTime.setDate(checkInDateTime.getDate() + 1);
                  checkOutTime = "05:00:00";
                  break;
              case "7:00 PM - 5:00 PM":
                  checkInTime = "19:00:00";
                  checkInDateTime.setDate(checkInDateTime.getDate() + 1);
                  checkOutTime = "17:00:00";
                  break;
              case "7:00 AM - 5:00 AM":
                  checkInTime = "07:00:00";
                  checkInDateTime.setDate(checkInDateTime.getDate() + 1);
                  checkOutTime = "05:00:00";
                  break;
              default:
                  return;
          }

          checkOutField.value = checkInDateTime.toISOString().split("T")[0];
          checkOutHidden.value = checkOutField.value;
          checkInTimeField.value = checkInTime;
          checkOutTimeField.value = checkOutTime;
      }
  }
  </script>
</body>
</html>
