<?php
include("../resources/database/config.php");

// Handle marking notifications as read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['type'])) {
    $id = intval($_POST['id']);
    $type = $_POST['type'];

    $table = '';
    $id_column = '';

    switch ($type) {
        case 'account':
            $table = 'account_notification';
            $id_column = 'account_id';
            break;
        case 'room':
            $table = 'room_notification';
            $id_column = 'room_id';
            break;
        case 'booking':
            $table = 'booking_notification';
            $id_column = 'book_id';
            break;
        case 'tasks':
            $table = 'task_notifications';
            $id_column = 'task_id';
            break;
        default:
            echo 'error';
            exit;
    }

    $update_sql = "UPDATE $table SET is_read = 1 WHERE $id_column = ?";
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo 'success';
    } else {
        echo 'error';
    }
    mysqli_stmt_close($stmt);
    exit; // Stop execution after AJAX response
}

include("includes/template.php");

$admin_id = $_SESSION['ID']; // Assuming admin ID is stored in session

// Fetch notifications
$notifications_sql = "
    SELECT 'account' AS type, account_id AS id, message, is_read, Date AS created_at FROM account_notification 
    UNION ALL
    SELECT 'room' AS type, room_id AS id, message, is_read, Date AS created_at FROM room_notification
    UNION ALL
    SELECT 'booking' AS type, book_id AS id, message, is_read, Date AS created_at FROM booking_notification
    UNION ALL
    SELECT 'tasks' AS type, task_id AS id, CONCAT('Task:', tasks.title, ' completed') AS message, is_read, tasks.created_at 
    FROM task_notifications 
    INNER JOIN tasks ON task_notifications.task_id = tasks.id
    WHERE tasks.status='completed' 
    GROUP BY tasks.id
    ORDER BY created_at DESC
";
$notifications_result = mysqli_query($conn, $notifications_sql);
?>

