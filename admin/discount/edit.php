<?php 
include ('../includes/template.html');
include('../../resources/database/config.php');
include("../includes/system_update.php");

$discount_id = $_GET['id'];


$sql = "SELECT * FROM discount WHERE discount_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $discount_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);




?>

<div class="content p-4">
    <div class="card border-0 shadow-lg p-4" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 12px;">
        <div class="card-header text-white text-center p-3" style="background: linear-gradient(135deg, #ff416c, #ff4b2b); border-radius: 10px;">
            <h4 class="mb-0 fw-bold"><i class="fas fa-edit"></i> Update Discount</h4>
        </div>
        <div class="card-body">
            <form action="update.php" method="POST">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control shadow-sm" name="discount_name" value="<?= htmlspecialchars($row['discount_name']) ?>" required>
                            <label><i class="fas fa-tag"></i> Discount Name</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" class="form-control shadow-sm" name="percentage" min="1" max="100" value="<?= $row['discount_percentage'] ?>" required>
                            <label><i class="fas fa-percent"></i> Percentage (%)</label>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-1">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control shadow-sm" name="start_date" 
                                   value="<?= date('Y-m-d\TH:i', strtotime($row['discount_start'])) ?>" required>
                            <label><i class="fas fa-calendar-alt"></i> Start Date</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control shadow-sm" name="end_date" 
                                   value="<?= date('Y-m-d\TH:i', strtotime($row['discount_end'])) ?>" required>
                            <label><i class="fas fa-calendar-check"></i> End Date</label>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-1">
                    <div class="col-md-12">
                        <div class="form-floating">
                            <select class="form-select shadow-sm" name="applicable_room" required>
                                <option value="standard" <?= ($row['applicable_room'] == 'standard') ? 'selected' : '' ?>>Standard Room</option>
                                <option value="deluxe" <?= ($row['applicable_room'] == 'deluxe') ? 'selected' : '' ?>>Deluxe Room</option>
                                <option value="suite" <?= ($row['applicable_room'] == 'suite') ? 'selected' : '' ?>>Suite</option>
                            </select>
                            <label><i class="fas fa-bed"></i> Applicable Room</label>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" name="update" class="btn btn-lg btn-gradient shadow fw-bold px-5 py-2" value="<?php echo $discount_id ?>">
                        <i class="fas fa-save"></i> Update Discount
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.btn-gradient {
    background: linear-gradient(135deg, #ff416c, #ff4b2b);
    color: white;
    border: none;
    transition: 0.3s;
    border-radius: 50px;
}

.btn-gradient:hover {
    background: linear-gradient(135deg, #ff4b2b, #ff416c);
    transform: scale(1.05);
}
</style>
