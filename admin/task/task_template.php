<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include ('../includes/template.php');
include("../includes/system_update.php");

$sql = "SELECT * FROM task_templates";
$result = mysqli_query($conn, $sql);
?>


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

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .task-table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-top: none;
            padding: 15px 16px;
        }

        .table td {
            padding: 15px 16px;
            vertical-align: middle;
        }

        .table tr:hover {
            background-color: #f8f9fa;
        }

        .task-priority {
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

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        .btn-primary:hover {
            background-color: #3a56d4;
            border-color: #3a56d4;
        }

        .btn-view {
            background-color: #6f42c1;
            border-color: #6f42c1;
            color: white;
        }

        .btn-view:hover {
            background-color: #5e35b1;
            border-color: #5e35b1;
            color: white;
        }

        .btn-edit {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-edit:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
            border-color: #c82333;
        }

        .btn-assign {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-assign:hover {
            background-color: #218838;
            border-color: #218838;
        }

        .task-description {
            max-width: 400px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }
            
            .task-description {
                max-width: 200px;
            }
            
            .header-actions {
                flex-direction: column;
            }
        }
    </style>

    <div class="content">
        <div class="page-header">
            <h2 class="m-0">Task Management</h2>
            <div class="header-actions">
                <a href="task_assigned_list.php" class="btn btn-view"><i class="fas fa-tasks"></i> View Assigned Tasks</a>
                <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> Create New Task</a>
            </div>
        </div>
        
        <div class="task-table">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th>Priority</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
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
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                                <td><div class="task-description"><?php echo htmlspecialchars($row['description']); ?></div></td>
                                <td><span class="task-priority <?php echo $priorityClass; ?>"><?php echo htmlspecialchars($row['priority']); ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit.php?id=<?php echo $row['task_template_id']; ?>" class="btn btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="delete.php?id=<?php echo $row['task_template_id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this task?');"><i class="fas fa-trash"></i> Delete</a>
                                        <a href="assign_task.php?id=<?php echo $row['task_template_id']; ?>" class="btn btn-assign"><i class="fas fa-user-plus"></i> Assign</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-tasks fa-3x mb-3"></i>
                                    <h5>No tasks found</h5>
                                    <p>Create a new task to get started</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

