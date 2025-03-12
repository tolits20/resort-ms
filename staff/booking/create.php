<?php
// Include database connection
include_once '../../config/database.php';

// Initialize variables
$guest_name = $room_number = $check_in_date = $check_out_date = "";
$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    if (empty($_POST["guest_name"])) {
        $errors[] = "Guest name is required.";
    } else {
        $guest_name = htmlspecialchars($_POST["guest_name"]);
    }

    if (empty($_POST["room_number"])) {
        $errors[] = "Room number is required.";
    } else {
        $room_number = htmlspecialchars($_POST["room_number"]);
    }

    if (empty($_POST["check_in_date"])) {
        $errors[] = "Check-in date is required.";
    } else {
        $check_in_date = htmlspecialchars($_POST["check_in_date"]);
    }

    if (empty($_POST["check_out_date"])) {
        $errors[] = "Check-out date is required.";
    } else {
        $check_out_date = htmlspecialchars($_POST["check_out_date"]);
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $query = "INSERT INTO bookings (guest_name, room_number, check_in_date, check_out_date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $guest_name, $room_number, $check_in_date, $check_out_date);

        if ($stmt->execute()) {
            echo "Booking successfully created.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Booking</title>
</head>
<body>
    <h2>Create Booking</h2>
    <?php
    if (!empty($errors)) {
        echo '<ul>';
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
    }
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="guest_name">Guest Name:</label>
        <input type="text" id="guest_name" name="guest_name" value="<?php echo $guest_name; ?>"><br><br>
        
        <label for="room_number">Room Number:</label>
        <input type="text" id="room_number" name</body>