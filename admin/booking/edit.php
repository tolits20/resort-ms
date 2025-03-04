<?php 
include('../../resources/database/config.php');
include ('../includes/template.html');
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
<div class="content">
    <div class="booking-edit-container">
        <div class="booking-edit-header">
            <h1>Modify Booking Details</h1>
            <p>Review and update your booking information</p>
        </div>

        <form action="update.php" method="post" class="booking-edit-form">
            <input type="hidden" value="<?php echo $row['book_id'] ?>" name="id">
            
            <div class="booking-info-section">
                <div class="info-card customer-details">
                    <div class="card-header">
                        <i class="fas fa-user"></i>
                        <h3>Customer Information</h3>
                    </div>
                    <div class="card-content">
                        <div class="detail-row">
                            <label>Name</label>
                            <input type="text" value="<?php echo $row['fname']." ".$row['lname']?>" disabled>
                        </div>
                    </div>
                </div>

                <div class="info-card room-details">
                    <div class="card-header">
                        <i class="fas fa-bed"></i>
                        <h3>Room Information</h3>
                    </div>
                    <div class="card-content">
                        <div class="detail-row">
                            <label>Room Type</label>
                            <select name="type" disabled>
                                <option value="standard" <?php echo ($row['room_type']==='standard' ? 'selected' : '') ?>>Standard</option>
                                <option value="premium" <?php echo ($row['room_type']==='premium' ? 'selected' : '') ?>>Premium</option>
                            </select>
                        </div>
                        <div class="detail-row">
                            <label>Room Code</label>
                            <input type="text" value="<?php echo $row['room_code']?>" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-modification-section">
                <div class="modification-card">
                    <div class="card-header">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>Booking Dates</h3>
                    </div>
                    <div class="card-content">
                        <div class="date-row">
                            <label for="editCheckIn">Check-In Date</label>
                            <input type="datetime-local" name="check_in" id="editCheckIn" value="<?php echo $row['check_in']?>">
                        </div>
                        <div class="date-row">
                            <label for="editCheckOut">Check-Out Date</label>
                            <input type="datetime-local" name="check_out" id="editCheckOut" value="<?php echo $row['check_out']?>">
                        </div>
                    </div>
                </div>

                <div class="modification-card">
                    <div class="card-header">
                        <i class="fas fa-tags"></i>
                        <h3>Booking Status</h3>
                    </div>
                    <div class="card-content">
                        <div class="status-row">
                            <label for="editStatus">Booking Status</label>
                            <select name="status" id="editStatus">
                                <option value="pending" <?php echo ($row['book_status']=='pending' ? 'selected' : '') ?>>Pending</option>
                                <option value="confirmed" <?php echo ($row['book_status']=='confirmed' ? 'selected' : '') ?>>Confirmed</option>
                                <option value="cancelled" <?php echo ($row['book_status']=='cancelled' ? 'selected' : '') ?>>Cancelled</option>
                                <option value="completed" <?php echo ($row['book_status']=='completed' ? 'selected' : '') ?>>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <button type="submit" name="save" class="btn-save">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="index.php" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
:root {
    --primary-color: #3a7bd5;
    --secondary-color: #f4f7f6;
    --text-color: #333;
    --card-bg: white;
    --border-color: #e0e4e7;
}

.content {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-color: var(--secondary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.booking-edit-container {
    background-color: var(--card-bg);
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    max-width: 900px;
    width: 100%;
    padding: 40px;
    color: #333333;
    transition: all 0.3s ease;
}

.booking-edit-header {
    text-align: center;
    margin-bottom: 30px;
}

.booking-edit-header h1 {
    color: var(--primary-color);
    margin-bottom: 10px;
    font-weight: 600;
}

.booking-edit-header p {
    color: #666;
}

.booking-info-section {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}

.info-card, .modification-card {
    flex: 1;
    background-color: var(--secondary-color);
    border-radius: 12px;
    padding: 20px;
}

.card-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    border-bottom: 2px solid var(--border-color);
    padding-bottom: 10px;
}

.card-header i {
    color: var(--primary-color);
    margin-right: 10px;
    font-size: 20px;
}

.detail-row, .date-row, .status-row {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
}

.detail-row label, .date-row label, .status-row label {
    margin-bottom: 5px;
    color: #555;
    font-weight: 500;
}

.detail-row input, .detail-row select,
.date-row input, .date-row select,
.status-row select {
    padding: 10px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background-color: white;
}

.detail-row input:disabled {
    background-color: #f9f9f9;
    cursor: not-allowed;
}

.booking-modification-section {
    display: flex;
    gap: 20px;
}

.action-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 30px;
}

.btn-save, .btn-cancel {
    padding: 12px 25px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
}

.btn-save {
    background-color: var(--primary-color);
    color: white;
}

.btn-save:hover {
    background-color: #2c5fc4;
}

.btn-cancel {
    background-color: #f1f3f4;
    color: var(--text-color);
}

.btn-cancel:hover {
    background-color: #e1e3e4;
}

@media (max-width: 768px) {
    .booking-info-section, 
    .booking-modification-section {
        flex-direction: column;
    }

    .booking-edit-container {
        padding: 20px;
    }
}
</style>