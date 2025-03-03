<?php 
include('../../resources/database/config.php');
include("../includes/system_update.php");
// var_dump($_SESSION);
$id=$_GET['id'];
$sql="";
if($_SESSION['identifier']=="user" || empty($_SESSION['identifier'])){
    $sql="SELECT * FROM booking left join account using(account_id) left join user using(account_id) left join room using (room_id)  
    WHERE booking.book_id= {$id}";
}elseif($_SESSION['identifier']=="guest"){
    $sql="SELECT * FROM booking left join account using(account_id) left join guest using(guest_id) left join room using (room_id) 
    WHERE booking.book_id= {$id}";
}
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)>0){
    $row=mysqli_fetch_assoc($result);
}else{
    print "no record has been retrieved";
}

?>
<style>
    
</style>
<div class="content">
    <div class="card p-4">
        <h2 class="mb-4 text-center">Edit Booking</h2>

        <form action="update.php" method="post" class="form-control">
            <input type="hidden" value="<?php echo $row['book_id'] ?>" name="id">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="editCustomerName" class="form-label">Customer Name</label>
                    <input type="text" class="form-control" id="editCustomerName" value="<?php echo $row['fname']." ".$row['lname']?>" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="editRoomType" class="form-label">Room Type</label>
                    <select class="form-select" id="editRoomType" name="type" disabled>
                        <option value="standard" <?php echo ($row['room_type']==='standard' ? 'selected' : '') ?> >Standard</option>
                        <option value="premium" <?php echo ($row['room_type']==='premium' ? 'selected' : '') ?>>Premium</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="editCheckIn" class="form-label">Room Code</label>
                    <input type="text" class="form-control" id="editCheckIn" value="<?php echo $row['room_code']?>" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="editCheckOut" class="form-label">Check-In Date</label>
                    <input type="datetime-local" class="form-control" name="check_in" id="editCheckIn" value="<?php echo $row['check_in']?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="editStatus" class="form-label">Booking Status</label>
                    <select class="form-select" name="status" id="editStatus">
                        <option value="pending" <?php echo ($row['book_status']=='pending' ? 'selected' : '') ?>>Pending</option>
                        <option value="confirmed" <?php echo ($row['book_status']=='confirmed' ? 'selected' : '') ?>>Confirmed</option>
                        <option value="cancelled" <?php echo ($row['book_status']=='cancelled' ? 'selected' : '') ?>>Cancelled</option>
                        <option value="completed" <?php echo ($row['book_status']=='completed' ? 'selected' : '') ?>>Completed</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="editPaymentStatus" class="form-label">Check-Out Date</label>
                    <input type="datetime-local" class="form-control" name="check_out" id="editCheckIn"  value="<?php echo $row['check_out']?>">

                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" name="save" class="btn btn-success px-4 me-2"><i class="fas fa-save"></i> Save Changes</button>
                <a type="button" href="index.php" class="btn btn-secondary px-4"><i class="fas fa-times"></i> Cancel</a>
            </div>
        </form>
    </div>
</div>

