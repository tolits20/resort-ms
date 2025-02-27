<?php
include('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$account_id = $_SESSION['ID'];
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
    <title>My Bookings</title>
    <?php include '../bootstrap.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .table-container {
            max-width: 900px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="table-container">
            <h2 class="text-center"><i class="fa-solid fa-calendar-check"></i> My Bookings</h2>
            <table class="table table-bordered">
                <thead class="table-dark">
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
                            <td colspan="8" class="text-center">No bookings found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bookings as $index => $booking): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>Room <?php echo htmlspecialchars($booking['room_code']); ?></td>
                                <td><?php echo ucfirst(htmlspecialchars($booking['room_type'])); ?></td>
                                <td><?php echo htmlspecialchars($booking['check_in']); ?></td>
                                <td><?php echo htmlspecialchars($booking['check_out']); ?></td>
                                <td>$<?php echo number_format((float)$booking['price'], 2); ?></td>
                                <td>
                                    <?php
                                        $status = strtolower($booking['book_status']);
                                        $badge_class = "secondary";
                                        if ($status == "pending") $badge_class = "warning";
                                        if ($status == "completed") $badge_class = "primary";
                                        if ($status == "confirmed") $badge_class = "success";
                                        if ($status == "cancelled") $badge_class = "danger";
                                    ?>
                                    <span class="badge bg-<?php echo $badge_class; ?>">
                                        <?php echo ucfirst($booking['book_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($booking['book_status'] == 'pending'): ?>
                                        <a href="edit.php?id=<?php echo $booking['book_id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>
                                        <a href="cancel.php?id=<?php echo $booking['book_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this booking?');">
                                            <i class="fa-solid fa-ban"></i> Cancel
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fa-solid fa-lock"></i> Locked
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="create.php" class="btn btn-success"><i class="fa-solid fa-plus"></i> New Booking</a>
        </div>
    </div>
</body>
</html>
