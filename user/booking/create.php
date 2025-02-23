<?php
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

// Fetch available rooms from the room table, including price.
$rooms = [];
$result = mysqli_query($conn, "SELECT room_id, room_code, type, price FROM room WHERE status = 'available'");
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
  <style>
    body {
      background: #f8f9fa;
    }
    .booking-form {
      max-width: 600px;
      margin: 50px auto;
      background: #ffffff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .booking-form h2 {
      margin-bottom: 20px;
      font-weight: 700;
      text-align: center;
    }
  </style>
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
                <option value="<?php echo $room['room_id']; ?>" data-price="<?php echo $room['price']; ?>">
                  Room <?php echo $room['room_code']; ?> - <?php echo ucfirst($room['type']); ?>
                </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="check_in" class="form-label">Check-in Date</label>
          <input type="date" name="check_in" id="check_in" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="check_out" class="form-label">Check-out Date</label>
          <input type="date" name="check_out" id="check_out" class="form-control" required>
        </div>
        <!-- Price field auto-fills based on the selected room -->
        <div class="mb-3">
          <label for="price_display" class="form-label">Price (USD)</label>
          <input type="text" id="price_display" class="form-control" readonly>
          <input type="hidden" name="price" id="price">
        </div>
        <div class="d-grid">
          <button type="submit" name="submit" class="btn btn-primary">Book Now</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // When the room selection changes, update the price fields
    document.getElementById("room_id").addEventListener("change", function() {
      let selectedOption = this.options[this.selectedIndex];
      let price = selectedOption.getAttribute("data-price") || 0;
      document.getElementById("price_display").value = price;
      document.getElementById("price").value = price;
    });
  </script>
</body>
</html>
