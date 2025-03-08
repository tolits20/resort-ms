<?php
if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
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
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--accent);
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
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #d35400;
        }
</style>

<!-- Updated Navbar HTML -->
<nav class="navbar">
    <a href="#" class="nav-brand">Paradise Resort</a>
    <div class="nav-links">
        <a href="../view/home.php"><i class="fas fa-home"></i> Home</a>
        <a href="../booking/rooms.php"><i class="fas fa-bed"></i> Rooms</a>
        <a href="../booking/index.php"><i class="fas fa-calendar-check"></i> Bookings</a>
        <a href="#"><i class="fas fa-comment"></i> Feedback</a>
        <a href="../customer/edit.php" class="profile-img">
            <img src="../../resources/assets/images/<?php echo htmlspecialchars($row['profile_img']); ?>" alt="Profile Image">
        </a>
        <a href="../../logout.php" class="logout-btn">Logout</a>
    </div>
</nav>