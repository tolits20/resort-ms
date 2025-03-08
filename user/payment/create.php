<?php
// Start session

// Include database connection
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

// Retrieve account ID
$account_id = $_SESSION['ID'];

// Retrieve booking ID and amount from the query parameters
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;
$amount = isset($_GET['amount']) ? floatval($_GET['amount']) : 0;

if ($book_id == 0 || $amount == 0) {
    echo "Invalid booking information.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Payment</title>
</head>
<body>
    <h2>Store Payment Information</h2>
    <form action="store.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book_id); ?>">
        <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>">
        <!-- <input type="hidden" name="account_id" value="<?php echo htmlspecialchars($account_id); ?>"> -->

        <label for="pay_amount">Pay Amount:</label><br>
        <input type="number" id="pay_amount" name="pay_amount" step="0.01" min="1" required><br><br>

        <label for="payment_type">Payment Type:</label><br>
        <select id="payment_type" name="payment_type" required>
            <option value="credit_card">Credit Card</option>
            <option value="e-wallet">E-Wallet</option>
        </select><br><br>

        <label for="payment_img">Payment Image:</label><br>
        <input type="file" id="payment_img" name="payment_img" accept="image/*" required><br><br>

        <label for="transaction_id">Transaction ID:</label><br>
        <input type="text" id="transaction_id" name="transaction_id" required><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
