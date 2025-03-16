<?php 
include("../../resources/database/config.php"); 
include('../includes/template.php');
include("../../admin/includes/system_update.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Update task_assignees table
        $task_sql = "UPDATE task_assignees SET assignee_task = 'Complete', completion_time = NOW() WHERE task_id = $id";
        $task_result = mysqli_query($conn, $task_sql);
        if (!$task_result) {
            throw new Exception("Error updating task_assignees");
        }

        // Update tasks table
        $update_task = "UPDATE tasks SET status = 'Completed' WHERE id = $id";
        $update_task_result = mysqli_query($conn, $update_task);
        if (!$update_task_result) {
            throw new Exception("Error updating tasks");
        }

        // Update task_notifications table
        $update_task_notif = "UPDATE task_notifications SET is_read = 1 WHERE task_id = $id";
        $task_notif_result = mysqli_query($conn, $update_task_notif);
        if (!$task_notif_result) {
            throw new Exception("Error updating task_notifications");
        }

        // Commit transaction (All queries succeeded)
        mysqli_commit($conn);

        // Redirect on success
        header("location:index.php");
        exit;
    } catch (Exception $e) {
        // Rollback transaction if any query fails
        mysqli_rollback($conn);
        echo "Transaction failed: " . $e->getMessage();
    }
}


?>