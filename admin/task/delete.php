<?php
include('../../resources/database/config.php');
include('../includes/page_authentication.php');

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];

    // Delete the task
    $sql = "DELETE FROM tasks WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, 'i', $task_id);
    if (mysqli_stmt_execute($stmt)) {
        header('Location: task_assigned_list.php');
        exit;
    } else {
        die("Error deleting task: " . mysqli_stmt_error($stmt));
    }
} else {
    die("Invalid request.");
}
?>