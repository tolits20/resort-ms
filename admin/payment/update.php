<?php
include('../../resources/database/config.php');

if(isset($_POST['save'])){
    echo $method=$_POST['payment_method'];
    echo $status=$_POST['status'];
    echo $amount=$_POST['amount'];
    echo $id=$_POST['payment_id'];

    try{
        mysqli_begin_transaction($conn);
        $sql="UPDATE payments SET  payment_method=?,payment_status=? WHERE book_id= ?";
        $stmt=mysqli_prepare($conn,$sql);
        mysqli_stmt_bind_param($stmt,'ssi',$method,$status,$id);
        mysqli_stmt_execute($stmt);
        if(mysqli_stmt_affected_rows($stmt)>0){
            mysqli_commit($conn);
            header("location:edit.php?id=$id");
            exit;
        }
    }catch(Exception $e){
        mysqli_rollback($conn);
        print $e->getMessage();
        exit;
    }
}

?>