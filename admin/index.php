<?php 
include ('../resources/database/config.php');
include('includes/page_authentication.php');
include('includes/template.php');
include("includes/system_update.php");

$sql="SELECT COUNT(account_id) as 'cnew' 
      FROM account 
      INNER JOIN user USING(account_id)
      WHERE DATE(account.created_at) >= CURDATE() - INTERVAL 7 DAY";
$result=mysqli_query($conn,$sql);
$new_customer=mysqli_fetch_assoc($result);

$ctmr_count_notif="SELECT 
    (SELECT COUNT(*) 
     FROM account
     INNER JOIN user USING(account_id)
     INNER JOIN account_notification USING(account_id)
     WHERE DATE(account_notification.Date) >= CURDATE() - INTERVAL 2 DAY
    ) 
    + 
    (SELECT COUNT(*) 
     FROM room
     INNER JOIN room_notification USING(room_id)
     WHERE DATE(room_notification.Date) >= CURDATE() - INTERVAL 2 DAY
    ) +
    (SELECT COUNT(*)
     FROM booking_notification
     WHERE DATE(booking_notification.Date) >= CURDATE() - INTERVAL 2 DAY
    ) 
AS total_count";
$c_count=mysqli_query($conn,$ctmr_count_notif);
$c_final_count=mysqli_fetch_assoc($c_count);

// Count available rooms
$sql1="SELECT COUNT(room_id) as 'available' FROM room INNER JOIN booking USING(room_id)
    WHERE room_status = 'available' && booking.check_in <> NOW()";
$result1=mysqli_query($conn,$sql1);
$available_room=mysqli_fetch_assoc($result1);

// Count pending & confirmed bookings
$sql2="SELECT COUNT(*) as 'books' FROM booking WHERE book_status='pending' OR book_status='confirmed'";
$result2=mysqli_query($conn,$sql2);
$bookings=mysqli_fetch_assoc($result2);

// Fetch notifications for the last 7 days
$_notif="SELECT 
    'customer' as indicator, 
    CONCAT(user.fname, ' ', user.lname) AS name,
    account_notification.account_notification AS status,
    account.created_at as c_created,
    account.updated_at as c_updated,
    account_notification.Date 
FROM account
INNER JOIN user USING(account_id)
INNER JOIN account_notification USING(account_id)
WHERE DATE(account_notification.Date) >= CURDATE() - INTERVAL 2 DAY

UNION

SELECT 
    'room' as indicator, 	
    room.room_code AS name, 
    room_notification.room_notification AS status,
    created_at as r_created,
    updated_at as r_updated,
    room_notification.Date 
FROM room
INNER JOIN room_notification USING(room_id)
WHERE DATE(room_notification.Date) >= CURDATE() - INTERVAL 2 DAY

UNION 

SELECT
    'book' as indicator, 
    CONCAT(user.fname, ' ', user.lname) AS name,
    booking_notification.booking_status,
    booking.check_in,
    booking.check_out,
    booking.created_at 
FROM account 
INNER JOIN user USING(account_id) 
INNER JOIN booking USING(account_id)
INNER JOIN booking_notification USING(book_id)
WHERE DATE(booking_notification.Date) >= CURDATE() - INTERVAL 2 DAY   
ORDER BY `Date` DESC";
$notif=mysqli_query($conn,$_notif);

?>
<style>
.content {
    background: #f4f6f9;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
    max-width: 100%;
}

.dashboard-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    padding: 20px;
}

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
    <?php include('alert.php'); ?>
    <div class="dashboard-container">
        <div class="card">
            <i class="fas fa-user"></i>
            <h3>New Customers</h3>
            <p><?php echo $new_customer['cnew'] ?></p>
        </div>

        <div class="card">
            <i class="fas fa-bed"></i>
            <h3>Rooms Available</h3>
            <p><?php echo (empty($available_room['available']) ? 0 : $available_room['available']) ?></p>
        </div>

        <div class="card">
            <i class="fas fa-calendar-check"></i>
            <h3>Bookings</h3>
            <p><?php echo (empty($bookings['books']) ? 0 : $bookings['books']) ?></p>
        </div>

        <div class="card">
            <i class="fas fa-bell"></i>
            <h3>Notifications</h3>
            <p><?php echo $c_final_count['total_count'] ?></p>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="notifications">
        <h3><i class="fas fa-bell"></i> Notifications</h3>
       <?php
       while ($inotif=mysqli_fetch_assoc($notif)){
           if($inotif['indicator']=='customer'){
               $to_print = ($inotif['status']=='create') ? 
                   "{$inotif['c_created']}: New user <strong>[{$inotif['name']}]</strong> has been created!" : 
                   "{$inotif['c_updated']}: <strong>{$inotif['name']}'s</strong> Account has been updated successfully!";
           } elseif ($inotif['indicator']=='room') {
               $to_print = ($inotif['status']=='create') ? 
                   "{$inotif['Date']}: Room <strong>{$inotif['name']}</strong> has been added to the system." : 
                   "{$inotif['Date']}: Room <strong>{$inotif['name']}</strong> was updated successfully.";
           } elseif ($inotif['indicator']=='book') {
               $to_print = "{$inotif['Date']}: <strong>{$inotif['name']}</strong> has a <strong>{$inotif['status']}</strong> booking.";
           }
           echo "<div class='notification-item'><i class='fas fa-bell'></i> {$to_print}</div>";
       }
       ?>
    </div>
</div>