<div id="content" class="content mt-5">
    <!-- Notification Center -->
    <div class="notification-center mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-primary mb-0">Notifications</h4>
            <button id="markAllAsRead" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                <i class="far fa-check-circle me-1"></i> Mark all as read
            </button>
        </div>
        
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white p-3 border-bottom-0">
                <ul class="nav nav-pills nav-filters">
                    <li class="nav-item">
                        <a class="nav-link active px-3 py-1" href="#" data-filter="all">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-1" href="#" data-filter="account">Accounts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-1" href="#" data-filter="room">Rooms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-1" href="#" data-filter="booking">Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-1" href="#" data-filter="tasks">Tasks</a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-0">
                <?php while ($notification = mysqli_fetch_assoc($notifications_result)): 
                    $directory = "#";
                    if ($notification['type'] == 'account') {
                        $directory = "/resort-ms/admin/customer/edit.php?id=" . $notification['id'];
                    } elseif ($notification['type'] == 'room') {
                        $directory = "/resort-ms/admin/rooms/edit.php?id=" . $notification['id'];
                    } elseif ($notification['type'] == 'booking') {
                        $directory = "/resort-ms/admin/booking/edit.php?id=" . $notification['id'];
                    } elseif ($notification['type'] == 'tasks') {
                        $directory = "/resort-ms/admin/task/task_assigned_list.php";
                    }
                    
                    $type_icon = 'far fa-bell';
                    $type_class = '';
                    
                    switch($notification['type']) {
                        case 'account':
                            $type_icon = 'far fa-user';
                            $type_class = 'type-account';
                            break;
                        case 'room':
                            $type_icon = 'far fa-building';
                            $type_class = 'type-room';
                            break;
                        case 'booking':
                            $type_icon = 'far fa-calendar-check';
                            $type_class = 'type-booking';
                            break;
                        case 'tasks':
                            $type_icon = 'far fa-check-square';
                            $type_class = 'type-task';
                            break;
                    }
                ?>
                    
                <div class="content">
                <div class="notification-item <?php echo $notification['is_read'] == 0 ? 'unread' : ''; ?>" data-type="<?php echo $notification['type']; ?>">
                    <div class="notification-container">
                        <?php if ($notification['is_read'] == 0): ?>
                            <div class="unread-indicator"></div>
                        <?php endif; ?>
                        
                        <div class="notification-icon <?php echo $type_class; ?>">
                            <i class="<?php echo $type_icon; ?>"></i>
                        </div>
                        
                        <div class="notification-content">
                            <div class="d-flex align-items-center mb-1">
                                <span class="notification-badge <?php echo $type_class; ?>"><?php echo ucfirst($notification['type']); ?></span>
                                <small class="notification-time ms-auto"><?php echo date_format(new DateTime($notification['created_at']), 'F j, Y, g:i a'); ?></small>
                            </div>
                            
                            <h6 class="notification-text mb-2"><?php echo htmlspecialchars($notification['message']); ?></h6>
                            
                            <div class="notification-actions">
                                <a href="<?php echo $directory ?>" class="btn btn-action view-details">
                                    <i class="far fa-eye me-1"></i> View Details
                                </a>

                                <?php if ($notification['is_read'] == 0): ?>
                                <button type="button" class="btn btn-action mark-as-read" 
                                        data-id="<?php echo $notification['id']; ?>" 
                                        data-type="<?php echo $notification['type']; ?>">
                                    <i class="far fa-check-circle me-1"></i> Mark as Read
                                </button>
                                <?php else: ?>
                                <button type="button" class="btn btn-action read-status" disabled>
                                    <i class="fas fa-check-circle me-1"></i> Read
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <?php endwhile; ?>
                
                <div class="empty-state d-none">
                    <div class="text-center py-5">
                        <i class="far fa-bell-slash empty-icon mb-3"></i>
                        <h6>No notifications</h6>
                        <p class="text-muted small">You're all caught up!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        /* Enhanced Notification Styles */

        .content{
            padding: 20px; 
            background-color: #fff;
        }
        .nav-filters {
            gap: 0.5rem;
        }
        
        .nav-filters .nav-link {
            border-radius: 20px;
            font-size: 0.85rem;
            color: #6c757d;
            transition: all 0.2s ease;
        }
        
        .nav-filters .nav-link.active {
            background-color: #4784f7;
            color: white;
            box-shadow: 0 2px 6px rgba(71, 132, 247, 0.3);
        }
        
        .notification-item {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            position: relative;
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-item:hover {
            background-color: rgba(0, 0, 0, 0.01);
        }
        
        .notification-item.unread {
            background-color: rgba(71, 132, 247, 0.03);
        }
        
        .notification-container {
            padding: 1.25rem;
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .notification-icon {
            flex-shrink: 0;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #6c757d;
        }
        
        .notification-icon.type-account {
            background-color: rgba(114, 124, 245, 0.1);
            color: #727cf5;
        }
        
        .notification-icon.type-room {
            background-color: rgba(241, 180, 76, 0.1);
            color: #f1b44c;
        }
        
        .notification-icon.type-booking {
            background-color: rgba(10, 207, 151, 0.1);
            color: #0acf97;
        }
        
        .notification-icon.type-task {
            background-color: rgba(250, 92, 124, 0.1);
            color: #fa5c7c;
        }
        
        .notification-content {
            flex-grow: 1;
        }
        
        .notification-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.75rem;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #f8f9fa;
            color: #6c757d;
        }
        
        .notification-badge.type-account {
            background-color: rgba(114, 124, 245, 0.1);
            color: #727cf5;
        }
        
        .notification-badge.type-room {
            background-color: rgba(241, 180, 76, 0.1);
            color: #f1b44c;
        }
        
        .notification-badge.type-booking {
            background-color: rgba(10, 207, 151, 0.1);
            color: #0acf97;
        }
        
        .notification-badge.type-task {
            background-color: rgba(250, 92, 124, 0.1);
            color: #fa5c7c;
        }
        
        .notification-time {
            font-size: 0.75rem;
            color: #98a6ad;
        }
        
        .notification-text {
            font-size: 0.9rem;
            color: #343a40;
            font-weight: 500;
            line-height: 1.4;
        }
        
        .notification-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 0.75rem;
        }
        
        .btn-action {
            padding: 0.3rem 0.75rem;
            font-size: 0.8rem;
            border-radius: 20px;
            border: none;
            background: transparent;
            transition: all 0.2s ease;
        }
        
        .view-details {
            background-color: rgba(71, 132, 247, 0.1);
            color: #4784f7;
        }
        
        .view-details:hover {
            background-color: #4784f7;
            color: white;
        }
        
        .mark-as-read {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        
        .mark-as-read:hover {
            background-color: #6c757d;
            color: white;
        }
        
        .read-status {
            background-color: rgba(108, 117, 125, 0.05);
            color: #98a6ad;
            cursor: default;
        }
        
        .unread-indicator {
            width: 10px;
            height: 10px;
            background-color: #4784f7;
            border-radius: 50%;
            position: absolute;
            top: 1.5rem;
            left: 0;
            box-shadow: 0 0 0 3px rgba(71, 132, 247, 0.1);
        }
        
        .empty-state .empty-icon {
            font-size: 2.5rem;
            color: #98a6ad;
            display: block;
        }
        
        /* Responsive adjustments */
        @media (max-width: 767px) {
            .notification-actions {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .btn-action {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</div>

<script>
    // Handle mark as read functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Mark as read individual notifications
        document.querySelectorAll('.mark-as-read').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const type = this.getAttribute('data-type');

                fetch('notification.php', { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${encodeURIComponent(id)}&type=${encodeURIComponent(type)}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === 'success') {
                        const notificationItem = this.closest('.notification-item');
                        notificationItem.classList.remove('unread');
                        
                        // Remove unread indicator
                        const unreadIndicator = notificationItem.querySelector('.unread-indicator');
                        if (unreadIndicator) {
                            unreadIndicator.remove();
                        }

                        // Update button
                        this.innerHTML = '<i class="fas fa-check-circle me-1"></i> Read';
                        this.classList.replace('mark-as-read', 'read-status');
                        this.disabled = true;
                        
                        // Update notification counter if exists
                        updateCounter();
                    } else {
                        alert('Failed to mark as read');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
        
        // Mark all as read functionality
        const markAllBtn = document.getElementById('markAllAsRead');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', function() {
                const unreadItems = document.querySelectorAll('.notification-item.unread');
                if (unreadItems.length === 0) return;
                
                const promises = [];
                
                unreadItems.forEach(item => {
                    const markButton = item.querySelector('.mark-as-read');
                    if (markButton) {
                        const id = markButton.getAttribute('data-id');
                        const type = markButton.getAttribute('data-type');
                        
                        const promise = fetch('notification.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `id=${encodeURIComponent(id)}&type=${encodeURIComponent(type)}`
                        });
                        
                        promises.push(promise);
                        
                        // Update UI immediately for better user experience
                        item.classList.remove('unread');
                        const indicator = item.querySelector('.unread-indicator');
                        if (indicator) indicator.remove();
                        
                        markButton.innerHTML = '<i class="fas fa-check-circle me-1"></i> Read';
                        markButton.classList.replace('mark-as-read', 'read-status');
                        markButton.disabled = true;
                    }
                });
                
               
                Promise.all(promises)
                    .then(() => updateCounter())
                    .catch(error => console.error('Error marking all as read:', error));
            });
        }
        
        // Filter functionality
        document.querySelectorAll('.nav-filters .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Update active state
                document.querySelectorAll('.nav-filters .nav-link').forEach(el => {
                    el.classList.remove('active');
                });
                this.classList.add('active');
                
                // Get filter type
                const filterType = this.getAttribute('data-filter');
                
                // Filter notifications
                filterNotifications(filterType);
            });
        });
        
        // Function to filter notifications
        function filterNotifications(type) {
            const notificationItems = document.querySelectorAll('.notification-item');
            const emptyState = document.querySelector('.empty-state');
            let visibleCount = 0;
            
            notificationItems.forEach(item => {
                if (type === 'all' || item.getAttribute('data-type') === type) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show/hide empty state
            if (visibleCount === 0) {
                emptyState.classList.remove('d-none');
            } else {
                emptyState.classList.add('d-none');
            }
        }
        
        // Function to update notification counter (if exists)
        function updateCounter() {
            const counterElem = document.querySelector('.notification-counter');
            if (counterElem) {
                const unreadCount = document.querySelectorAll('.notification-item.unread').length;
                counterElem.textContent = unreadCount;
                
                if (unreadCount === 0) {
                    counterElem.classList.add('d-none');
                } else {
                    counterElem.classList.remove('d-none');
                }
            }
        }
    });
</script>