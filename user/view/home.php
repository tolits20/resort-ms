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
    <title>Paradise Resort | Luxury Stays</title>
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

        /* Modern Navbar */


        /* Hero Section */
      /* Hero Section */
.hero {
    height: 100vh;
    background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
                url('../../resources/assets/resort_images/hero_img.jpg'); /* Add your hero background image */
    background-size: cover;
    background-position: center;
    background-attachment: fixed; /* Creates a parallax effect */
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 60px; /* Account for fixed navbar */
}

.hero-content {
    max-width: 800px;
    text-align: center;
    padding: 0 20px;
    color: var(--white);
}

.hero-content h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.hero-content p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

.cta-button {
    display: inline-block;
    background-color: var(--accent);
    color: var(--white);
    padding: 1rem 2.5rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.cta-button:hover {
    background-color: #d35400;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2.5rem;
    }
    
    .hero-content p {
        font-size: 1rem;
    }
    
    .cta-button {
        padding: 0.8rem 2rem;
        font-size: 1rem;
    }
}

        /* Room Grid */
        .image-grid {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 2rem;
        }

        .image-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            position: relative;
        }

        .image-card.large {
            grid-column: span 12;
        }

        .image-card.medium {
            grid-column: span 6;
        }

        .image-card.small {
            grid-column: span 4;
        }

        .image-card:hover {
            transform: translateY(-10px);
        }

        .image-wrapper {
            position: relative;
            overflow: hidden;
        }

        .image-card.large .image-wrapper {
            height: 500px;
        }

        .image-card.medium .image-wrapper {
            height: 400px;
        }

        .image-card.small .image-wrapper {
            height: 300px;
        }

        .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .image-card:hover .image-wrapper img {
            transform: scale(1.1);
        }

        .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            padding: 2rem;
            color: white;
        }

        .image-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        .image-desc {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .image-card.large,
            .image-card.medium,
            .image-card.small {
                grid-column: span 12;
            }

            .image-card.large .image-wrapper,
            .image-card.medium .image-wrapper,
            .image-card.small .image-wrapper {
                height: 300px;
            }
        }


        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
            }

            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .room-card.large,
            .room-card.medium,
            .room-card.small {
                grid-column: span 12;
            }

            .room-card.large .room-img,
            .room-card.medium .room-img,
            .room-card.small .room-img {
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->

<?php include("navbar.php"); ?>
    <!-- Hero Section -->
<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Welcome to Paradise Resort</h1>
        <p>Experience luxury and comfort in our carefully curated accommodations</p>
        <p class="current-info" style="font-size: 0.9rem; margin-bottom: 1rem; opacity: 0.8;">
            <?php echo date('Y-m-d H:i:s'); ?> UTC
            <br>
            Welcome, <?php echo htmlspecialchars($row['username']); ?>
        </p>
        <a href="#explore" class="cta-button">Explore Resort</a>
    </div>
</section>

    <!-- Image Grid Section -->
    <section id="explore" class="image-grid">
        <!-- Large Featured Image -->
        <div class="image-card large">
            <div class="image-wrapper">
                <img src="../../resources/assets/resort_images/pool.jpg" alt="Luxury Pool">
                <div class="image-overlay">
                    <div class="image-title">Infinity Pool</div>
                    <div class="image-desc">Breathtaking views from our world-class infinity pool</div>
                </div>
            </div>
        </div>

        <!-- Medium Images -->
        <div class="image-card medium">
            <div class="image-wrapper">
                <img src="../../resources/assets/resort_images/resto_img.png" alt="Restaurant">
                <div class="image-overlay">
                    <div class="image-title">Fine Dining</div>
                    <div class="image-desc">Exquisite culinary experiences</div>
                </div>
            </div>
        </div>
        <div class="image-card medium">
            <div class="image-wrapper">
                <img src="../../resources/assets/resort_images/spa_img.jpg" alt="Spa">
                <div class="image-overlay">
                    <div class="image-title">Wellness Spa</div>
                    <div class="image-desc">Rejuvenate your body and mind</div>
                </div>
            </div>
        </div>

        <!-- Small Images -->
        <div class="image-card small">
            <div class="image-wrapper">
                <img src="../../resources/assets/resort_images/pool1_img.jpg" alt="Fitness Center">
                <div class="image-overlay">
                    <div class="image-title">Serene Oasis</div>
                    <div class="image-desc">Immerse yourself in the tranquility of our resort pools.</div>
                </div>
            </div>
        </div>
        <div class="image-card small">
            <div class="image-wrapper">
                <img src="../../resources/assets/resort_images/pool2.jpg" alt="Private Beach">
                <div class="image-overlay">
                    <div class="image-title">Refreshing Escape</div>
                    <div class="image-desc">Enjoy a leisurely swim or simply bask in the sun by our pristine pools.</div>
                </div>
            </div>
        </div>
        <div class="image-card small">
            <div class="image-wrapper">
                <img src="../../resources/assets/resort_images/pool3.jpg" alt="Lounge Bar">
                <div class="image-overlay">
                    <div class="image-title">Family-Friendly Fun </div>
                    <div class="image-desc">Dive into excitement with our spacious resort pools.</div>
                </div>
            </div>
        </div>
    </section>

<?php include("footer.php"); ?>
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>