<?php 
include ('../resources/database/config.php');
include('includes/template.html');

$sql="SELECT COUNT(account_id) as 'cnew' FROM account INNER JOIN user USING(account_id)
    WHERE DATE(account.created_at) >= CURDATE() - INTERVAL 7 DAY";
$result=mysqli_query($conn,$sql);
$new_customer=mysqli_fetch_assoc($result);

$sql1="SELECT COUNT(room_id) as 'available' FROM room WHERE room_status = 'available'";
$result1=mysqli_query($conn,$sql1);
$available_room=mysqli_fetch_assoc($result1);

$sql2="SELECT COUNT(*) as 'books' FROM booking WHERE DATE(created_at) = CURDATE()";
$result2=mysqli_query($conn,$sql2);
$bookings=mysqli_fetch_assoc($result2);

$customer_notif="SELECT concat(user.fname,' ',user.lname) AS 'name',account_notification.account_notification as 'status',
                 account.created_at,account.updated_at FROM
                account Inner JOIN user using(account_id) Inner JOIN account_notification USING(account_id) ORDER BY account_notification.account_notification DESC";
$ctmr_notif=mysqli_query($conn,$customer_notif);

$ctmr_count_notif="SELECT COUNT(*) as 'c_count' FROM account_notification";
$c_count=mysqli_query($conn,$ctmr_count_notif);
$c_final_count=mysqli_fetch_assoc($c_count);

?>
<style>
/* Style for the content div only */
.content {
    background: #f4f6f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    max-width: 100%;
}

/* Dashboard grid layout */
.dashboard-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    padding: 20px;
}

/* Statistic Cards */
.card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0px 7px 20px rgba(0, 0, 0, 0.15);
}

.card i {
    font-size: 30px;
    margin-bottom: 10px;
    color: #007bff;
}

.card h3 {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}

.card p {
    font-size: 16px;
    font-weight: 600;
    color: #555;
}

/* Notifications Section */
.notifications {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

.notifications h3 {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    margin-bottom: 15px;
}

.notification-item {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    font-size: 14px;
    color: #555;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item i {
    margin-right: 8px;
    color: #007bff;
}
</style>

<div class="content">
    <!-- Dashboard Cards -->
    <div class="dashboard-container">
        <div class="card">
            <i class="fas fa-user"></i>
            <h3>New Customers</h3>
            <p><?php echo $new_customer['cnew'] ?></p>
        </div>

        <div class="card">
            <i class="fas fa-bed"></i>
            <h3>Rooms Available</h3>
            <p><?php echo (empty($available_room['available']) ? 0 : $available_room['available'] ) ?></p>
        </div>

        <div class="card">
            <i class="fas fa-calendar-check"></i>
            <h3>Bookings</h3>
            <p><?php echo (empty($bookings['books']) ? 0 : $bookings['books'] ) ?></p>
        </div>

        <div class="card">
            <i class="fas fa-bell"></i>
            <h3>Notifications</h3>
            <p><?php echo $c_final_count['c_count'] ?></p>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="notifications">
        <h3><i class="fas fa-bell"></i> Notifications</h3>
       <?php while ($cnotif =mysqli_fetch_assoc($ctmr_notif)){
            $to_print=($cnotif['status']=='create' ?  $cnotif['created_at'].': New user <strong>['.$cnotif['name'].']</strong> has been created! ' :
                 $cnotif['updated_at'].': <strong>'.$cnotif['name'].'\'s</strong> Account has been updated successfully!');
            echo "<div class='notification-item'>
            <i class='fas fa-exclamation-triangle'></i> {$to_print}
        </div>";
        
        }?>
    </div>
</div>
 <!-- <div class="notification-item">
            <i class="fas fa-exclamation-triangle"></i> Payment pending for Booking #321.
        </div> -->