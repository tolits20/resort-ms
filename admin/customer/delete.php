<?php 
include('../../resources/database/config.php');
include ('../includes/template.html');
include("../includes/system_update.php");

if(isset($_POST['no'])){
    header('location:index.php');
    exit;
}

if(isset($_POST['yes'])){
   echo $id=$_GET['id'];
    $sql1="SELECT * FROM user WHERE account_id=$id";
    $result=mysqli_query($conn,$sql1);
    $row=mysqli_fetch_assoc($result);

    echo $path="../../resources/assets/images/".$row['profile_img'];
    try{

        if(file_exists($path)){
            if(unlink($path)){
                $sql="DELETE FROM account WHERE account_id=?";
                $stmt=mysqli_prepare($conn,$sql);
                mysqli_stmt_bind_param($stmt,'i',$id);
                mysqli_stmt_execute($stmt);

                if(mysqli_stmt_affected_rows($stmt)>0){
                    mysqli_commit($conn);
                    header('location:index.php');
                    exit;
                }else{
                    throw new Exception("faile to delete the account");
                }
            }else{
                throw new Exception("cant unlink the current profile of the user");
            }
        }else{
            $sql="DELETE FROM account WHERE account_id=?";
            $stmt=mysqli_prepare($conn,$sql);
            mysqli_stmt_bind_param($stmt,'i',$id);
            mysqli_stmt_execute($stmt);

            if(mysqli_stmt_affected_rows($stmt)>0){
                mysqli_commit($conn);
                header('location:index.php');
                exit;
            }else{
                throw new Exception("faile to delete the account (2)");
            }
        }
        }catch(Exception $e){
            mysqli_rollback($conn);
            print $e->getMessage();
            exit;
        }

    
}
?>