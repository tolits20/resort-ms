<?php 
include("../../resources/database/config.php");

if(isset($_POST['create'])){

echo $name=trim(ucwords($_POST['fname'])." ".ucwords($_POST['lname']));
echo $age=$_POST['age'];
echo $gender=$_POST['gender'];
echo $contact=$_POST['contact'];
echo $email = $_POST['email'];

try{
    mysqli_begin_transaction($conn);
    $sql1="INSERT INTO customer(name,age,gender,contact,email,created_at)values(?,?,?,?,?,now())";
    $stmt=mysqli_prepare($conn,$sql1);
    mysqli_stmt_bind_param($stmt,'sisis',$name,$age,$gender,$contact,$email);
    mysqli_stmt_execute($stmt);
    if(mysqli_stmt_affected_rows($stmt)>0){
        mysqli_commit($conn);
        header('location:create.php');
        exit;
    }else{
        throw new Exception('Failed to insert the customer');
    }
}catch(Exception $e){
    mysqli_rollback($conn);
    echo $e->getMessage();
}


}



?>