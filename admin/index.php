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
    :root {
        --primary-color: #4a90e2;
        --secondary-color: #f4f6f9;
        --accent-color: #2ecc71;
        --warning-color: #f39c12;
        --danger-color: #e74c3c;
        --text-color: #333;
        --text-light: #777;
        --card-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
        --border-radius: 12px;
    }

    .content {
        background: var(--secondary-color);
        padding: 25px;
        border-radius: var(--border-radius);
        max-width: 100%;
        transition: var(--transition);
    }

    .dashboard-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .card {
        background: white;
        padding: 20px;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        text-align: center;
        transition: var(--transition);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        border-left: 4px solid var(--primary-color);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .card-customers {
        border-left-color: var(--accent-color);
    }

    .card-rooms {
        border-left-color: var(--warning-color);
    }

    .card-bookings {
        border-left-color: var(--danger-color);
    }

    .card-notifications {
        border-left-color: #9b59b6;
    }

    .card i {
        font-size: 36px;
        margin-bottom: 15px;
        color: var(--primary-color);
    }

    .card-customers i {
        color: var(--accent-color);
    }

    .card-rooms i {
        color: var(--warning-color);
    }

    .card-bookings i {
        color: var(--danger-color);
    }

    .card-notifications i {
        color: #9b59b6;
    }

    .card h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 10px;
    }

    .card p {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 0;
    }

    .card-link {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    .notifications {
        background: white;
        padding: 20px;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        margin-top: 20px;
    }

    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .notifications h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 0;
    }

    .notifications h3 i {
        margin-right: 10px;
        color: #9b59b6;
    }

    .notification-item {
        padding: 12px;
        margin-bottom: 8px;
        border-radius: 8px;
        background-color: #f8f9fa;
        font-size: 14px;
        color: var(--text-color);
        transition: var(--transition);
    }

    .notification-item:hover {
        background-color: #f0f0f0;
    }

    .notification-item i {
        margin-right: 10px;
    }

    .notification-item.customer i {
        color: var(--accent-color);
    }

    .notification-item.room i {
        color: var(--warning-color);
    }

    .notification-item.book i {
        color: var(--danger-color);
    }

    .shortcuts {
        background: white;
        padding: 20px;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        margin-top: 20px;
    }

    .shortcuts h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .shortcuts h3 i {
        margin-right: 10px;
        color: var(--primary-color);
    }

    .shortcuts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }

    .shortcut-item {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        transition: var(--transition);
        cursor: pointer;
    }

    .shortcut-item:hover {
        background-color: var(--primary-color);
        transform: translateY(-3px);
    }

    .shortcut-item:hover i, .shortcut-item:hover p {
        color: white;
    }

    .shortcut-item i {
        font-size: 24px;
        color: var(--primary-color);
        margin-bottom: 8px;
        transition: var(--transition);
    }

    .shortcut-item p {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-color);
        margin-bottom: 0;
        transition: var(--transition);
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 20px;
        border-radius: var(--border-radius);
        width: 70%;
        max-width: 800px;
        position: relative;
        animation: modalFadeIn 0.3s;
    }

    @keyframes modalFadeIn {
        from {opacity: 0; transform: translateY(-30px);}
        to {opacity: 1; transform: translateY(0);}
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .modal-header h2 {
        font-size: 20px;
        font-weight: 600;
        color: var(--text-color);
        margin: 0;
    }

    .modal-close {
        font-size: 22px;
        color: var(--text-light);
        cursor: pointer;
        transition: var(--transition);
    }

    .modal-close:hover {
        color: var(--danger-color);
    }

    .modal-body {
        max-height: 60vh;
        overflow-y: auto;
    }

    .modal-footer {
        padding-top: 15px;
        margin-top: 15px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
    }

    .modal-footer button {
        padding: 8px 15px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
        margin-left: 10px;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #3a7bbf;
    }

    .btn-secondary {
        background-color: #f0f0f0;
        color: var(--text-color);
        border: 1px solid #ddd;
    }

    .btn-secondary:hover {
        background-color: #e0e0e0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dashboard-container {
            grid-template-columns: 1fr;
        }
        
        .shortcuts-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .modal-content {
            width: 90%;
            margin: 20% auto;
        }
    }
</style>

<div class="content">
    <?php include('alert.php'); ?>
    <div class="dashboard-container">
        <div class="card card-customers" onclick="openModal('customers-modal')">
            <i class="fas fa-user"></i>
            <h3>New Customers</h3>
            <p><?php echo $new_customer['cnew'] ?></p>
            <a href="customers.php" class="card-link" aria-label="View customers"></a>
        </div>

        <div class="card card-rooms" onclick="openModal('rooms-modal')">
            <i class="fas fa-bed"></i>
            <h3>Rooms Available</h3>
            <p><?php echo (empty($available_room['available']) ? 0 : $available_room['available']) ?></p>
            <a href="rooms.php" class="card-link" aria-label="View rooms"></a>
        </div>

        <div class="card card-bookings" onclick="openModal('bookings-modal')">
            <i class="fas fa-calendar-check"></i>
            <h3>Bookings</h3>
            <p><?php echo (empty($bookings['books']) ? 0 : $bookings['books']) ?></p>
            <a href="bookings.php" class="card-link" aria-label="View bookings"></a>
        </div>

        <div class="card card-notifications" onclick="openModal('notifications-modal')">
            <i class="fas fa-bell"></i>
            <h3>Notifications</h3>
            <p><?php echo $c_final_count['total_count'] ?></p>
            <a href="notifications.php" class="card-link" aria-label="View notifications"></a>
        </div>
    </div>

    <!-- Shortcuts Section -->
    <div class="shortcuts">
        <h3><i class="fas fa-th"></i> Quick Actions</h3>
        <div class="shortcuts-grid">
            <a href="customer/create.php" class="shortcut-item">
                <i class="fas fa-user-plus"></i>
                <p>Add User</p>
            </a>
            <a href="rooms/create.php" class="shortcut-item">
                <i class="fas fa-plus-circle"></i>
                <p>Add Room</p>
            </a>
            <a href="create_booking.php" class="shortcut-item">
                <i class="fas fa-tags"></i>
                <p>New Discount</p>
            </a>
            <a href="reports/index.php" class="shortcut-item">
                <i class="fas fa-chart-bar"></i>
                <p>Reports</p>
            </a>
            <a href="activity_logs/index.php" class="shortcut-item">
                <i class="fas fa-trash"></i>
                <p>Recently Deleted</p>
            </a>