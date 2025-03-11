<?php 
include("../../resources/database/config.php"); 
include('../includes/template.php');
// include("../../admin/includes/system_update.php");


$id = $_SESSION['ID'];

// Fetch task counts
$total_tasks_sql = "SELECT COUNT(*) AS count FROM
 tasks t INNER JOIN task_assignees ta ON t.id = ta.task_id WHERE ta.staff_id = $id";
$total_tasks_result = mysqli_query($conn, $total_tasks_sql);
$total_tasks_count = mysqli_fetch_assoc($total_tasks_result)['count'];

$overdue_tasks_sql = "SELECT COUNT(ta.id) AS count FROM
 tasks t INNER JOIN task_assignees ta ON t.id = ta.task_id 
 WHERE ta.staff_id = $id AND ta.assignee_task = 'overdue'";
$overdue_tasks_result = mysqli_query($conn, $overdue_tasks_sql);
$overdue_tasks_count = mysqli_fetch_assoc($overdue_tasks_result)['count'];

$pending_tasks_sql = "SELECT COUNT(*) AS count FROM
 tasks t INNER JOIN task_assignees ta ON t.id = ta.task_id
WHERE ta.staff_id = $id AND ta.assignee_task = 'pending'";
$pending_tasks_result = mysqli_query($conn, $pending_tasks_sql);
$pending_tasks_count = mysqli_fetch_assoc($pending_tasks_result)['count'];

// Fetch tasks
$tasks_sql = "SELECT t.id, t.title, t.due_date, t.priority, 
              CASE 
                WHEN t.due_date < CURDATE() AND ta.assignee_task != 'completed' THEN 'overdue' 
                ELSE ta.assignee_task 
              END AS display_status 
              FROM tasks t 
              INNER JOIN task_assignees ta ON t.id = ta.task_id 
              WHERE ta.staff_id = $id 
              ORDER BY t.due_date ASC";
$tasks_result = mysqli_query($conn, $tasks_sql);
?>

<div id="main-content" class="container mt-5">
  <div class="card shadow-sm border-0 rounded-lg">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
      <h4 class="mb-0 text-primary">My Assigned Tasks</h4>
      <div>
        <span class="badge badge-pill badge-light mr-2"><?php echo $total_tasks_count; ?> Total Tasks</span>
        <span class="badge badge-pill badge-soft-danger mr-2"><?php echo $overdue_tasks_count; ?> Overdue</span>
        <span class="badge badge-pill badge-soft-warning"><?php echo $pending_tasks_count; ?> Pending</span>
      </div>
    </div>
    
    <div class="card-body bg-white p-4">
      <div class="form-row mb-4">
        <div class="col-md-4">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text bg-white border-right-0">
                <i class="fa fa-search text-muted"></i>
              </span>
            </div>
            <input type="text" class="form-control border-left-0" placeholder="Search my tasks...">
          </div>
        </div>
        <div class="col-md-3 ml-auto">
          <select class="form-control bg-light border-0">
            <option>All Statuses</option>
            <option>Pending</option>
            <option>In Progress</option>
            <option>Completed</option>
          </select>
        </div>
      </div>
      
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th class="border-top-0 text-muted font-weight-normal">Task ID</th>
              <th class="border-top-0 text-muted font-weight-normal">Task Name</th>
              <th class="border-top-0 text-muted font-weight-normal">Due Date</th>
              <th class="border-top-0 text-muted font-weight-normal">Priority</th>
              <th class="border-top-0 text-muted font-weight-normal">Status</th>
              <th class="border-top-0 text-muted font-weight-normal">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($task = mysqli_fetch_assoc($tasks_result)): ?>
              <tr class="border-soft">
                <td class="align-middle"><?php echo htmlspecialchars($task['id']); ?></td>
                <td class="align-middle font-weight-medium"><?php echo htmlspecialchars($task['title']); ?></td>
                <td class="align-middle">
                  <?php 
                    $due_date = new DateTime($task['due_date']);
                    $formatted_due_date = $due_date->format('F j, Y, g:i a');
                    $badge_class = ($due_date < new DateTime() && $task['display_status'] != 'completed') ? 'badge-soft-danger' : 'badge-soft-success';
                  ?>
                  <span class="badge <?php echo $badge_class; ?> p-2"><?php echo $formatted_due_date; ?></span>
                </td>
                <td class="align-middle">
                  <span style="color:black;" class="badge badge-soft-<?php echo strtolower($task['priority']); ?> p-2"><?php echo ucfirst($task['priority']); ?></span>
                </td>
                <td class="align-middle">
                  <span style="color:black;" class="badge badge-soft-<?php echo strtolower($task['display_status']); ?> p-2"><?php echo ucfirst($task['display_status']); ?></span>
                </td>
                <td class="align-middle">
                  <button class="btn btn-soft-success btn-sm rounded-pill mr-1" 
                          onclick="window.location.href='update_task.php?id=<?php echo $task['id']; ?>'" 
                          title="Mark as Complete" 
                          <?php echo ($task['display_status'] == 'overdue' || $task['display_status'] == 'completed') ? 'disabled' : ''; ?>>
                    Complete
                  </button>
                  <button class="btn btn-soft-info btn-sm rounded-pill" title="View Details">Details</button>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      
      <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
          <span class="text-muted">Showing all <?php echo $total_tasks_count; ?> of your assigned tasks</span>
        </div>
        <div>
          <button class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="fa fa-download mr-1"></i> Export Tasks
          </button>
        </div>
      </div>
    </div>
  </div>

  <style>
    /* Softer Color Palette */
    .badge-soft-danger {
      background-color: rgba(247, 79, 112, 0.15);
      color: #f74f70;
    }
    
    .badge-soft-warning {
      background-color: rgba(247, 178, 71, 0.15);
      color: #f7b247;
    }
    
    .badge-soft-success {
      background-color: rgba(61, 188, 147, 0.15);
      color: #3dbc93;
    }
    
    .badge-soft-primary {
      background-color: rgba(71, 132, 247, 0.15);
      color: #4784f7;
    }
    
    .badge-soft-secondary {
      background-color: rgba(142, 142, 142, 0.15);
      color: #8e8e8e;
    }
    
    .badge-soft-info {
      background-color: rgba(57, 175, 209, 0.15);
      color: #39afd1;
    }
    
    /* Soft Buttons */
    .btn-soft-success {
      background-color: rgba(61, 188, 147, 0.15);
      color: #3dbc93;
      border: none;
    }
    
    .btn-soft-success:hover {
      background-color: rgba(61, 188, 147, 0.25);
      color: #3dbc93;
    }
    
    .btn-soft-info {
      background-color: rgba(57, 175, 209, 0.15);
      color: #39afd1;
      border: none;
    }
    
    .btn-soft-info:hover {
      background-color: rgba(57, 175, 209, 0.25);
      color: #39afd1;
    }
    
    /* Softer Borders */
    .border-soft {
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .table td, .table th {
      border-top: none;
      padding: 1rem 0.75rem;
    }
    
    .badge {
      font-weight: 500;
      letter-spacing: 0.3px;
    }
    
    .badge-pill {
      border-radius: 50rem;
    }
    
    .font-weight-medium {
      font-weight: 500;
    }
    
    .rounded-lg {
      border-radius: 0.5rem !important;
    }
    
    .card {
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
  </style>
</div>