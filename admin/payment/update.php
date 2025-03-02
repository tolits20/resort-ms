<?php
include('../../resources/database/config.php');

if(isset($_POST['save'])){
    echo $method=($_POST['payment_method']=='none' ? NULL : $_POST['payment_method']);
    echo $status=$_POST['status'];
    echo $amount=$_POST['amount'];
    echo $id=$_GET['id'];

    try{
        mysqli_begin_transaction($conn);
        $sql="UPDATE payment SET  payment_type=?,payment_status=?,amount=? WHERE book_id= ?";
        $stmt=mysqli_prepare($conn,$sql);
        mysqli_stmt_bind_param($stmt,'ssii',$method,$status,$amount,$id);
        mysqli_stmt_execute($stmt);
        if(mysqli_stmt_affected_rows($stmt)>0){
            mysqli_commit($conn);
            header("location:index.php?id=$id");
            exit;
        }
    }catch(Exception $e){
        mysqli_rollback($conn);
        print $e->getMessage();
        exit;
    }
}

?>