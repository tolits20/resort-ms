<?php 
include ('../includes/template.html');
include('../../resources/database/config.php');

$sql="SELECT * FROM booking left join account using(account_id) left join user using(account_id) left join room using (room_id)";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)>0){
    $row=mysqli_fetch_assoc($result);
    // var_dump($row);
}else{
    print "no record has been retrieved";
}

?>
<style>
    
</style>
<div class="content">
    <div class="card p-4">
        <h2 class="mb-4 text-center">Edit Booking</h2>

        <form>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="editCustomerName" class="form-label">Customer Name</label>
                    <input type="text" class="form-control" id="editCustomerName" value="<?php echo $row['fname']." ".$row['lname']?>" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="editRoomType" class="form-label">Room Type</label>
                    <select class="form-select" id="editRoomType">
                        <option value="standard" <?php echo ($row['type']==='standard' ? 'selected' : '') ?> >Standard</option>
                        <option value="premium" <?php echo ($row['type']==='premium' ? 'selected' : '') ?>>Premium</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="editCheckIn" class="form-label">Room Code</label>
                    <input type="text" class="form-control" id="editCheckIn" value="<?php echo $row['room_code']?>" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="editCheckOut" class="form-label">Check-Out Date</label>
                    <input type="date" class="form-control" id="editCheckIn" value="<?php echo $row['check_in']?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="editStatus" class="form-label">Booking Status</label>
                    <select class="form-select" id="editStatus">
                        <option value="pending" <?php echo ($row['status']==='pending' ? 'selected' : '') ?>>Pending</option>
                        <option value="confirmed" <?php echo ($row['status']==='confirmed' ? 'selected' : '') ?>>Confirmed</option>
                        <option value="cancelled" <?php echo ($row['status']==='cancelled' ? 'selected' : '') ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="editPaymentStatus" class="form-label">Check Out</label>
                    <input type="date" class="form-control" id="editCheckIn" value="<?php echo $row['check_out']?>">

                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success px-4 me-2"><i class="fas fa-save"></i> Save Changes</button>
                <button type="button" class="btn btn-secondary px-4"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </form>
    </div>
</div>

