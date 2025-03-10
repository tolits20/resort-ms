<?php 
include("../resources/database/config.php"); 
include("includes/template.php");
?>

<div id="main-content" class="container-fluid">
  <h2 class="page-title">Dashboard</h2>
  
  <div class="row">
    <div class="col-md-4">
      <div class="card stat-card">
        <i class="fas fa-clipboard-list"></i>
        <div class="count">12</div>
        <div class="label">Pending Tasks</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card">
        <i class="fas fa-spinner"></i>
        <div class="count">5</div>
        <div class="label">In Progress</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card">
        <i class="fas fa-check-circle"></i>
        <div class="count">23</div>
        <div class="label">Completed Tasks</div>
      </div>
    </div>
  </div>
  
  <div class="row mt-4">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header bg-white">
          <h5 class="card-title mb-0">Recent Tasks</h5>
        </div>
        <div class="card-body">
          <div class="task-item priority-high">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1">Clean Pool Area</h6>
                <small class="text-muted">Due: Today, 5:00 PM</small>
              </div>
              <span class="status-badge badge-pending">Pending</span>
            </div>
          </div>
          <div class="task-item priority-medium">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1">Restock Storage Room</h6>
                <small class="text-muted">Due: Tomorrow, 9:00 AM</small>
              </div>
              <span class="status-badge badge-progress">In Progress</span>
            </div>
          </div>
          <div class="task-item priority-low">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1">Weekly Report Submission</h6>
                <small class="text-muted">Due: Mar 12, 12:00 PM</small>
              </div>
              <span class="status-badge badge-completed">Completed</span>
            </div>
          </div>
        </div>
        <div class="card-footer bg-white">
          <a href="#" class="btn btn-sm btn-primary">View All Tasks</a>
        </div>
      </div>
    </div>
    
    <div class="col-md-6">
      <div class="card">
        <div class="card-header bg-white">
          <h5 class="card-title mb-0">Recent Notifications</h5>
        </div>
        <div class="card-body notifications-container">
          <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <div>
              <strong>New Task Assigned:</strong> Clean Pool Area
              <div><small>2 hours ago</small></div>
            </div>
          </div>
          <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>
              <strong>Task Completed:</strong> Room 203 Service
              <div><small>Yesterday</small></div>
            </div>
          </div>
          <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
              <strong>Task Reminder:</strong> Restock Storage Room
              <div><small>Yesterday</small></div>
            </div>
          </div>
        </div>
        <div class="card-footer bg-white">
          <a href="#" class="btn btn-sm btn-primary">View All Notifications</a>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }

  .stat-card i {
    font-size: 40px;
    color: #007bff;
    margin-bottom: 10px;
  }

  .stat-card .count {
    font-size: 32px;
    font-weight: bold;
    color: #333;
  }

  .stat-card .label {
    font-size: 16px;
    color: #777;
  }

  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  }

  .task-item {
    padding: 15px;
    border-bottom: 1px solid #eee;
  }

  .task-item:last-child {
    border-bottom: none;
  }

  .status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
  }

  .badge-pending {
    background-color: #ffc107;
    color: #212529;
  }

  .badge-progress {
    background-color: #17a2b8;
    color: white;
  }

  .badge-completed {
    background-color: #28a745;
    color: white;
  }

  .notifications-container .alert {
    margin-bottom: 15px;
  }

  .notifications-container .alert i {
    font-size: 24px;
  }
</style>