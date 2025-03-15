<?php 
include("../../resources/database/config.php"); 
include('../includes/template.php');
include("../../admin/includes/system_update.php");

if($_GET['id']){
        $id = $_GET['id'];
        $task_sql = "UPDATE task_assignees SET assignee_task = 'Complete',completion_time=NOW() WHERE task_id = $id";
        $task_result = mysqli_query($conn, $task_sql); 
        if($task_result){
            $update_task="UPDATE tasks SET status='Completed' WHERE id=$id";
            $update_task_result=mysqli_query($conn,$update_task);
            if($update_task_result){
                echo $update_task_notif="UPDATE task_notifications SET is_read=1 WHERE task_id=$id";
                 $task_notif_result=mysqli_query($conn,$update_task);
                 if(mysqli_affected_rows($conn)>0){
                    header("location:index.php");
                    exit;
                 }
            }
    }
}

?>