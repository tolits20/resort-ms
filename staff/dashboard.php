<?php 
include("../resources/database/config.php"); 
$id = $_SESSION['ID'];

// Fetch task counts - Fixed status field names
$pending_task_sql = "SELECT COUNT(*) AS count FROM tasks t INNER JOIN task_assignees ta ON t.id = ta.task_id WHERE ta.staff_id = $id AND ta.assignee_task = 'pending'";
$pending_task_result = mysqli_query($conn, $pending_task_sql);
$pending_task_count = mysqli_fetch_assoc($pending_task_result)['count'];

$completed_task_sql = "SELECT COUNT(*) AS count FROM tasks t INNER JOIN task_assignees ta ON t.id = ta.task_id WHERE ta.staff_id = $id AND ta.assignee_task = 'completed'";
$completed_task_result = mysqli_query($conn, $completed_task_sql);
$completed_task_count = mysqli_fetch_assoc($completed_task_result)['count'];

// Fixed overdue task query to use due_date for determining overdue status
$overdue_task_sql = "SELECT COUNT(*) AS count FROM tasks t INNER JOIN task_assignees ta ON t.id = ta.task_id WHERE ta.staff_id = $id AND t.due_date < NOW() AND ta.assignee_task = 'overdue'";
$overdue_task_result = mysqli_query($conn, $overdue_task_sql);
$overdue_task_count = mysqli_fetch_assoc($overdue_task_result)['count'];

// Fetch recent tasks
$recent_tasks_sql = "SELECT t.title, t.due_date, t.status FROM tasks t INNER JOIN task_assignees ta ON t.id = ta.task_id WHERE ta.staff_id = $id ORDER BY t.due_date DESC LIMIT 5";
$recent_tasks_result = mysqli_query($conn, $recent_tasks_sql);

// Fetch recent notifications
$recent_notifications_sql = "SELECT message, created_at FROM task_notifications WHERE staff_id = $id ORDER BY created_at DESC LIMIT 3";
$recent_notifications_result = mysqli_query($conn, $recent_notifications_sql);

// Fetch overdue tasks
$overdue_tasks_sql = "SELECT t.title, t.due_date, t.status, ta.id FROM tasks t INNER JOIN task_assignees ta ON t.id = ta.task_id WHERE ta.staff_id = $id AND ta.assignee_task = 'overdue' AND t.status != 'completed' ORDER BY t.due_date DESC";
$overdue_tasks_result = mysqli_query($conn, $overdue_tasks_sql);

include("includes/template.php");
?>

