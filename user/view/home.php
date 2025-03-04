<?php
include ("../../resources/database/config.php");

if (isset($_SESSION['ID'])) {
    $account_id = $_SESSION['ID'];

    $sql1 = "SELECT u.*, a.username, a.password FROM user u 
        JOIN account a ON u.account_id = a.account_id 
        WHERE u.account_id = ?";
    $stmt = $conn->prepare($sql1);
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "User data not found.";
        exit;
    }

    $_SESSION['update_id'] = $account_id;
} else {
    echo "User not logged in.";
    exit;
}

$sql = "SELECT r.room_id, r.room_code, r.room_type, r.price, g.room_img FROM room r INNER JOIN room_gallery g on r.room_id = g.room_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Rooms | Resort</title>
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: url('profile.jpg') center/cover;
            border: 2px solid white;
            display: block;
        }
        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        .hero {
            background: url('hero1.jpg') center/cover;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
        }
        .explore-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #27ae60;
            color: white;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            z-index: 1000;
        }
        .explore-button:hover {
            background: #1e8449;
        }
        .quick-access {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 30px;
            max-width: 1200px;
            margin: auto;
        }
        .quick-access .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: 0.3s;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .quick-access .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .quick-access .card i {
            font-size: 36px;
            margin-bottom: 10px;
            color: #34495e;
        }
        .quick-access .card h3 {
            font-size: 18px;
            margin-bottom: 8px;
            color: #2c3e50;
        }
        .quick-access .card p {
            font-size: 14px;
            color: #555;
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
        .facilities {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
        }
        .facilities h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .facility-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: auto;
        }
        .facility-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .facility-item i {
            font-size: 36px;
            margin-bottom: 10px;
            color: #34495e;
        }
        .facility-item h3 {
            font-size: 18px;
            color: #2c3e50;
        }
        .testimonials {
            padding: 30px;
            background: white;
            text-align: center;
        }
        .testimonials h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .testimonial-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: auto;
        }
        .testimonial-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .testimonial-item p {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }
        .testimonial-item strong {
            font-size: 16px;
            color: #2c3e50;
        }
        .rating {
            color: #f1c40f;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <div>Explore Our Luxurious Rooms</div>
        <div class="header-right">
            <a href="../customer/edit.php" class="profile-icon"></a>
            <a href="../../logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div>
            <h1>Welcome to Paradise Resort</h1>
            <p>Your perfect getaway awaits.</p>
        </div>
    </section>

    <!-- Fixed Explore Rooms Button -->
    <a href="#room-container" class="explore-button">Explore Rooms</a>

    <!-- Quick Access Cards -->
    <section class="quick-access">
        <a href="../booking/create.php" class="card">
            <i class="fas fa-bed"></i>
            <h3>Book a Room</h3>
            <p>Find your perfect stay.</p>
        </a>
        <a href="../facilities/index.php" class="card">
            <i class="fas fa-swimming-pool"></i>
            <h3>View Facilities</h3>
            <p>Explore our amenities.</p>
        </a>
        <a href="../bookings/index.php" class="card">
            <i class="fas fa-calendar-check"></i>
            <h3>Check Bookings</h3>
            <p>Manage your reservations.</p>
        </a>
        <a href="../feedback/create.php" class="card">
            <i class="fas fa-comment"></i>
            <h3>Leave Feedback</h3>
            <p>Share your experience.</p>
        </a>
    </section>

    <!-- Room Listing -->
    <main class="room-container" id="room-container">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="room-card">
                <img src="../../resources/assets/room_images/<?php echo $row['room_img']; ?>" alt="Room Image">
                <div class="content">
                    <h2><?php echo $row['room_code']; ?></h2>
                    <p class="price">$<?php echo $row['price']; ?> per night</p>
                    <a href="../booking/create.php?room_id=<?php echo $row['room_id']; ?>" class="book-btn">Book Now</a>
                </div>
            </div>
        <?php } ?>
    </main>

    <!-- Facilities Section -->
    <section class="facilities">
        <h2>Our Facilities</h2>
        <div class="facility-list">
            <div class="facility-item">
                <i class="fas fa-swimmer"></i>
                <h3>Swimming Pool</h3>
            </div>
            <div class="facility-item">
                <i class="fas fa-spa"></i>
                <h3>Spa & Wellness</h3>
            </div>
            <div class="facility-item">
                <i class="fas fa-utensils"></i>
                <h3>Restaurant & Bar</h3>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <h2>What Our Guests Say</h2>
        <div class="testimonial-list">
            <div class="testimonial-item">
                <p>"Amazing experience! The staff was very friendly."</p>
                <strong>- John Doe</strong>
                <div class="rating">★★★★★</div>
            </div>
            <div class="testimonial-item">
                <p>"The rooms were clean and luxurious. Highly recommended!"</p>
                <strong>- Jane Smith</strong>
                <div class="rating">★★★★☆</div>
            </div>
        </div>
    </section>
</body>
</html>
<?php $conn->close(); ?>