<?php
include('../../resources/database/config.php');
include ('../includes/template.html');
include("../includes/system_update.php");

$discount="SELECT * FROM discount ";
$result=mysqli_query($conn,$discount);

$track_result=mysqli_query($conn,$track_query);
$track=mysqli_fetch_assoc($track_result);
?>
<style>
    
</style>
<div class="content">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary"><i class="fas fa-tags"></i> Discount</h2>
            <button class="btn btn-success"  onclick="window.location.href='create.php'">
                <i class="fas fa-plus"></i> Add Discount
            </button>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-check-circle"></i> Active Discounts</h5>
                        <h2><?php echo $track['active_discount'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-danger shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-times-circle"></i> Expired Discounts</h5>
                        <h2><?php echo $track['expired_discount'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-clock"></i> Upcoming Discounts</h5>
                        <h2><?php echo $track['upcoming_discount'] ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Discount Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3"><i class="fas fa-list"></i> Discount List</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Discount Name</th>
                                <th>Percentage</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Room Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            <?php while($row=mysqli_fetch_assoc($result)){
                                $start = date("F d, Y h:i A", strtotime($row['discount_start']));
                                $end= date("F d, Y h:i A", strtotime($row['discount_end']));
                                $status=($row['discount_status'] == 'activate') ? 'selected' : '';
                                echo " <tr>
                                            <td>{$row['discount_name']}</td>
                                            <td>{$row['discount_percentage']}%</td>
                                            <td>$start</td>
                                            <td>$end</td>
                                            <td>{$row['applicable_room']}</td>
                                            <td><form action='update.php?id={$row['discount_id']}' method='POST'>
                                                <select class='form-select' name='status' onchange='this.form.submit()'>
                                                <option value='activate' ". ($status ? "selected" : "") .">Activate</option>
                                                <option value='deactivate' ". ($status ? "" : "selected") .">Deactivate</option>
                                                </select>
                                            </form>
                                            </td>
                                            <td>
                                                <a class='btn btn-sm btn-warning' href='edit.php?id={$row['discount_id']}'><i class='fas fa-edit'></i></a>
                                                <a class='btn btn-sm btn-danger' href='delete.php?id={$row['discount_id']}'><i class='fas fa-trash'></i></a>                                        
                                            </td>
                                        </tr>";
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Discount Modal -->
<div class="modal fade" id="addDiscountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus"></i> Add Discount</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Discount Name</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Percentage (%)</label>
                        <input type="number" class="form-control" min="1" max="100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                            <input type="date" class="form-control" required>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Discount</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
