<?php
// Include database connection
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}


// Retrieve payment ID from the query parameters
$payment_id = isset($_GET['payment_id']) ? intval($_GET['payment_id']) : 0;

if ($payment_id == 0) {
    echo "Invalid payment information.";
    exit;
}

// Fetch payment record from the database
$sql = "SELECT * FROM payment WHERE payment_id = ? AND account_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $payment_id, $_SESSION['ID']);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();

if (!$payment) {
    echo "Payment not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
</head>
<body>
    <h2>Edit Payment Information</h2>
    <form action="update.php" method="post" enctype="multipart/form-data">
        <input type="hidden" id="payment_id" name="payment_id" value="<?php echo $payment_id; ?>">
        <label for="pay_amount">Pay Amount:</label><br>
        <input type="number" id="pay_amount" name="pay_amount" value="<?php echo $payment['pay_amount']; ?>" step="0.01" required><br><br>
        <label for="payment_type">Payment Type:</label><br>
        <select id="payment_type" name="payment_type" required>
            <option value="credit_card" <?php echo $payment['payment_type'] == 'credit_card' ? 'selected' : ''; ?>>Credit Card</option>
            <option value="e-wallet" <?php echo $payment['payment_type'] == 'e-wallet' ? 'selected' : ''; ?>>E-Wallet</option>
        </select><br><br>
        <label for="payment_img">Payment Image:</label><br>
        <input type="file" id="payment_img" name="payment_img" accept="image/*"><br><br>
        <label for="transaction_id">Transaction ID:</label><br>
        <input type="text" id="transaction_id" name="transaction_id" value="<?php echo $payment['transaction_id']; ?>" required><br><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>