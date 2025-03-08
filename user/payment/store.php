<?php
// Include database connection
include ('../../resources/database/config.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Retrieve payment information from POST request
        $book_id = intval($_POST['book_id']);
        $amount = floatval($_POST['amount']);
        $pay_amount = floatval($_POST['pay_amount']);
        $payment_type = $_POST['payment_type'];
        $transaction_id = $_POST['transaction_id'];
        $payment_status = "pending"; // Default status

        // Handle file upload
        if (isset($_FILES['payment_img']) && $_FILES['payment_img']['error'] == 0) {
            $target_dir = "../../resources/assets/payment_images/";
            $target_file = $target_dir . basename($_FILES["payment_img"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if file is an actual image
            $check = getimagesize($_FILES["payment_img"]["tmp_name"]);
            if ($check === false) {
                throw new Exception("File is not an image.");
            }

            // Check file size (limit: 5MB)
            if ($_FILES["payment_img"]["size"] > 5000000) {
                throw new Exception("Sorry, your file is too large.");
            }

            // Allow certain file formats
            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            }

            // Move the file to the target directory
            if (!move_uploaded_file($_FILES["payment_img"]["tmp_name"], $target_file)) {
                throw new Exception("Sorry, there was an error uploading your file.");
            }
        } else {
            throw new Exception("Payment image is required.");
        }

        // Insert payment information into the 'payment' table
        $sql = "INSERT INTO payment (book_id, amount, pay_amount, payment_type, payment_img, transaction_id, payment_status, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iddssss", $book_id, $amount, $pay_amount, $payment_type, $target_file, $transaction_id, $payment_status);
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