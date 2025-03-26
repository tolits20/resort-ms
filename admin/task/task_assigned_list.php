<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include ('../includes/template.php');
include("../includes/system_update.php");

// Fetch all assigned tasks grouped by title, due date, and recurrence
$sql = "
    SELECT 
        t.id AS task_id, 
        t.title, 
        t.description, 
        t.priority, 
        t.status, 
        t.due_date, 
        t.recurrence, 
        GROUP_CONCAT(CONCAT(u.fname, ' ', u.lname) SEPARATOR ', ') AS assignees
    FROM 
        tasks t
    INNER JOIN 
        task_assignees ta ON t.id = ta.task_id
    INNER JOIN 
        account a ON ta.staff_id = a.account_id
    INNER JOIN 
        user u ON a.account_id = u.account_id
    GROUP BY 
        t.title, t.due_date, t.recurrence
    ORDER BY 
        t.due_date ASC
";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Tasks</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .content {
            max-width: 1200px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .navigation-banner {
            background-color: #4361ee;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .banner-title {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .banner-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .banner-btn {
            background-color: white;
            color: #4361ee;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .banner-btn:hover {
            background-color: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .banner-btn i {
            font-size: 16px;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-top: none;
            padding: 12px 16px;
        }

        .table td {
            padding: 12px 16px;
            vertical-align: middle;
        }

        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .task-priority, .task-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            min-width: 80px;
        }

        .priority-high {
            background-color: #ffebee;
            color: #d32f2f;
        }

        .priority-medium {
            background-color: #fff8e1;
            color: #ff8f00;
        }

        .priority-low {
            background-color: #e8f5e9;
            color: #388e3c;
        }

        .status-pending {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .status-in-progress {
            background-color: #e8f5e9;
            color: #388e3c;
        }

        .status-completed {
            background-color: #e0f2f1;
            color: #00796b;
        }

        .status-overdue {
            background-color: #ffebee;
            color: #d32f2f;
        }

        .action-btns {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.2s;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .edit-btn {
            background-color: #6c757d;
        }

        .edit-btn:hover {
            background-color: #5a6268;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .task-description {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        @media (max-width: 992px) {
            .navigation-banner {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .banner-actions {
                margin-top: 10px;
                width: 100%;
            }
            
            .task-description {
                max-width: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="navigation-banner">
            <h2 class="banner-title">Assigned Tasks</h2>
            <div class="banner-actions">
                <a href="task_template.php" class="banner-btn"><i class="fas fa-arrow-left"></i> Back to Tasks</a>
                <a href="task_template.php" class="banner-btn"><i class="fas fa-plus"></i> Assign New Task</a>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Recurrence</th>
                        <th>Assignees</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($result) && mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <?php 
                                $priorityClass = '';
                                switch(strtolower($row['priority'])) {
                                    case 'high':
                                        $priorityClass = 'priority-high';
                                        break;
                                    case 'medium':
                                        $priorityClass = 'priority-medium';
                                        break;
                                    case 'low':
                                        $priorityClass = 'priority-low';
                                        break;
                                    default:
                                        $priorityClass = '';
                                }
                                
                                $statusClass = '';
                                switch(strtolower($row['status'])) {
                                    case 'pending':
                                        $statusClass = 'status-pending';
                                        break;
                                    case 'in progress':
                                        $statusClass = 'status-in-progress';
                                        break;
                                    case 'completed':
                                        $statusClass = 'status-completed';
                                        break;
                                    case 'overdue':
                                        $statusClass = 'status-overdue';
                                        break;
                                    default:
                                        $statusClass = '';
                                }
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                                <td><div class="task-description"><?php echo htmlspecialchars($row['description']); ?></div></td>
                                <td><span class="task-priority <?php echo $priorityClass; ?>"><?php echo htmlspecialchars($row['priority']); ?></span></td>
                                <td><span class="task-status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                                <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['recurrence']); ?></td>
                                <td><?php echo htmlspecialchars($row['assignees']); ?></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="assign_edit.php?id=<?php echo $row['task_id']; ?>" class="action-btn edit-btn" title="Edit Task"><i class="fas fa-edit"></i></a>
                                        <a href="delete.php?id=<?php echo $row['task_id']; ?>&delete='yes'" class="action-btn delete-btn" title="Delete Task" onclick="return confirm('Are you sure you want to delete this task?');"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-tasks fa-3x mb-3"></i>
                                    <h5>No assigned tasks found</h5>
                                    <p>Assign a task to get started</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>