<div id="main-content" class="container-fluid">
  <h2 class="page-title mb-4">Dashboard</h2>
  
  <div class="row">
    <div class="col-md-4">
      <div class="card stat-card">
        <i class="fas fa-clipboard-list"></i>
        <div class="count"><?php echo $pending_task_count; ?></div>
        <div class="label">Pending Tasks</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card completed">
        <i class="fas fa-check-circle"></i>
        <div class="count"><?php echo $completed_task_count; ?></div>
        <div class="label">Completed Tasks</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card overdue">
        <i class="fas fa-exclamation-circle"></i>
        <div class="count"><?php echo $overdue_task_count; ?></div>
        <div class="label">Overdue Tasks</div>
      </div>
    </div>
  </div>
  
  <div class="row mt-4">
    <div class="col-md-6">
      <div class="card dashboard-card">
        <div class="card-header">
          <h5 class="card-title mb-0">Recent Tasks</h5>
        </div>
        <div class="card-body">
          <?php if (mysqli_num_rows($recent_tasks_result) > 0): ?>
            <?php while ($task = mysqli_fetch_assoc($recent_tasks_result)): ?>
              <div class="task-item">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="mb-1"><?php echo htmlspecialchars($task['title']); ?></h6>
                    <small class="text-muted">Due: <?php echo date_format(new DateTime($task['due_date']), 'F j, Y, g:i a'); ?></small>
                  </div>
                  <?php
                  // Determine if task is overdue regardless of status
                  $is_overdue = strtotime($task['due_date']) < time() && $task['status'] != 'completed';
                  $status = $is_overdue ? 'overdue' : $task['status'];
                  ?>
                  <span class="status-badge badge-<?php echo strtolower($status); ?>"><?php echo $is_overdue ? 'Overdue' : ucfirst($task['status']); ?></span>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="text-center py-3">
              <i class="fas fa-tasks text-muted mb-2" style="font-size: 2rem;"></i>
              <p>No recent tasks found</p>
            </div>
          <?php endif; ?>
        </div>
        <div class="card-footer">
          <a href="tasks.php" class="btn btn-primary">View All Tasks</a>
        </div>
      </div>
    </div>
    
    <div class="col-md-6">
      <div class="card dashboard-card">
        <div class="card-header">
          <h5 class="card-title mb-0">Recent Notifications</h5>
        </div>
        <div class="card-body notifications-container">
          <?php if (mysqli_num_rows($recent_notifications_result) > 0): ?>
            <?php while ($notification = mysqli_fetch_assoc($recent_notifications_result)): ?>
              <div class="notification-item">
                <div class="notification-icon">
                  <i class="fas fa-bell"></i>
                </div>
                <div class="notification-content">
                  <div class="notification-message"><?php echo htmlspecialchars($notification['message']); ?></div>
                  <div class="notification-time"><?php echo date_format(new DateTime($notification['created_at']), 'F j, Y, g:i a'); ?></div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="text-center py-3">
              <i class="fas fa-bell-slash text-muted mb-2" style="font-size: 2rem;"></i>
              <p>No notifications found</p>
            </div>
          <?php endif; ?>
        </div>
        <div class="card-footer">
          <a href="notifications.php" class="btn btn-primary">View All Notifications</a>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card dashboard-card">
        <div class="card-header">
          <h5 class="card-title mb-0">Overdue Tasks</h5>
        </div>
        <div class="card-body">
          <?php if (mysqli_num_rows($overdue_tasks_result) > 0): ?>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Task Title</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($task = mysqli_fetch_assoc($overdue_tasks_result)): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($task['title']); ?></td>
                      <td>
                        <span class="text-danger">
                          <?php echo date_format(new DateTime($task['due_date']), 'F j, Y, g:i a'); ?>
                        </span>
                      </td>
                      <td>
                        <span class="status-badge badge-overdue">Overdue</span>
                      </td>
                      <td>
                        <a href="task-details.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-info">
                          <i class="fas fa-eye"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="text-center py-4">
              <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
              <h5>Great job!</h5>
              <p>You don't have any overdue tasks.</p>
            </div>
          <?php endif; ?>
        </div>
        <?php if (mysqli_num_rows($overdue_tasks_result) > 0): ?>
        <div class="card-footer">
          <a href="overdue-tasks.php" class="btn btn-primary">View All Overdue Tasks</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<style>
  /* Enhanced Dashboard Styles */
  #main-content {
    padding: 30px 0;
  }
  
  .page-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 25px;
    border-bottom: 2px solid #f1f1f1;
    padding-bottom: 10px;
  }
  
  .dashboard-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
    overflow: hidden;
  }
  
  .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #edf2f7;
    padding: 15px 20px;
  }
  
  .card-title {
    color: #2c3e50;
    font-weight: 600;
  }
  
  .card-body {
    padding: 20px;
  }
  
  .card-footer {
    background-color: #fff;
    border-top: 1px solid #edf2f7;
    padding: 15px 20px;
  }
  
  /* Stats Cards */
  .stat-card {
    background: #fff;
    border-radius: 10px;
    padding: 25px 20px;
    text-align: center;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border-left: 5px solid #4e73df;
  }

  .stat-card.completed {
    border-left-color: #1cc88a;
  }
  
  .stat-card.overdue {
    border-left-color: #e74a3b;
  }
  
  .stat-card i {
    font-size: 40px;
    color: #4e73df;
    margin-bottom: 15px;
  }
  
  .stat-card.completed i {
    color: #1cc88a;
  }
  
  .stat-card.overdue i {
    color: #e74a3b;
  }
  
  .stat-card .count {
    font-size: 36px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 5px;
  }
  
  .stat-card .label {
    font-size: 16px;
    color: #7b8a8b;
    font-weight: 500;
  }
  
  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
  }
  
  /* Task Items */
  .task-item {
    padding: 15px;
    border-bottom: 1px solid #eee;
    transition: all 0.2s ease;
  }
  
  .task-item:last-child {
    border-bottom: none;
  }
  
  .task-item:hover {
    background-color: #f8f9fa;
  }
  
  .task-item h6 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 5px;
  }
  
  /* Status Badges */
  .status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .badge-pending {
    background-color: #f6c23e;
    color: #212529;
  }
  
  .badge-progress, .badge-in-progress {
    background-color: #36b9cc;
    color: white;
  }
  
  .badge-completed {
    background-color: #1cc88a;
    color: white;
  }
  
  .badge-overdue {
    background-color: #e74a3b;
    color: white;
  }
  
  /* Notification Items */
  .notification-item {
    display: flex;
    align-items: flex-start;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
  }
  
  .notification-item:last-child {
    border-bottom: none;
  }
  
  .notification-icon {
    background-color: #e8f4fd;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
  }
  
  .notification-icon i {
    color: #4e73df;
    font-size: 18px;
  }
  
  .notification-content {
    flex-grow: 1;
  }
  
  .notification-message {
    color: #2c3e50;
    font-weight: 500;
    margin-bottom: 5px;
    line-height: 1.4;
  }
  
  .notification-time {
    color: #95a5a6;
    font-size: 12px;
  }
  
  /* Buttons */
  .btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.2s ease;
  }
  
  .btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2e59d9;
    transform: translateY(-1px);
  }
  
  /* Table Styles */
  .table {
    margin-bottom: 0;
  }
  
  .table thead th {
    background-color: #f8f9fa;
    color: #2c3e50;
    font-weight: 600;
    border-top: none;
    border-bottom: 2px solid #e9ecef;
    padding: 12px 15px;
  }
  
  .table td {
    padding: 15px;
    vertical-align: middle;
    color: #2c3e50;
  }
  
  .table tr:hover {
    background-color: #f8f9fa;
  }
  
  /* Empty State Styling */
  .text-center i {
    color: #d1d1d1;
  }
  
  /* Responsive Adjustments */
  @media (max-width: 767px) {
    .stat-card {
      margin-bottom: 20px;
    }
    
    .task-item {
      flex-direction: column;
    }
    
    .task-item .status-badge {
      margin-top: 10px;
    }
  }
</style>