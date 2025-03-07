<?php
// Include database connection
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$account_id = $_SESSION['ID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Retrieve payment information from POST request
        $book_id = intval($_POST['book_id']);
        $amount = floatval($_POST['amount']);
        $pay_amount = floatval($_POST['pay_amount']);
        $payment_type = $_POST['payment_type'];
        $payment_img = $_POST['payment_img']; // Assuming this is the path to the payment image
        $transaction_id = $_POST['transaction_id'];
        $payment_status = "pending"; // Default status

        if (!$book_id || !$amount || empty($payment_type) || empty($payment_img) || empty($transaction_id)) {
            throw new Exception("All payment fields are required.");
        }

        // Insert payment information into the 'payment' table
        $sql = "INSERT INTO payment (book_id, amount, pay_amount, payment_type, payment_img, transaction_id, payment_status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idsssss", $book_id, $amount, $pay_amount, $payment_type, $payment_img, $transaction_id, $payment_status);
        $stmt->execute();

        if ($stmt->affected_rows == 0) {
            throw new Exception("Failed to insert payment.");
        }

        // Redirect to a success page or show a success message
        echo "Payment information inserted successfully.";
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>