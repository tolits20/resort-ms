<?php 
include("../../resources/database/config.php"); 
include("../includes/template.php");
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
                <button class="btn btn-soft-primary btn-sm rounded-pill">
                    <i class="fa fa-cog mr-1"></i> Settings
                </button>
            </div>
        </div>
        
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-body p-0">
                <!-- Unread Notifications -->
                <div class="notification-item unread">
                    <div class="d-flex p-3">
                        <div class="notification-icon bg-soft-primary mr-3">
                            <i class="fa fa-tasks text-primary"></i>
                        </div>
                        <div class="notification-content flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">New Task Assigned</h6>
                                <small class="text-muted">Just now</small>
                            </div>
                            <p class="mb-1 text-dark">You have been assigned a new task: "Repair Deck"</p>
                            <div class="notification-actions">
                                <button class="btn btn-soft-primary btn-sm rounded-pill mr-2">View Task</button>
                                <button class="btn btn-link btn-sm text-muted p-0">Dismiss</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="notification-item unread">
                    <div class="d-flex p-3">
                        <div class="notification-icon bg-soft-danger mr-3">
                            <i class="fa fa-exclamation-circle text-danger"></i>
                        </div>
                        <div class="notification-content flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">Task Overdue</h6>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                            <p class="mb-1 text-dark">The task "Clean Pool" is now overdue. The due date was yesterday.</p>
                            <div class="notification-actions">
                                <button class="btn btn-soft-danger btn-sm rounded-pill mr-2">Update Status</button>
                                <button class="btn btn-link btn-sm text-muted p-0">Dismiss</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Read Notifications -->
                <div class="notification-item">
                    <div class="d-flex p-3">
                        <div class="notification-icon bg-soft-success mr-3">
                            <i class="fa fa-check-circle text-success"></i>
                        </div>
                        <div class="notification-content flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1 text-muted">Task Completed</h6>
                                <small class="text-muted">Yesterday</small>
                            </div>
                            <p class="mb-1 text-muted">You successfully completed the task "Mow Lawn".</p>
                            <div class="notification-actions">
                                <button class="btn btn-link btn-sm text-muted p-0">Dismiss</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="notification-item">
                    <div class="d-flex p-3">
                        <div class="notification-icon bg-soft-info mr-3">
                            <i class="fa fa-comment text-info"></i>
                        </div>
                        <div class="notification-content flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1 text-muted">New Comment</h6>
                                <small class="text-muted">3 days ago</small>
                            </div>
                            <p class="mb-1 text-muted">John added a comment to your task "Fix Fence": "Please use the new materials in the garage."</p>
                            <div class="notification-actions">
                                <button class="btn btn-link btn-sm text-muted p-0">Dismiss</button>
                            </div>
                        </div>
                    </div>
                </div>
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