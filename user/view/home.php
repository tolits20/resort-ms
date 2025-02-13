

<?php
include ("../../resources/database/config.php");

// Fetch available rooms from the database
$sql = "SELECT * FROM room WHERE status = 'available'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms | Resort</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            font-size: 24px;
        }
        .room-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }
        .room-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: 15px;
            padding: 15px;
            width: 300px;
            text-align: left;
        }
        .room-card img {
            width: 100%;
            height: 200px;
            border-radius: 10px;
        }
        .room-card h2 {
            margin: 10px 0;
            font-size: 20px;
            color: #333;
        }
        .room-card p {
            font-size: 14px;
            color: #666;
        }
        .price {
            font-weight: bold;
            color: #e74c3c;
        }
        .book-btn {
            display: block;
            text-align: center;
            background: #27ae60;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }
        .book-btn:hover {
            background: #219150;
        }
    </style>
</head>
<body>
    <header>
        <h1>Explore Our Rooms</h1>
    </header>
    <main class="room-container">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="room-card">
                <img src="uploads/<?php echo $row['image']; ?>" alt="Room Image">
                <h2><?php echo $row['room_name']; ?></h2>
                <p><?php echo $row['description']; ?></p>
                <p class="price">$<?php echo $row['price']; ?> per night</p>
                <a href="book.php?room_id=<?php echo $row['room_id']; ?>" class="book-btn">Book Now</a>
            </div>
        <?php } ?>
    </main>
</body>
</html>
<?php $conn->close(); ?>
