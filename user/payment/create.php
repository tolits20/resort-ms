<?php
include ('../../resources/database/config.php');
include("../../admin/includes/system_update.php");

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$account_id = $_SESSION['ID'];

// Get user data for the navbar
$sql_user = "SELECT u.*, a.username 
             FROM user u 
             JOIN account a ON u.account_id = a.account_id 
             WHERE u.account_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $account_id);
$stmt_user->execute();
$user_data = $stmt_user->get_result()->fetch_assoc();

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
    <title>Paradise Resort | Payment Information</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        /* Room Info Bar */
        .room-info-bar {
            background: var(--white);
            padding: 1rem 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.5rem;
            color: var(--primary);
            font-weight: 600;
        }

        .user-info {
            text-align: right;
            color: var(--secondary);
            font-size: 0.9rem;
        }

        /* Payment Form Container */
        .payment-container {
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 2rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .amount-display {
            background: var(--light);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .amount-display h3 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .amount-display .price {
            font-size: 2rem;
            color: var(--success);
            font-weight: bold;
        }

        /* Form Styles */
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

        input[type="file"] {
            width: 100%;
            padding: 0.5rem;
            background: var(--light);
            border-radius: 8px;
            cursor: pointer;
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
        }

        .submit-btn:hover {
            background: #219653;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .payment-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include("../view/navbar.php"); ?>

    <div class="main-container">
        <!-- Info Bar -->
        <div class="room-info-bar">
            <div class="page-title">
                Payment Information
            </div>
            <div class="user-info">
                <div><?php echo date('Y-m-d H:i:s'); ?> UTC</div>
                <div>Welcome, <?php echo htmlspecialchars($user_data['username']); ?></div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="payment-container">
            <div class="amount-display">
                <h3>Total Amount Due</h3>
                <div class="price">₱<?php echo number_format($amount, 2); ?></div>
            </div>

            <form action="store.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book_id); ?>">
                <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>">

                <div class="form-group">
                    <label for="pay_amount">Payment Amount:</label>
                    <input type="number" id="pay_amount" name="pay_amount" step="0.01" min="1" 
                           value="<?php echo $amount; ?>" required>
                </div>

                <div class="form-group">
                    <label for="payment_type">Payment Method:</label>
                    <select id="payment_type" name="payment_type" required>
                        <option value="">Select payment method</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="e-wallet">E-Wallet</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="payment_img">Payment Screenshot:</label>
                    <input type="file" id="payment_img" name="payment_img" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="transaction_id">Transaction ID:</label>
                    <input type="text" id="transaction_id" name="transaction_id" required>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-check-circle"></i> Confirm Payment
                </button>
            </form>
        </div>
    </div>

    <?php include("../view/footer.php"); ?>
</body>
</html>