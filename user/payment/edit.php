<?php
include ('../../resources/database/config.php');
include("../../admin/includes/system_update.php");

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$payment_id = isset($_GET['payment_id']) ? intval($_GET['payment_id']) : 0;

if ($payment_id == 0) {
    echo "Invalid payment information.";
    exit;
}

$sql = "SELECT * FROM payment WHERE payment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payment_id);
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
    <title>Paradise Resort | Edit Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #e67e22;
            --light: #f8f9fa;
            --dark: #2c3e50;
            --success: #27ae60;
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

        .datetime-info {
            text-align: right;
            color: var(--secondary);
            font-size: 0.9rem;
        }

        .payment-form-container {
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 2rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            color: var(--primary);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        input[type="number"],
        input[type="text"],
        select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--accent);
        }

        input[type="file"] {
            width: 100%;
            padding: 0.5rem;
            background: var(--light);
            border-radius: 8px;
            cursor: pointer;
        }

        .current-image {
            margin: 1rem 0;
            text-align: center;
        }

        .current-image img {
            max-width: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .submit-btn {
            background: var(--success);
            color: var(--white);
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .submit-btn:hover {
            background: #219653;
            transform: translateY(-2px);
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

            .datetime-info {
                text-align: center;
            }

            .payment-form-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include("../view/navbar.php"); ?>

    <div class="main-container">
        <div class="info-bar">
            <div class="page-title">Edit Payment</div>
            <div class="datetime-info">
                <div>2025-03-10 13:57:02 UTC</div>
                <div>Welcome, tolits20</div>
            </div>
        </div>

        <div class="payment-form-container">
            <form action="update.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="payment_id" value="<?php echo $payment_id; ?>">

                <div class="form-group">
                    <label for="pay_amount">Payment Amount:</label>
                    <input type="number" id="pay_amount" name="pay_amount" 
                           value="<?php echo $payment['pay_amount']; ?>" 
                           step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="payment_type">Payment Method:</label>
                    <select id="payment_type" name="payment_type" required>
                        <option value="credit_card" <?php echo $payment['payment_type'] == 'credit_card' ? 'selected' : ''; ?>>
                            Credit Card
                        </option>
                        <option value="e-wallet" <?php echo $payment['payment_type'] == 'e-wallet' ? 'selected' : ''; ?>>
                            E-Wallet
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="payment_img">Payment Receipt:</label>
                    <input type="file" id="payment_img" name="payment_img" accept="image/*">
                    
                    <?php if ($payment['payment_img']): ?>
                    <div class="current-image">
                        <p>Current Receipt:</p>
                        <img src="../../resources/assets/payment_images/<?php echo $payment['payment_img']; ?>" 
                             alt="Current Payment Receipt">
                    </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="transaction_id">Transaction ID:</label>
                    <input type="text" id="transaction_id" name="transaction_id" 
                           value="<?php echo $payment['transaction_id']; ?>" required>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-save"></i> Update Payment
                </button>
            </form>
        </div>
    </div>

    <?php include("../view/footer.php"); ?>
</body>
</html>