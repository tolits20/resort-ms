<?php
include ("../../resources/database/config.php");

// Fetch rooms with one image per room
$sql = "SELECT r.room_id, r.room_code, r.room_type, r.price, 
        (SELECT g.room_img FROM room_gallery g WHERE g.room_id = r.room_id LIMIT 1) as room_img 
        FROM room r";
$rooms_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paradise Resort | Our Rooms</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Keep your existing root and general styles */
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

        /* Your existing page header styles */
        .page-header {
            margin-top: 80px;
            padding: 4rem 2rem;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('../../resources/assets/resort_images/header_room.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: var(--white);
            text-align: center;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Keep your existing header content styles */
        .page-header h1 {
            font-size: 2.8rem;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header-content {
            background: rgba(0, 0, 0, 0.7);
            padding: 2rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .header-content p {
            color: var(--white);
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: center;
            margin: 0;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        /* Updated Room Card Styles for Guest View */
        .rooms-grid {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .room-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: 65% 35%;
            height: 400px;
        }

        .room-image {
            position: relative;
            height: 100%;
            overflow: hidden;
        }

        .room-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .room-details {
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .room-type {
            color: var(--accent);
            font-size: 1rem;
            text-transform: uppercase;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }

        .room-name {
            font-size: 1.6rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .room-price {
            font-size: 1.4rem;
            color: var(--primary);
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .login-prompt {
            background: var(--light);
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            color: var(--secondary);
        }

        .login-btn {
            display: inline-block;
            background-color: var(--accent);
            color: var(--white);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 1rem;
            font-weight: 500;
        }

        .login-btn:hover {
            background-color: #d35400;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .room-card {
                grid-template-columns: 1fr;
            }
            
            .room-image {
                height: 250px;
            }
            
            .room-details {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include("guest_navbar.php")?>

    <section class="page-header">
        <h1>Our Luxurious Rooms</h1>
        <div class="header-content">
            <p>Welcome to Paradise Resort's exceptional accommodations, where luxury meets comfort. 
               Our carefully curated selection of rooms offers the perfect blend of elegance and modern amenities. 
               Each room is designed to provide you with an unforgettable stay, featuring premium furnishings, 
               stunning views, and world-class service.</p>
        </div>
    </section>

    <section class="rooms-grid">
        <?php while ($room = $rooms_result->fetch_assoc()) { ?>
            <div class="room-card">
                <div class="room-image">
                    <img src="../../resources/assets/room_images/<?php echo $room['room_img']; ?>" 
                         alt="<?php echo htmlspecialchars($room['room_code']); ?>">
                </div>
                <div class="room-details">
                    <div class="room-type"><?php echo htmlspecialchars($room['room_type']); ?></div>
                    <h3 class="room-name"><?php echo htmlspecialchars($room['room_code']); ?></h3>
                    <div class="room-price">₱<?php echo number_format($room['price'], 2); ?> <span style="font-size: 0.8rem; font-weight: normal;">per night</span></div>
                    <div class="login-prompt">
                        <p>Want to book this room?</p>
                        <a href="../../login.php" class="login-btn">Login to Book</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>

    <?php include("../view/footer.php")?>
</body>
</html>
<?php $conn->close(); ?>