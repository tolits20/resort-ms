<?php
include('../includes/template.html');
include('../../resources/database/config.php');


if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];

    $sql = "SELECT * FROM book_payment WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $sql="SELECT * FROM customer_booking WHERE id=$payment_id";
    $result2=mysqli_query($conn,$sql);
    $res2=mysqli_fetch_assoc($result2);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "<script>alert('Payment record not found.'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid Request.'); window.location.href='index.php';</script>";
    exit();
}
?>

<style>


.content {
    display: flex;
    flex-direction: row;
    background: #fff;
    width: 100%;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
    transition: 0.3s;
}

.container:hover {
    box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.15);
}

.form-section {
    width: 60%;
    padding-right: 20px;
}

h2 {
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
    background:rgba(249, 249, 249, 0.44);
    transition: 0.3s ease-in-out;
}

.form-control:focus,
.form-select:focus {
    border-color: #007bff;
    background: #fff;
    outline: none;
}

/* Right Column (Summary) */
.summary-section {
    width: 40%;
    background:#f8f9fa;
    padding: 20px;
    border-radius: 10px;
    text-align: Start;
    color: black;
}

.summary-section h3 {
    font-size: 18px;
    color: #444;
    font-weight: bold;
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
    margin: 15px 0;
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
</style>

<div class="content">
    <!-- Left: Form Section -->
    <div class="form-section">
        <h2><i class="fas fa-edit"></i> Edit Payment</h2>
        
        <form action="update_payment.php" method="POST">
            <input type="hidden" name="payment_id" value="<?= $row['ID']; ?>">

            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" class="form-control" name="customer_name" value="<?= $row['name']; ?>" required>
            </div>

            <div class="form-group">
                <label>Amount</label>
                <input type="number" class="form-control" name="amount" value="<?= $row['amount']; ?>" required>
            </div>

            <div class="form-group">
                <label>Payment Method</label>
                <select class="form-select" name="payment_method" required>
                    <option value="credit card" <?= ($row['payment_method'] == 'credit card') ? 'selected' : ''; ?>>Credit Card</option>
                    <option value="e-payment" <?= ($row['payment_method'] == 'e-payment') ? 'selected' : ''; ?>>E payment</option>
                    <option value="cash" <?= ($row['payment_method'] == 'cash') ? 'selected' : ''; ?>>Cash</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select class="form-select" name="status" required>
                    <option value="Pending" <?= ($row['payment_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="Completed" <?= ($row['payment_status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="Failed" <?= ($row['payment_status'] == 'Failed') ? 'selected' : ''; ?>>Failed</option>
                </select>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Right: Summary Section (Optional) -->
    <div class="summary-section">
        <center><h3>Payment Details</h3></center>
        <div class="summary-box">
            <p><strong>Customer:</strong> <?= $row['name']; ?></p>
            <p><strong>Room:</strong> <?= $res2['room_code']; ?></p>
            <p><strong>Check In:</strong> <?= $res2['check_in']; ?></p>
            <p><strong>Check Out:</strong> <?= $res2['check_out']; ?></p>
            <p><strong>Status:</strong> <?= $res2['status']; ?></p>
            <p><strong>Amount:</strong> <?= $row['amount']; ?>php</p>
            <p><strong>Method:</strong> <?= $row['payment_method']; ?></p>
            <p><strong>Status:</strong> <?= $row['payment_status']; ?></p>
        </div>
    </div>
</div>
