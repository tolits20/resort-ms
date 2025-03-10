<?php
include('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$account_id = $_SESSION['ID'];

// Get user data for navbar
$sql_user = "SELECT u.*, a.username FROM user u 
             JOIN account a ON u.account_id = a.account_id 
             WHERE u.account_id = ?";
$stmt_user = mysqli_prepare($conn, $sql_user);
mysqli_stmt_bind_param($stmt_user, "i", $account_id);
mysqli_stmt_execute($stmt_user);
$user_data = mysqli_stmt_get_result($stmt_user)->fetch_assoc();

// Get bookings
$sql = "SELECT b.book_id, b.room_id, b.check_in, b.check_out, b.book_status, 
               r.room_code, r.room_type, r.price 
        FROM booking b 
        JOIN room r ON b.room_id = r.room_id 
        WHERE b.account_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $account_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$bookings = [];
while ($row = mysqli_fetch_array($result)) {
    $bookings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paradise Resort | My Bookings</title>
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

        /* Page Header Styles */
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
            min-height: 300px;
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

        /* Bookings Table Styles */
        .bookings-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        .table th {
            background-color: var(--primary);
            color: var(--white);
            padding: 1rem;
            text-align: left;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--white);
        }

        .badge-pending { background-color: #f39c12; }
        .badge-confirmed { background-color: var(--success); }
        .badge-completed { background-color: var(--primary); }
        .badge-cancelled { background-color: #e74c3c; }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }

        .btn-danger {
            background-color: #e74c3c;
            color: var(--white);
        }

        .btn-success {
            background-color: var(--success);
            color: var(--white);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .current-info {
            margin-bottom: 2rem;
            color: var(--white);
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include("../view/navbar.php")?>

    <section class="page-header">
        <h1>My Bookings</h1>

    </section>

    <div class="bookings-container">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Room</th>
                    <th>Type</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem;">No bookings found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bookings as $index => $booking): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>Room <?php echo htmlspecialchars($booking['room_code']); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($booking['room_type'])); ?></td>
                            <td><?php echo htmlspecialchars($booking['check_in']); ?></td>
                            <td><?php echo htmlspecialchars($booking['check_out']); ?></td>
                            <td>₱<?php echo number_format((float)$booking['price'], 2); ?></td>
                            <td>
                                <?php
                                    $status = strtolower($booking['book_status']);
                                    $badge_class = "badge-secondary";
                                    if ($status == "pending") $badge_class = "badge-pending";
                                    if ($status == "completed") $badge_class = "badge-completed";
                                    if ($status == "confirmed") $badge_class = "badge-confirmed";
                                    if ($status == "cancelled") $badge_class = "badge-cancelled";
                                ?>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <?php echo ucfirst($booking['book_status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($booking['book_status'] == 'pending'): ?>
                                    <a href="edit.php?booking_id=<?php echo $booking['book_id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>
                                    <a href="cancel.php?booking_id=<?php echo $booking['book_id']; ?>" 
                                       class="btn btn-danger"
                                       onclick="return confirm('Are you sure you want to cancel this booking?');">
                                        <i class="fas fa-ban"></i> Cancel
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-lock"></i> Locked
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="../booking/rooms.php" class="btn btn-success">
            <i class="fas fa-plus"></i> New Booking
        </a>
    </div>
</body>
</html>