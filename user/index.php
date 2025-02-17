<?php
include ("../resources/database/config.php");

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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f8f9fa;
            text-align: center;
        }
        header {
            background: #34495e;
            color: white;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .auth-buttons {
            display: flex;
            gap: 10px;
        }
        .auth-buttons a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
            transition: 0.3s;
        }
        .login-btn {
            background: #2980b9;
        }
        .login-btn:hover {
            background: #1f6691;
        }
        .register-btn {
            background: #27ae60;
        }
        .register-btn:hover {
            background: #1e8449;
        }
        .room-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 30px;
            max-width: 1200px;
            margin: auto;
        }
        .room-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .room-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .room-card .content {
            padding: 15px;
        }
        .room-card h2 {
            font-size: 18px;
            margin-bottom: 8px;
            color: #2c3e50;
        }
        .room-card p {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }
        .price {
            font-weight: bold;
            color: #e74c3c;
            font-size: 16px;
        }
        .book-btn {
            display: block;
            text-align: center;
            background: #27ae60;
            color: white;
            font-size: 14px;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }
        .book-btn:hover {
            background: #1e8449;
        }
    </style>
</head>
<body>
    <header>
        <div>Explore Our Luxurious Rooms</div>
        <div class="auth-buttons">
            <a href="../login.php" class="login-btn">Login</a>
            <a href="../admin/customer/create.php" class="register-btn">Register</a>
        </div>
    </header>

    <main class="room-container">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="room-card">
                <img src="../resources/assets/images/<?php echo $row['image']; ?>" alt="Room Image">
                <div class="content">
                    <h2><?php echo $row['room_name']; ?></h2>
                    <p><?php echo $row['description']; ?></p>
                    <p class="price">$<?php echo $row['price']; ?> per night</p>
                    <a href="book.php?room_id=<?php echo $row['room_id']; ?>" class="book-btn">Book Now</a>
                </div>
            </div>
        <?php } ?>
    </main>
</body>
</html>
<?php $conn->close(); ?>
