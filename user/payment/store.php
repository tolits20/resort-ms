<?php
// Start session
session_start();

// Include database connection
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Retrieve payment information from POST request
        $account_id = isset($_POST['account_id']) ? intval($_POST['account_id']) : 0;
        $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
        $pay_amount = isset($_POST['pay_amount']) ? floatval($_POST['pay_amount']) : 0;
        $payment_type = isset($_POST['payment_type']) ? $_POST['payment_type'] : '';
        $transaction_id = isset($_POST['transaction_id']) ? trim($_POST['transaction_id']) : '';

        // Ensure required fields are not empty
        if ($account_id == 0 || $book_id == 0 || $amount == 0 || $pay_amount == 0 || empty($payment_type) || empty($transaction_id)) {
            throw new Exception("Invalid payment details.");
        }

        $payment_status = "pending"; // Default status

        // Handle file upload
        if (isset($_FILES['payment_img']) && $_FILES['payment_img']['error'] == 0) {
            $target_dir = "../../resources/assets/payment_images/";
            $imageFileType = strtolower(pathinfo($_FILES["payment_img"]["name"], PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

            // Validate file type
            if (!in_array($imageFileType, $allowed_types)) {
                throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed.");
            }

            // Validate file size (limit: 5MB)
            if ($_FILES["payment_img"]["size"] > 5000000) {
                throw new Exception("File is too large. Maximum 5MB allowed.");
            }

            // Ensure the target directory exists
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Generate a unique filename
            $unique_name = uniqid("pay_", true) . "." . $imageFileType;
            $target_file = $target_dir . $unique_name;

            // Check if file is an actual image
            $check = getimagesize($_FILES["payment_img"]["tmp_name"]);
            if ($check === false) {
                throw new Exception("Uploaded file is not a valid image.");
            }

            // Move file to target directory
            if (!move_uploaded_file($_FILES["payment_img"]["tmp_name"], $target_file)) {
                throw new Exception("Error uploading file.");
            }
        } else {
            throw new Exception("Payment image is required.");
        }

        // Insert payment information into the 'payment' table
        $sql = "INSERT INTO payment (account_id, book_id, amount, pay_amount, payment_type, payment_img, transaction_id, payment_status, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiddssss", $account_id, $book_id, $amount, $pay_amount, $payment_type, $unique_name, $transaction_id, $payment_status);
        $stmt->execute();

        if ($stmt->affected_rows == 0) {
            throw new Exception("Failed to insert payment.");
        }

        $stmt->close();
        $conn->close();

        // Redirect to a success page or show a success message
        echo "<script>alert('Payment submitted successfully.'); window.location.href='success.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>
