<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include("../includes/system_update.php");


if(isset($_POST['create'])){
    $name=$_POST['discount_name'];
    $percentage=$_POST['percentage'];
    $start=$_POST['start_date'];
    $end=$_POST['end_date'];
    $type=$_POST['applicable_room'];
    $minDateTime = date('Y-m-d\TH:i');

    try{
        mysqli_begin_transaction($conn);
        $sql="INSERT INTO discount (discount_name,discount_percentage,discount_start,discount_end,discount_status,applicable_room )
            VALUES(?,?,?,?,'active',?)";
            $stmt=mysqli_prepare($conn,$sql);
            mysqli_stmt_bind_param($stmt,"sisss",$name,$percentage,$start,$end,$type);
            mysqli_stmt_execute($stmt);

            if(mysqli_stmt_affected_rows($stmt)){
                mysqli_commit($conn);
                $_SESSION["discount_create"]="yes";
                header("location:index.php");
                exit;
            }else{
                throw new Exception("failed to insert to the database");
            }


    }catch(Exception $e){
        mysqli_rollback($conn);
        print "error".$e->getMessage();
    }
}



?>