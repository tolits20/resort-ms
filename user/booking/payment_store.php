<?php
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
$a = isset($_GET['amount']) ? floatval($_GET['amount']) : 0;

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
    <form action="payment_insert.php" method="post">
        <input type="hidden" id="book_id" name="book_id" value="<?php echo $book_id; ?>">
        <input type="text" id="amount" name="amount" value="<?php echo $amount;?>" disabled>
        <label for="payment_type">Payment Type:</label><br>
        <input type="text" id="payment_type" name="payment_type" required><br><br>
        <label for="payment_img">Payment Image Path:</label><br>
        <input type="text" id="payment_img" name="payment_img" required><br><br>
        <label for="transaction_id">Transaction ID:</label><br>
        <input type="text" id="transaction_id" name="transaction_id" required><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>