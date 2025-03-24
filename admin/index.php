<?php 
include ('../resources/database/config.php');
include('includes/page_authentication.php');
include('includes/template.php');
include("includes/system_update.php");

// Count new customers
$sql="SELECT COUNT(account_id) as 'cnew' 
      FROM account 
      INNER JOIN user USING(account_id)
      WHERE DATE(account.created_at) >= CURDATE() - INTERVAL 7 DAY";
$result=mysqli_query($conn,$sql);
$new_customer=mysqli_fetch_assoc($result);


// Count available rooms
$sql1="SELECT COUNT(DISTINCT room_id) as 'available' FROM room INNER JOIN booking USING(room_id)
    WHERE room_status = 'available' && DATE(booking.check_in) <> CURDATE()";
$result1=mysqli_query($conn,$sql1);
$available_room=mysqli_fetch_assoc($result1);

// Count pending & confirmed bookings
$sql2="SELECT COUNT(*) as 'books' FROM booking WHERE book_status='pending' OR book_status='confirmed'";
$result2=mysqli_query($conn,$sql2);
$bookings=mysqli_fetch_assoc($result2);

// Fetch notifications for the last 7 days
$_notif="SELECT 
    SUM(total_notifications) AS total_unread_notifications
FROM (
    SELECT COUNT(*) AS total_notifications
    FROM account_notification
    WHERE  is_read = 1

    UNION ALL

    SELECT COUNT(*) AS total_notifications
    FROM room_notification
    WHERE  is_read = 1

    UNION ALL

    SELECT COUNT(tasks.title) AS total_notifications
    FROM task_notifications
    INNER JOIN tasks ON tasks.id = task_notifications.task_id
    WHERE  task_notifications.is_read = 1

    UNION ALL

    SELECT COUNT(*) AS total_notifications
    FROM booking_notification
    INNER JOIN booking ON booking_notification.book_id = booking.book_id
    WHERE booking_notification.is_read = 1
) AS notification_counts;";
$notif=mysqli_query($conn,$_notif);
$notif_count=mysqli_fetch_assoc($notif);
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
            <a href="customer/index.php" class="card-link" aria-label="View customers"></a>
        </div>

        <div class="card card-rooms" onclick="openModal('rooms-modal')">
            <i class="fas fa-bed"></i>
            <h3>Rooms Available</h3>
            <p><?php echo (empty($available_room['available']) ? 0 : $available_room['available']) ?></p>
            <a href="rooms/index.php" class="card-link" aria-label="View rooms"></a>
        </div>

        <div class="card card-bookings" onclick="openModal('bookings-modal')">
            <i class="fas fa-calendar-check"></i>
            <h3>Bookings</h3>
            <p><?php echo (empty($bookings['books']) ? 0 : $bookings['books']) ?></p>
            <a href="booking/index.php" class="card-link" aria-label="View bookings"></a>
        </div>

        <div class="card card-notifications" onclick="openModal('notifications-modal')">
            <i class="fas fa-bell"></i>
            <h3>Notifications</h3>
            <p><?php echo $notif_count['total_unread_notifications'] ?></p>
            <a href="notification.php" class="card-link" aria-label="View notifications"></a>
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
        </div>
    </div>

    <!-- Notifications Modal -->
    <div id="notifications-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Notifications</h2>
                <span class="modal-close" onclick="closeModal('notifications-modal')">&times;</span>
            </div>
            <div class="modal-body">
                <?php while ($notification = mysqli_fetch_assoc($notif)): ?>
                    <div class="notification-item <?php echo $notification['indicator']; ?>">
                        <i class="fas fa-info-circle"></i>
                        <span><?php echo $notification['name']; ?> - <?php echo $notification['status']; ?></span>
                        <small class="text-muted"><?php echo date('F j, Y, g:i a', strtotime($notification['Date'])); ?></small>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('notifications-modal')">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
</script>