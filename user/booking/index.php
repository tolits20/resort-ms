<?php
include('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$account_id = $_SESSION['ID'];

$sql = "SELECT b.book_id, b.check_in, b.check_out, b.book_status, b.price, 
               r.room_code, r.room_type 
        FROM booking b
        JOIN room r ON b.room_id = r.room_id
        WHERE b.account_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $account_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Bookings</title>
    <?php include '../bootstrap.php'; ?>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">My Bookings</h2>
    <a href="create.php" class="btn btn-primary mb-3">Book a Room</a>
    
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Booking ID</th>
                <th>Room Code</th>
                <th>Room Type</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Price (USD)</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_array()) { ?>
                <tr>
                    <td><?php echo $row[0]; ?></td>
                    <td><?php echo htmlspecialchars($row[5]); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($row[6])); ?></td>
                    <td><?php echo $row[1]; ?></td>
                    <td><?php echo $row[2]; ?></td>
                    <td><?php echo number_format((float)$row[4], 2); ?></td>
                    <td>
                        <span class="badge <?php echo ($row[3] == 'confirmed') ? 'bg-success' : 'bg-warning'; ?>">
                            <?php echo ucfirst($row[3]); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($row[3] !== 'confirmed') { ?>
                            <a href="edit.php?id=<?php echo $row[0]; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <?php } else { ?>
                            <button class="btn btn-sm btn-secondary" disabled>Confirmed</button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
