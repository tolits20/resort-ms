<?php
include ("../../resources/database/config.php");

if (isset($_SESSION['ID'])) {
    $account_id = $_SESSION['ID'];

    // Single query to get all user data including profile image
    $sql1 = "SELECT u.*, a.username, a.password, u.profile_img 
             FROM user u 
             JOIN account a ON u.account_id = a.account_id 
             WHERE u.account_id = ?";
    $stmt = $conn->prepare($sql1);
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc(); // Changed from $row to $user_data

    if (!$user_data) {
        echo "User data not found.";
        exit;
    }

    $_SESSION['update_id'] = $account_id;
} else {
    echo "User not logged in.";
    exit;
}
// Fetch rooms with one image per room
$sql = "SELECT r.room_id, r.room_code, r.room_type, r.price, 
        (SELECT g.room_img FROM room_gallery g WHERE g.room_id = r.room_id LIMIT 1) as room_img 
        FROM room r";
$rooms_result = $conn->query($sql); // Changed from $result to $rooms_result
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paradise Resort | Available Rooms</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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

/* Updated Page Header Styles */
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

/* Remove all .current-info related styles as they're no longer needed */    /* Rooms Grid */
/* Updated Rooms Grid */
.rooms-grid {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* Updated Room Card Styles */
.room-card {
    background: var(--white);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    display: grid;
    grid-template-columns: 65% 35%; /* Image takes 65%, details take 35% */
    height: 400px; /* Increased height */
}

.room-card:hover {
    transform: translateY(-5px);
}

.room-image {
    position: relative;
    height: 100%; /* Make image fill full height */
    overflow: hidden;
    cursor: pointer;
}

.room-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.room-image:hover img {
    transform: scale(1.1);
}

.room-details {
    padding: 2.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 100%;
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

.book-btn {
    display: inline-block;
    background-color: var(--success);
    color: var(--white);
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.3s ease;
    width: 100%;
    text-align: center;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.book-btn:hover {
    background-color: #219653;
    transform: translateY(-2px);
}


/* Responsive adjustments */
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
    
    <?php
    $row = $user_data;
    include("../view/navbar.php")?>

<section class="page-header">
    <h1>Our Luxurious Rooms</h1>
    <div class="header-content">
        <p>Welcome to Paradise Resort's exceptional accommodations, where luxury meets comfort. 
           Our carefully curated selection of rooms offers the perfect blend of elegance and modern amenities. 
           Each room is designed to provide you with an unforgettable stay, featuring premium furnishings, 
           stunning views, and world-class service. Whether you're here for business or leisure, 
           discover your perfect sanctuary in our resort.</p>
    </div>
</section>

<!-- Rooms Grid -->
<section class="rooms-grid">
    <?php while ($room = $rooms_result->fetch_assoc()) { ?>
        <div class="room-card">
            <a href="../booking/create.php?room_id=<?php echo $room['room_id']; ?>" class="room-image">
                <img src="../../resources/assets/room_images/<?php echo $room['room_img']; ?>" 
                     alt="<?php echo htmlspecialchars($room['room_code']); ?>">
            </a>
            <div class="room-details">
                <div class="room-type"><?php echo htmlspecialchars($room['room_type']); ?></div>
                <h3 class="room-name"><?php echo htmlspecialchars($room['room_code']); ?></h3>
                <div class="room-price">₱<?php echo number_format($room['price'], 2); ?> <span style="font-size: 0.8rem; font-weight: normal;"></span></div>
                <a href="../booking/create.php?room_id=<?php echo $room['room_id']; ?>" class="book-btn">Book Room</a>
            </div>
        </div>
    <?php } ?>
</section>

<?php include("../view/footer.php")?>
    <!-- Footer section remains the same -->

</body>
</html>
<?php $conn->close(); ?>