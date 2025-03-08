<?php
// Include database connection
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

// Retrieve account ID
$account_id = $_SESSION['ID'];

// Fetch user's payments from the database
$sql = "SELECT * FROM payment WHERE account_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $account_id);
$stmt->execute();
$result = $stmt->get_result();
$payments = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>
    <h2>Your Payments</h2>
    <table border="1">
        <tr>
            <th>Booking ID</th>
            <th>Amount</th>
            <th>Pay Amount</th>
            <th>Payment Type</th>
            <th>Payment Image</th>
            <th>Transaction ID</th>
            <th>Payment Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($payments as $payment): ?>
        <tr>
            <td><?php echo $payment['book_id']; ?></td>
            <td><?php echo $payment['amount']; ?></td>
            <td><?php echo $payment['pay_amount']; ?></td>
            <td><?php echo $payment['payment_type']; ?></td>
            <td><img src="../../resources/assets/payment_images/<?php echo $payment['payment_img']; ?>" width="50" height="50"></td>
            <td><?php echo $payment['transaction_id']; ?></td>
            <td><?php echo $payment['payment_status']; ?></td>
            <td>
                <?php if ($payment['payment_status'] == 'paid' || $payment['payment_status'] == 'refunded'): ?>
                <i class="fas fa-edit" style="color: grey;"></i>
                <?php else: ?>
                <a href="edit.php?payment_id=<?php echo $payment['payment_id']; ?>"><i class="fas fa-edit"></i></a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>