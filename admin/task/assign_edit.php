<?php 
include('../../resources/database/config.php');

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$task_id = $_GET['id'];

// Fetch task details
$sql = "SELECT * FROM tasks WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $task_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$task = mysqli_fetch_assoc($result);

if (!$task) {
    die("Task not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $assignees = isset($_POST['assignees']) ? $_POST['assignees'] : [];
    $due_date = $_POST['due_date'];
    $recurrence = $_POST['recurrence'];

    // Update task details
    $update_sql = "UPDATE tasks SET due_date = ?, recurrence = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, 'ssi', $due_date, $recurrence, $task_id);
    mysqli_stmt_execute($update_stmt);

    // Fetch current assignees
    $current_assignees_sql = "SELECT staff_id FROM task_assignees WHERE task_id = ?";
    $current_assignees_stmt = mysqli_prepare($conn, $current_assignees_sql);
    mysqli_stmt_bind_param($current_assignees_stmt, 'i', $task_id);
    mysqli_stmt_execute($current_assignees_stmt);
    $current_assignees_result = mysqli_stmt_get_result($current_assignees_stmt);
    $current_assignees = [];
    while ($row = mysqli_fetch_assoc($current_assignees_result)) {
        $current_assignees[] = $row['staff_id'];
    }

    // Determine assignees to delete notifications for
    $assignees_to_delete = array_diff($current_assignees, $assignees);
    $assignees_to_add = array_diff($assignees, $current_assignees);

    // Delete existing task assignments
    $delete_sql = "DELETE FROM task_assignees WHERE task_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, 'i', $task_id);
    mysqli_stmt_execute($delete_stmt);

    // Insert new task assignments
    if (!empty($assignees)) {
        $insert_sql = "INSERT INTO task_assignees (task_id, staff_id) VALUES (?, ?)";
        $insert_stmt = mysqli_prepare($conn, $insert_sql);
        foreach ($assignees as $staff_id) {
            mysqli_stmt_bind_param($insert_stmt, 'ii', $task_id, $staff_id);
            mysqli_stmt_execute($insert_stmt);
        }
    }

    // Delete notifications for unassigned staff members
    if (!empty($assignees_to_delete)) {
        $delete_notifications_sql = "DELETE FROM task_notifications WHERE task_id = ? AND staff_id = ?";
        $delete_notifications_stmt = mysqli_prepare($conn, $delete_notifications_sql);
        foreach ($assignees_to_delete as $staff_id) {
            mysqli_stmt_bind_param($delete_notifications_stmt, 'ii', $task_id, $staff_id);
            mysqli_stmt_execute($delete_notifications_stmt);
        }
    }

    // Add notifications for newly assigned staff members
    if (!empty($assignees_to_add)) {
        $insert_notifications_sql = "INSERT INTO task_notifications (task_id, staff_id, message) VALUES (?, ?, ?)";
        $insert_notifications_stmt = mysqli_prepare($conn, $insert_notifications_sql);
        $message = "You have been assigned a new task: " . $task['title'];
        foreach ($assignees_to_add as $staff_id) {
            mysqli_stmt_bind_param($insert_notifications_stmt, 'iis', $task_id, $staff_id, $message);
            mysqli_stmt_execute($insert_notifications_stmt);
        }
    }

    if (mysqli_stmt_affected_rows($update_stmt) > 0) {
        header('Location: task_assigned_list.php');
        exit;
    } else {
        $error = "Failed to update task.";
    }
}

// Fetch staff members
$staff_sql = "SELECT a.account_id AS id, CONCAT(u.fname, ' ', u.lname) AS name FROM account a INNER JOIN user u USING(account_id) WHERE role = 'staff'";
$staff_result = mysqli_query($conn, $staff_sql);

// Fetch current assignees
$current_assignees_sql = "SELECT staff_id FROM task_assignees WHERE task_id = ?";
$current_assignees_stmt = mysqli_prepare($conn, $current_assignees_sql);
mysqli_stmt_bind_param($current_assignees_stmt, 'i', $task_id);
mysqli_stmt_execute($current_assignees_stmt);
$current_assignees_result = mysqli_stmt_get_result($current_assignees_stmt);
$current_assignees = [];
while ($row = mysqli_fetch_assoc($current_assignees_result)) {
    $current_assignees[] = $row['staff_id'];
}

include('../includes/page_authentication.php');
include ('../includes/template.php');
include("../includes/system_update.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assigned Task</title>
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
    </style>
</head>
<body>
    <div class="content">
        <h2 class="mb-4">Edit Assigned Task</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="assign_edit.php?id=<?php echo $task_id; ?>" method="post">
            <div class="form-group">
                <label for="task_name">Task Name</label>
                <input type="text" class="form-control" id="task_name" name="task_name" value="<?php echo htmlspecialchars($task['title']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" class="form-control" id="due_date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="recurrence">Recurrence</label>
                <select class="form-control" id="recurrence" name="recurrence" required>
                    <option value="None" <?php echo ($task['recurrence'] == 'None' ? 'selected' : ''); ?>>None</option>
                    <option value="Daily" <?php echo ($task['recurrence'] == 'Daily' ? 'selected' : ''); ?>>Daily</option>
                    <option value="Weekly" <?php echo ($task['recurrence'] == 'Weekly' ? 'selected' : ''); ?>>Weekly</option>
                    <option value="Monthly" <?php echo ($task['recurrence'] == 'Monthly' ? 'selected' : ''); ?>>Monthly</option>
                </select>
            </div>
            <div class="form-group">
                <label for="assignees">Assign to</label>
                <div id="assignees">
                    <?php while ($staff = mysqli_fetch_assoc($staff_result)): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="assignees[]" value="<?php echo htmlspecialchars($staff['id']); ?>" id="staff-<?php echo $staff['id']; ?>" <?php echo in_array($staff['id'], $current_assignees) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="staff-<?php echo $staff['id']; ?>">
                                <?php echo htmlspecialchars($staff['name']); ?>
                            </label>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Task</button>
            <a href="task_assigned_list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
