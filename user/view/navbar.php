<?php
if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

if (isset($_SESSION['ID'])) {
    $account_id = $_SESSION['ID'];

    $sql1 = "SELECT u.*, a.username, a.password, u.profile_img 
             FROM user u 
             JOIN account a ON u.account_id = a.account_id 
             WHERE u.account_id = ?";
    $stmt = $conn->prepare($sql1);
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    if (!$user_data) {
        echo "User data not found.";
        exit;
    }

    $_SESSION['update_id'] = $account_id;
} else {
    echo "User not logged in.";
    exit;
}
?>

<style>
    .navbar {
        background-color: var(--white);
        padding: 1rem 2rem;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .navbar-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .nav-brand {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary);
        text-decoration: none;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .nav-links a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .nav-links a:hover {
        color: var(--accent);
        transform: translateY(-2px);
    }

    .nav-links a i {
        font-size: 1.1rem;
    }

    .profile-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid var(--accent);
    }

    .profile-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .logout-btn {
        background-color: var(--accent);
        color: var(--white);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background-color: #d35400;
        transform: translateY(-2px);
    }

    .user-info {
        text-align: right;
        font-size: 0.8rem;
        color: var(--secondary);
        position: absolute;
        right: 2rem;
        top: -1.5rem;
        background: var(--white);
        padding: 0.3rem 1rem;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    @media (max-width: 968px) {
        .nav-links {
            gap: 1rem;
        }
        
        .nav-links a span {
            display: none;
        }

        .user-info {
            display: none;
        }
    }
</style>

<div class="user-info">
    <?php echo date('Y-m-d H:i:s'); ?> UTC | 
    Welcome, <?php echo htmlspecialchars($user_data['username']); ?>
</div>

<nav class="navbar">
    <div class="navbar-content">
        <a href="#" class="nav-brand">Paradise Resort</a>
        <div class="nav-links">
            <a href="../view/home.php"><i class="fas fa-home"></i> <span>Home</span></a>
            <a href="../booking/rooms.php"><i class="fas fa-bed"></i> <span>Rooms</span></a>
            <a href="../booking/index.php"><i class="fas fa-calendar-check"></i> <span>Bookings</span></a>
            <a href="../payment/index.php"><i class="fas fa-credit-card"></i> <span>Payments</span></a>
            <a href="../feedback/index.php"><i class="fas fa-comment"></i> <span>Feedback</span></a>
            <a href="../customer/edit.php" class="profile-img">
                <img src="../../resources/assets/images/<?php echo htmlspecialchars($user_data['profile_img']); ?>" 
                     alt="Profile Image"
                     onerror="this.src='../../resources/assets/images/default-profile.png'">
            </a>
            <a href="../../logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </div>
    </div>
</nav>