<?php
include('../../resources/database/config.php');
include('../bootstrap.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$account_id = $_SESSION['ID'];
$book_id = $_GET['id'] ?? 0;

if (!is_numeric($book_id) || $book_id <= 0) {
    die("Invalid booking ID.");
}

$sql = "SELECT b.book_id, b.room_id, b.check_in, b.check_out, b.price, 
               r.room_code, r.price as room_price
        FROM booking b
        JOIN room r ON b.room_id = r.room_id
        WHERE b.book_id = ? AND b.account_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $book_id, $account_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: Booking not found or you are not authorized.");
}

$booking = $result->fetch_assoc();

$rooms = [];
$result = mysqli_query($conn, "SELECT room_id, room_code, price FROM room WHERE room_status = 'available' OR room_id = " . $booking['room_id']);
while ($row = mysqli_fetch_assoc($result)) {
    $rooms[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Booking</title>
</head>
<body>
<div class="container mt-5">
    <h2>Edit Booking</h2>
    <form action="update.php" method="POST">
        <input type="hidden" name="book_id" value="<?php echo $booking['book_id']; ?>">

        <div class="mb-3">
            <label class="form-label">Select Room</label>
            <select name="room_id" id="room_id" class="form-select" required>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?php echo $room['room_id']; ?>" data-price="<?php echo $room['price']; ?>"
                        <?php if ($room['room_id'] == $booking['room_id']) echo "selected"; ?>>
                        Room <?php echo $room['room_code']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (USD)</label>
            <input type="text" id="price_display" class="form-control" value="<?php echo $booking['price']; ?>" readonly>
            <input type="hidden" name="price" id="price" value="<?php echo $booking['price']; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Check-in Date</label>
            <input type="date" name="check_in" id="check_in" class="form-control"
                   value="<?php echo $booking['check_in']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Check-out Date (Automatically set to tomorrow)</label>
            <input type="date" name="check_out" id="check_out" class="form-control"
                   value="<?php echo $booking['check_out']; ?>" readonly required>
        </div>

        <button type="submit" class="btn btn-success">Update Booking</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
    document.getElementById("room_id").addEventListener("change", function () {
        var selectedOption = this.options[this.selectedIndex];
        var price = selectedOption.getAttribute("data-price") || 0;
        document.getElementById("price_display").value = price;
        document.getElementById("price").value = price;
    });

    document.getElementById("check_in").addEventListener("change", function () {
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
</script>

</body>
</html>
