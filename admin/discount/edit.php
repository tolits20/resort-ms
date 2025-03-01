<?php 
include ('../includes/template.html');
include('../../resources/database/config.php');
include("../includes/system_update.php");

$sql="SELECT * FROM discount WHERE discount_id= {$_GET['id']}";
$result=mysqli_query($conn,$sql);
$row=mysqli_fetch_assoc($result);

?>


<div class="content p-4">
    <!-- Discount Form -->
    <div class="card border-0 shadow-lg p-4" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 12px;">
        <div class="card-header text-white text-center p-3" style="background: linear-gradient(135deg, #ff416c, #ff4b2b); border-radius: 10px;">
            <h4 class="mb-0 fw-bold"><i class="fas fa-percentage"></i> Add New Discount</h4>
        </div>
        <div class="card-body">
            <form>
                <div class="row g-4">
                    <!-- Discount Name -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control shadow-sm" id="discount_name" placeholder="Discount Name" value="<?php echo $row['discount_name'] ?>">
                            <label for="discount_name"><i class="fas fa-tag"></i> Discount Name</label>
                        </div>
                    </div>
                    <!-- Discount Percentage -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" class="form-control shadow-sm" id="percentage" min="1" max="100" placeholder="0-100" value="<?php echo $row['discount_percentage'] ?>">
                            <label for="percentage"><i class="fas fa-percent"></i> Percentage (%)</label>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-1">
                    <!-- Start Date -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control shadow-sm" id="start_date" value="<?php echo $row['discount_start'] ?>">
                            <label for="start_date"><i class="fas fa-calendar-alt"></i> Start Date</label>
                        </div>
                    </div>
                    <!-- End Date -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control shadow-sm" id="end_date" value="<?php echo $row['discount_end'] ?>">
                            <label for="end_date"><i class="fas fa-calendar-check" ></i> End Date</label>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-lg btn-gradient shadow fw-bold px-5 py-2">
                        <i class="fas fa-check-circle"></i> Apply Discount
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Discount Table -->
    <div class="card border-0 shadow-lg p-4 mt-4" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 12px;">
        <h4 class="text-center mb-3"><i class="fas fa-list"></i> Active Discounts</h4>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Percentage</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Summer Sale</td>
                    
