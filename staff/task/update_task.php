<?php 
include("../../resources/database/config.php"); 
include('../includes/template.php');

if($_GET['id']){
        $id = $_GET['id'];
        $task_sql = "UPDATE task_assignees SET assignee_task = 'completed',completion_time=NOW() WHERE task_id = $id";
        $task_result = mysqli_query($conn, $task_sql);  
        if($task_result){
            echo "<script>window.location.href='index.php'</script>";
    }
}

?>