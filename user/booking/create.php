<?php
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$rooms = [];
$result = mysqli_query($conn, "SELECT room_id, room_code, room_type, price FROM room WHERE room_status = 'available'");
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
                  Room <?php echo $room['room_code']; ?> - <?php echo ucfirst($room['room_type']); ?>
                </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="price_display" class="form-label">Price (USD)</label>

          <input type="text" id="price_display" class="form-control" readonly>
          <input type="hidden" name="price" id="price">
        </div>
        <div class="mb-3">
          <label for="check_in" class="form-label">Check-in Date</label>
          <input type="date" name="check_in" id="check_in" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="check_out" class="form-label">Check-out Date (Automatically set to tomorrow)</label>
          <input type="date" name="check_out" id="check_out" class="form-control" readonly required>
        </div>
        <div class="d-grid">
          <button type="submit" name="submit" class="btn btn-primary">Book Now</button>
        </div>
      </form>
      <small class="text-muted">Booking is limited to one day only.</small>
    </div>
  </div>

  <script>
    document.getElementById("check_in").addEventListener("change", function() {
      var checkInDate = new Date(this.value);
      if (!isNaN(checkInDate)) {
        checkInDate.setDate(checkInDate.getDate() + 1);
        var year = checkInDate.getFullYear();
        var month = ("0" + (checkInDate.getMonth() + 1)).slice(-2);
        var day = ("0" + checkInDate.getDate()).slice(-2);
        var tomorrow = year + '-' + month + '-' + day;
        document.getElementById("check_out").value = tomorrow;
      } else {
        document.getElementById("check_out").value = '';
      }
    });

    document.getElementById("room_id").addEventListener("change", function() {
      var selectedOption = this.options[this.selectedIndex];
      var price = selectedOption.getAttribute("data-price") || 0;
      document.getElementById("price_display").value = price;
      document.getElementById("price").value = price;
    });
  </script>
</body>
</html>
