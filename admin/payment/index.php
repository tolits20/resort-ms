<?php
include('../includes/template.html');
include('../../resources/database/config.php');
include('../includes/system_update.php');

if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];

    $sql = "SELECT * FROM summary_payment WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();


        $res2 = $result->fetch_assoc();
        var_dump($res2);
}
?>

<style>


.content {
    display: flex;
    flex-direction: res2;
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
        
        <form action="update.php?id=<?php echo $_GET['id'] ?>" method="POST">
            <input type="hidden" name="payment_id" value="<?= $res2['ID']; ?>">

            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" class="form-control" name="customer_name" value="<?= $res2['NAME']; ?>" required>
            </div>

            <div class="form-group">
                <label>Amount</label>
                <input type="number" class="form-control" name="amount" value="<?= $res2['amount_paid']; ?>" required>
            </div>

            <div class="form-group">
                <label>Payment Method</label>
                <select class="form-select" name="payment_method" required>
                    <option value="none" <?= (empty($res2['payment_type'])) ? 'selected' : ''; ?>>N/A</option>
                    <option value="credit card" <?= ($res2['payment_type'] == 'credit card') ? 'selected' : ''; ?>>Credit Card</option>
                    <option value="e-payment" <?= ($res2['payment_type'] == 'e-payment') ? 'selected' : ''; ?>>E payment</option>
                    <option value="cash" <?= ($res2['payment_type'] == 'cash') ? 'selected' : ''; ?>>Cash</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select class="form-select" name="status" required>
                    <option value="Pending" <?= ($res2['payment_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="Completed" <?= ($res2['payment_status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="Failed" <?= ($res2['payment_status'] == 'Failed') ? 'selected' : ''; ?>>Failed</option>
                </select>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-primary" name="save" value="save">Save Changes</button>
                <button onclick="history.back()" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>

    <!-- Right: Summary Section (Optional) -->
    <div class="summary-section">
        <center><h3>Payment Details</h3></center>
        <div class="summary-box">
            <p><strong>Customer:</strong> <?= $res2['NAME']; ?></p>
            <p><strong>Room:</strong> <?= $res2['room_code']; ?></p>
            <p><strong>Check In:</strong> <?= $res2['check_in']; ?></p>
            <p><strong>Check Out:</strong> <?= $res2['check_out']; ?></p>
            <p><strong>Book Status:</strong> <?= $res2['book_status']; ?></p>
            <p><strong>Amount:</strong> <?= $res2['amount_paid']; ?>php</p>
            <p><strong>Method:</strong> <?= (empty($res2['payment_type']) ? 'N/A' : $res2['payment_type']); ?></p>
            <p><strong>Status:</strong> <?= $res2['payment_status']; ?></p>
        </div>
    </div>
</div>