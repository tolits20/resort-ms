<?php
// Include database connection
include ('../../resources/database/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Retrieve payment information from POST request
        $payment_id = intval($_POST['payment_id']);
        $pay_amount = floatval($_POST['pay_amount']);
        $payment_type = $_POST['payment_type'];
        $transaction_id = $_POST['transaction_id'];

        // Fetch the existing payment image before updating
        $sql_select = "SELECT payment_img FROM payment WHERE payment_id = ?";
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->bind_param("i", $payment_id);
        $stmt_select->execute();
        $stmt_select->bind_result($old_image);
        $stmt_select->fetch();
        $stmt_select->close();

        // Handle file upload
        if (isset($_FILES['payment_img']) && $_FILES['payment_img']['error'] == 0) {
            $target_dir = "../../resources/assets/payment_images/";
            $imageFileType = strtolower(pathinfo($_FILES["payment_img"]["name"], PATHINFO_EXTENSION));
            $unique_name = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $unique_name;

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

            // Delete the old image if it exists
            if (!empty($old_image) && file_exists($target_dir . $old_image)) {
                unlink($target_dir . $old_image);
            }

            // Update payment record with new image
            $sql = "UPDATE payment SET pay_amount = ?, payment_type = ?, payment_img = ?, transaction_id = ?, updated_at = NOW() WHERE payment_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("dsssi", $pay_amount, $payment_type, $unique_name, $transaction_id, $payment_id);
        } else {
            // Update payment record without changing the image
            $sql = "UPDATE payment SET pay_amount = ?, payment_type = ?, transaction_id = ?, updated_at = NOW() WHERE payment_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("dssi", $pay_amount, $payment_type, $transaction_id, $payment_id);
        }

        $stmt->execute();

        if ($stmt->affected_rows == 0) {
            throw new Exception("Failed to update payment.");
        }

        // Redirect to a success page or show a success message
        echo "Payment information updated successfully.";
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>
