<?php 
include('../../resources/database/config.php');


if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $sql = "SELECT * FROM task_templates WHERE task_template_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $task_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $task = mysqli_fetch_assoc($result);

    if (!$task) {
        die("Task not found.");
    }
} else {
    die("Invalid request.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $assignees = $_POST['assignees'];
    $due_date = $_POST['due_date'];
    $recurrence = $_POST['recurrence'];

    // Insert new task
   echo $sql = "INSERT INTO tasks (title, description, priority, status, due_date, recurrence, template_id) 
            VALUES ('{$task['title']}', '{$task['description']}', '{$task['priority']}', 'Pending', '$due_date', '$recurrence', $task_id)";

    if (mysqli_query($conn, $sql)) {
        $last_id = mysqli_insert_id($conn); // Get the inserted task_id
        echo "Task inserted successfully with ID: $last_id <br>";
    } else {
        die("Error inserting task: " . mysqli_error($conn));
    }

    // Assign staff members
    foreach ($assignees as $staff_id) {
        $staff_sql = "INSERT INTO task_assignees (task_id, staff_id) VALUES ($last_id, $staff_id)";

        if (mysqli_query($conn, $staff_sql)) {
            echo "Assigned staff ID: $staff_id to task ID: $last_id <br>";
        } else {
            die("Error assigning staff: " . mysqli_error($conn));
        }
    }

    // Insert notifications
    foreach ($assignees as $staff_id) {
        $message = "New task assigned: " . mysqli_real_escape_string($conn, $task['title']);
        $notification_sql = "INSERT INTO task_notifications (task_id, staff_id, message) VALUES ($last_id, $staff_id, '$message')";

        if (mysqli_query($conn, $notification_sql)) {
            echo "Notification sent to staff ID: $staff_id <br>";
        } else {
            die("Error inserting notification: " . mysqli_error($conn));
        }
    }

    // Redirect after successful insertion
    header('Location: task_template.php');
    exit;
}



// Fetch staff members for the checkboxes
$staff_sql = "SELECT a.account_id AS id, CONCAT(u.fname, ' ', u.lname) AS name FROM account a INNER JOIN user u USING(account_id) WHERE role = 'staff'";
$staff_result = mysqli_query($conn, $staff_sql);
include('../includes/page_authentication.php');
include ('../includes/template.php');
include("../includes/system_update.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Task</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .content {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-secondary {
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="content">
        <h2 class="mb-4">Assign Task</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="assign_task.php?id=<?php echo $task_id; ?>" method="post">
            <div class="form-group">
                <label for="task_name">Task Name</label>
                <input type="text" class="form-control" id="task_name" name="task_name" value="<?php echo htmlspecialchars($task['title']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" class="form-control" id="due_date" name="due_date" required>
            </div>
            <div class="form-group">
                <label for="recurrence">Recurrence</label>
                <select class="form-control" id="recurrence" name="recurrence" required>
                    <option value="None">None</option>
                    <option value="Daily">Daily</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Monthly">Monthly</option>
                </select>
            </div>
            <div class="form-group">
                <label for="assignees">Assign to</label>
                <div id="assignees">
                    <?php while ($staff = mysqli_fetch_assoc($staff_result)): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="assignees[]" value="<?php echo htmlspecialchars($staff['id']); ?>" id="staff-<?php echo $staff['id']; ?>">
                            <label class="form-check-label" for="staff-<?php echo $staff['id']; ?>">
                                <?php echo htmlspecialchars($staff['name']); ?>
                            </label>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Assign Task</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>