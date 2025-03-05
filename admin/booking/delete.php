<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include("../includes/system_update.php");

if(isset($_GET['id']) && isset($_POST['yes'])){
    $id=$_GET['id'];
    try{
        mysqli_begin_transaction($conn);
        $sql="DELETE FROM booking  WHERE book_id =?";
        $stmt=mysqli_prepare($conn,$sql);
        mysqli_stmt_bind_param($stmt,'i',$id);
        mysqli_stmt_execute($stmt);

        if(mysqli_stmt_affected_rows($stmt)>0){
            mysqli_commit($conn);
            header("location:index.php?switch=user");
            exit;
        }

    }catch(Exception $e){
        mysqli_rollback($conn);
        print $e->getMessage();
    }
}else{
    header("location:index.php?switch=user");
}

?>