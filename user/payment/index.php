<?php
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$account_id = $_SESSION['ID'];


// Fetch payments
$sql = "SELECT p.*, b.account_id FROM payment p
        JOIN booking b ON p.book_id = b.book_id
        WHERE b.account_id = ?";
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
    <title>Paradise Resort | Payments</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #e67e22;
            --light: #f8f9fa;
            --dark: #2c3e50;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f1c40f;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light);
        }

        .main-container {
            max-width: 1200px;
            margin: 100px auto 2rem;
            padding: 2rem;
        }

        .info-bar {
            background: var(--white);
            padding: 1.5rem 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.8rem;
            color: var(--primary);
            font-weight: 600;
        }

        .current-info {
            text-align: right;
            color: var(--secondary);
            font-size: 0.9rem;
        }

        .payments-container {
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 2rem;
            overflow-x: auto;
        }

        .payments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .payments-table th {
            background: var(--primary);
            color: var(--white);
            padding: 1rem;
            text-align: left;
            font-weight: 500;
        }

        .payments-table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .payments-table tr:hover {
            background: var(--light);
        }

        .payment-img {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-refunded {
            background: #f8d7da;
            color: #721c24;
        }

        .action-icon {
            color: var(--accent);
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .action-icon:hover {
            transform: scale(1.2);
        }

        .action-icon.disabled {
            color: #ccc;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }

            .info-bar {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .current-info {
                text-align: center;
            }

            .payments-table th, 
            .payments-table td {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <?php include("../view/navbar.php"); ?>

    <div class="main-container">
        <div class="info-bar">
            <div class="page-title">Payment History</div>
            <div class="current-info">
                <div><?php echo date('Y-m-d H:i:s'); ?> UTC</div>
                <div>Welcome, <?php echo htmlspecialchars($user_data['username']); ?></div>
            </div>
        </div>

        <div class="payments-container">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Payment Type</th>
                        <th>Receipt</th>
                        <th>Transaction ID</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td>#<?php echo $payment['book_id']; ?></td>
                        <td>₱<?php echo number_format($payment['amount'], 2); ?></td>
                        <td>₱<?php echo number_format($payment['pay_amount'], 2); ?></td>
                        <td>
                            <i class="fas <?php echo $payment['payment_type'] == 'credit_card' ? 'fa-credit-card' : 'fa-wallet'; ?>"></i>
                            <?php echo ucfirst(str_replace('_', ' ', $payment['payment_type'])); ?>
                        </td>
                        <td>
                            <img class="payment-img" src="../../resources/assets/payment_images/<?php echo $payment['payment_img']; ?>" 
                                 alt="Payment Receipt">
                        </td>
                        <td><?php echo $payment['transaction_id']; ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($payment['payment_status']); ?>">
                                <?php echo ucfirst($payment['payment_status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($payment['payment_status'] == 'paid' || $payment['payment_status'] == 'refunded'): ?>
                                <i class="fas fa-edit action-icon disabled"></i>
                            <?php else: ?>
                                <a href="edit.php?payment_id=<?php echo $payment['payment_id']; ?>">
                                    <i class="fas fa-edit action-icon"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include("../view/footer.php"); ?>
</body>
</html>