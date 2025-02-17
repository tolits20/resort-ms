<?php
include ('../../resources/database/config.php');
// Check if user is logged in
if (!isset($_SESSION['ID'])) {
    header("Location: ../../login.php");
    exit();
}


// Fetch available rooms for selection
$sql = "SELECT * FROM room WHERE status = 'available'";
$result = $conn->query($sql);
?>

<h1>Book a Room</h1>
<form method="POST" action="store.php">
    <label for="room_id">Select Room:</label>
    <select name="room_id" required>
        <option value="">Select a room</option>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <option value="<?php echo $row['room_id']; ?>"><?php echo $row['room_code'] . ' - ' . $row['type']; ?></option>
        <?php } ?>
    </select><br>

    <label for="check_in">Check-in Date:</label>
    <input type="date" name="check_in" required><br>

    <label for="check_in_time">Check-in Time:</label>
    <select name="check_in_time" required>
        <option value="07:00:00 - 17:00:00">07:00 AM - 5:00 PM</option>
        <option value="19:00:00 - 05:00:00">07:00 PM - 5:00 AM</option>
    </select><br>

    <label for="check_out">Check-out Date:</label>
    <input type="date" name="check_out" required><br>

    <input type="submit" value="Book Now">
</form>
