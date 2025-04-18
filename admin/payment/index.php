<?php
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include('../includes/template.php');
include('../includes/system_update.php');

if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];

    $sql = "SELECT * FROM summary_payment WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $res2 = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Overall Content */
        .content {
            display: flex;
            flex-direction: row;
            background: #fff;
            width: 100%;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            flex-wrap: wrap; /* Responsive */
        }

        .container:hover {
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.15);
        }

        /* Form Section */
        .form-section {
            width: 60%;
            padding-right: 20px;
        }

        .content h2 {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: 500;
            color: #555;
            font-size: 14px;
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: 0.3s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            background: #fff;
            outline: none;
        }

        /* Summary Section */
        .summary-section {
            width: 40%;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: left;
            color: black;
        }

        .summary-section h3 {
            font-size: 18px;
            color: #444;
            font-weight: bold;
            text-align: center;
        }

        .summary-box {
            background: white;
            padding: 15px;
            margin-top: 10px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .summary-box p {
            font-size: 14px;
            margin: 10px 0;
        }

        /* Buttons */
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            font-weight: bold;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s ease-in-out;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: scale(1.05);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content {
                flex-direction: column;
            }

            .form-section,
            .summary-section {
                width: 100%;
            }
        }

        .generate-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background: #28a745;
            color: white;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s ease-in-out;
        }

        .download-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background: rgb(228, 8, 15);
            color: white;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s ease-in-out;
        }

        .generate-btn:hover {
            background: #218838;
            transform: scale(1.05);
        }

        .download-btn:hover {
            background: rgb(228, 8, 15);
            transform: scale(1.05);
        }

        /* Receipt Section */
        .receipt-section {
            width: 100%;
            margin-top: 20px;
            text-align: center;
        }

        .receipt-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }

        .default-receipt {
            width: 200px;
            height: auto;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="content">
        <!-- Left: Form Section -->
        <div class="form-section">
            <h2><i class="fas fa-edit"></i> Edit Payment</h2>
            
            <?php if ($res2): ?>
                <form action="update.php?id=<?php echo $_GET['id'] ?>" method="POST">
                    <input type="hidden" name="payment_id" value="<?= htmlspecialchars($res2['booking_id']); ?>">

                    <div class="form-group">
                        <label>Customer Name</label>
                        <input type="text" class="form-control" name="customer_name" value="<?= htmlspecialchars($res2['NAME']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" class="form-control" name="amount" value="<?= htmlspecialchars($res2['amount']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Amount Paid</label>
                        <input type="number" class="form-control" name="pay_amount" value="<?= htmlspecialchars($res2['amount_paid']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Payment Method</label>
                        <select class="form-select" name="payment_method" required>
                            <option value="none" <?= (empty($res2['payment_type'])) ? 'selected' : ''; ?>>N/A</option>
                            <option value="credit_card" <?= ($res2['payment_type'] == 'credit_card') ? 'selected' : ''; ?>>Credit Card</option>
                            <option value="e-wallet" <?= ($res2['payment_type'] == 'e-wallet') ? 'selected' : ''; ?>>E Wallet</option>
                            <option value="cash" <?= ($res2['payment_type'] == 'cash') ? 'selected' : ''; ?>>Cash</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-select" name="status" required>
                            <option value="pending" <?= ($res2['payment_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="paid" <?= ($res2['payment_status'] == 'paid') ? 'selected' : ''; ?>>Paid</option>
                            <option value="refunded" <?= ($res2['payment_status'] == 'refunded') ? 'selected' : ''; ?>>Refunded</option>
                        </select>
                    </div>

                    <!-- Receipt Image Section -->
                    <div class="receipt-section">
                        <h3>Receipt Preview</h3>
                        <?php 
                        if(!empty($res2['receipt'])): ?>
                            <img src="../../resources/assets/payment_images/<?= htmlspecialchars($res2['receipt']); ?>" alt="Receipt Image" class="receipt-image">
                        <?php else: ?>
                            <img src="default_receipt.jpg" alt="Default Receipt" class="receipt-image default-receipt">
                            <p>No receipt image available.</p>
                        <?php endif; ?>
                    </div>

                    <div class="btn-container">
                        <button type="submit" class="btn btn-primary" name="save" value="save">Save Changes</button>
                        <button onclick="history.back()" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            <?php else: ?>
                <p style="color: red; text-align: center;">No payment details found.</p>
            <?php endif; ?>
        </div>

        <!-- Right: Summary Section -->
        <?php if ($res2): 
            $check_in = date("F d, Y h:i A", strtotime($res2['check_in']));
            $check_out= date("F d, Y h:i A", strtotime($res2['check_out']));?>
            <div class="summary-section">
                <h3>Payment Details</h3>
                <div class="summary-box">
                    <p><strong>Customer:</strong> <?= htmlspecialchars($res2['NAME']); ?></p>
                    <p><strong>Room:</strong> <?= htmlspecialchars($res2['room_code']); ?></p>
                    <p><strong>Check In:</strong> <?= htmlspecialchars($check_in); ?></p>
                    <p><strong>Check Out:</strong> <?= htmlspecialchars($check_out); ?></p>
                    <p><strong>Book Status:</strong> <?= htmlspecialchars($res2['book_status']); ?></p>
                    <p><strong>Amount:</strong> <?= htmlspecialchars($res2['amount_paid']); ?> PHP</p>
                    <p><strong>Method:</strong> <?= htmlspecialchars(empty($res2['payment_type']) ? 'N/A' : $res2['payment_type']); ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($res2['payment_status']); ?></p>
                </div>
                <a href="generate.php?id=<?= $res2['booking_id']; ?>" class="generate-btn">
                    <i class="fa-solid fa-file"></i> Generate Invoice
                </a>
                <a href="generate.php?id=<?= $res2['booking_id']; ?>&download=1" class="download-btn">
                    <i class="fa-solid fa-download"></i> Download PDF
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>