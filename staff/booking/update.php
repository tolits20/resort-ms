<?php 
include('../../resources/database/config.php');
include("../../admin/includes/system_update.php");

if(isset($_POST['save'])){
    $check_in=$_POST['check_in'];
    $check_out=$_POST['check_out'];
    $book_status=$_POST['status'];
    print $id=$_POST['id'];
    if($check_in<$check_out){

      try{
        mysqli_begin_transaction($conn);
        $sql='UPDATE booking SET check_in=? , check_out= ? , book_status= ?,updated_at=now() WHERE book_id=?';
        $stmt=mysqli_prepare($conn,$sql);
        mysqli_stmt_bind_param($stmt,'sssi',$check_in,$check_out,$book_status,$id);
        mysqli_stmt_execute($stmt);
        if(mysqli_stmt_affected_rows($stmt)>0){
            mysqli_commit($conn);
            header("location:edit.php?switch=user&&id=$id");
        }else{
            throw new Exception("failed to update the booking table");
        }
      }catch(Exception $e){
        print $e->getMessage();
        mysqli_rollback($conn);
        exit;
      }

    }else{
        print "the check in date is greater than check out date that seems impossible tho";
    }
}

?>