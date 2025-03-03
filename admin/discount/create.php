<?php 
include('../../resources/database/config.php');
include ('../includes/template.html');
include("../includes/system_update.php");
$minDateTime = date('Y-m-d\TH:i');
?>

<div class="content p-4">
    <div class="card border-0 shadow-lg p-4" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 12px;">
        <div class="card-header text-white text-center p-3" style="background: linear-gradient(135deg, #ff416c, #ff4b2b); border-radius: 10px;">
            <h4 class="mb-0 fw-bold"><i class="fas fa-percentage"></i> Add New Discount</h4>
        </div>
        <div class="card-body">
            <form action="store.php" method="POST">
                <div class="row g-4">
                    <!-- Discount Name -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control shadow-sm" name="discount_name" id="discount_name" placeholder="Discount Name" required>
                            <label for="discount_name"><i class="fas fa-tag"></i> Discount Name</label>
                        </div>
                    </div>
                    <!-- Discount Percentage -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" class="form-control shadow-sm" name="percentage" id="percentage" min="1" max="100" placeholder="0-100" required>
                            <label for="percentage"><i class="fas fa-percent"></i> Percentage (%)</label>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-1">
                    <!-- Start Date -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control shadow-sm" name="start_date" id="start_date" min="<?php echo $minDateTime ?>" required>
                            <label for="start_date"><i class="fas fa-calendar-alt"></i> Start Date</label>
                        </div>
                    </div>
                    <!-- End Date -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control shadow-sm" name="end_date" id="end_date" min="<?php echo $minDateTime ?>" required>
                            <label for="end_date"><i class="fas fa-calendar-check"></i> End Date</label>
                        </div>
                    </div>
                </div>

                <!-- Applicable Room -->
                <div class="row g-4 mt-1">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select shadow-sm" name="applicable_room" id="applicable_room" required>
                                <option value="all" >All Rooms</option>
                                <option value="deluxe">Deluxe</option>
                                <option value="standard">Standard</option>
                                <option value="premium">Premium</option>
                            </select>
                            <label for="applicable_room"><i class="fas fa-bed"></i> Applicable Room</label>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-lg btn-gradient shadow fw-bold px-5 py-2" name="create">
                        <i class="fas fa-check-circle"></i> Apply Discount
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Button Gradient */
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

/* Card Styling */
.card {
    border-radius: 12px;
}

/* Floating Labels */
.form-floating input, .form-floating select {
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.form-floating label {
    color: #555;
}
</style>
