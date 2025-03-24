<?php 
include("../../resources/database/config.php"); 
include("../includes/template.php");
include("../../admin/includes/system_update.php");


$id = $_SESSION['ID'];

// Fetch notifications
$notifications_sql = "SELECT message, created_at, is_read FROM task_notifications WHERE staff_id = $id ORDER BY created_at DESC";
$notifications_result = mysqli_query($conn, $notifications_sql);
?>

<div id="main-content" class="container mt-5">
    <!-- Notification Center -->
    <div class="notification-center mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-primary mb-0">Notifications</h4>
            <div>
                <button class="btn btn-soft-secondary btn-sm rounded-pill mr-2">
                    <i class="fa fa-check-circle mr-1"></i> Mark All as Read
                </button>
            </div>
        </div>
        
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-body p-0">
                <?php while ($notification = mysqli_fetch_assoc($notifications_result)): ?>
                    <div class="notification-item <?php echo $notification['is_read'] == '0' ? '1' : ''; ?>">
                        <div class="d-flex p-3">
                            <div class="notification-icon bg-soft-<?php echo $notification['is_read'] == '0' ? 'primary' : 'secondary'; ?> mr-3">
                                <i class="fa fa-<?php echo $notification['is_read'] == '0' ? 'tasks' : 'check-circle'; ?> text-<?php echo $notification['is_read'] == 'unread' ? 'primary' : 'secondary'; ?>"></i>
                            </div>
                            <div class="notification-content flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($notification['message']); ?></h6>
                                    <small class="text-muted"><?php echo date_format(new DateTime($notification['created_at']), 'F j, Y, g:i a'); ?></small>
                                </div>
                                <div class="notification-actions">
                                    <button class="btn btn-soft-primary btn-sm rounded-pill mr-2">View Task</button>
                                    <button class="btn btn-link btn-sm text-muted p-0">Dismiss</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <div class="card-footer bg-light border-top-0 text-center p-3">
                <a href="#" class="text-primary">View All Notifications</a>
            </div>
        </div>
    </div>
    
    <!-- Task Dashboard (Your previous code here) -->
    
    <style>
        /* Notification Styles */
        .notification-item {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: background-color 0.2s ease;
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
        
        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .bg-soft-primary {
            background-color: rgba(71, 132, 247, 0.15);
        }
        
        .bg-soft-danger {
            background-color: rgba(247, 79, 112, 0.15);
        }
        
        .bg-soft-success {
            background-color: rgba(61, 188, 147, 0.15);
        }
        
        .bg-soft-info {
            background-color: rgba(57, 175, 209, 0.15);
        }
        
        .bg-soft-warning {
            background-color: rgba(247, 178, 71, 0.15);
        }
        
        .bg-soft-secondary {
            background-color: rgba(142, 142, 142, 0.15);
        }
        
        .text-primary {
            color: #4784f7 !important;
        }
        
        .text-danger {
            color: #f74f70 !important;
        }
        
        .text-success {
            color: #3dbc93 !important;
        }
        
        .text-info {
            color: #39afd1 !important;
        }
        
        .text-warning {
            color: #f7b247 !important;
        }
        
        .text-secondary {
            color: #8e8e8e !important;
        }
        
        /* Soft Buttons */
        .btn-soft-primary {
            background-color: rgba(71, 132, 247, 0.15);
            color: #4784f7;
            border: none;
        }
        
        .btn-soft-primary:hover {
            background-color: rgba(71, 132, 247, 0.25);
            color: #4784f7;
        }
        
        .btn-soft-danger {
            background-color: rgba(247, 79, 112, 0.15);
            color: #f74f70;
            border: none;
        }
        
        .btn-soft-danger:hover {
            background-color: rgba(247, 79, 112, 0.25);
            color: #f74f70;
        }
        
        .btn-soft-secondary {
            background-color: rgba(142, 142, 142, 0.15);
            color: #8e8e8e;
            border: none;
        }
        
        .btn-soft-secondary:hover {
            background-color: rgba(142, 142, 142, 0.25);
            color: #8e8e8e;
        }
        
        .btn-link {
            text-decoration: none;
        }
        
        .btn-link:hover {
            text-decoration: underline;
        }
        
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
        
        .rounded-lg {
            border-radius: 0.5rem !important;
        }
        
        .rounded-pill {
            border-radius: 50rem !important;
        }
    </style>
</div>