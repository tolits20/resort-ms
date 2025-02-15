<?php 
include ('../includes/template.html');
include('../../resources/database/config.php');

if(isset($_POST['update'])){
    echo $fname=trim($_POST['fname']);
    echo $lname=trim($_POST['lname']);
    echo $age=$_POST['age'];
    echo $gender=trim($_POST['gender']);
    echo $contact=trim($_POST['contact']);
    echo $username=trim($_POST['username']);
    echo $role=trim($_POST['role']);
    echo $id=$_SESSION['update_id'];
    echo $current_img=trim($_POST['current_img']);
    echo $img_path= $_FILES['file']['name'];
    echo $img_tmp= $_FILES['file']['tmp_name'];
    $allowed=array('jpg','jpeg','png','webp');

    try{
        mysqli_begin_transaction($conn);
        $sq1="UPDATE account SET username=?, role=?, updated_at=NOW() WHERE account_id=? ";
        $stmt1=mysqli_prepare($conn,$sq1);
        mysqli_stmt_bind_param($stmt1,'ssi',$username,$role,$id);
        mysqli_stmt_execute($stmt1);

        if(mysqli_stmt_affected_rows($stmt1)>0){
            if($_FILES['file']['error']==0){
                $file_ext=explode('.',$img_path);
                $extension=strtolower(end($file_ext));

                if(in_array($extension,$allowed)){
                    $newfile=uniqid('',true).".".$extension;
                    $location="../../resources/assets/images/".$newfile;

                    $sql2="UPDATE user SET fname=?, lname=?, age=?, gender=?, contact=?,profile_img=?, updated_at=NOW() WHERE account_id=?";
                    $stmt2=mysqli_prepare($conn,$sql2);
                    mysqli_stmt_bind_param($stmt2,'ssisssi',$fname,$lname,$age,$gender,$contact,$newfile,$id);
                    mysqli_stmt_execute($stmt2);

                    if(mysqli_stmt_affected_rows($stmt2)>0){
                        if(file_exists("../../resources/assets/images/".$current_img)){
                            if(unlink("../../resources/assets/images/".$current_img)){
                                if(move_uploaded_file($img_tmp,$location)){
                                    mysqli_commit($conn);
                                    header("location:edit.php?id=$id");
                                    unset($_SESSION['update_id']);
                                    exit;
                                }else{
                                    throw new Exception("failed to move the new image to the given location (1)");
                                }
                            }else{
                                throw new Exception('faild to delete the current Profile Photo');
                            }
                        }else{
                            if(move_uploaded_file($img_tmp,$location)){
                                mysqli_commit($conn);
                                header("location:edit.php?id=$id");
                                exit;
                            }else{
                                throw new Exception("failed to move the new image to the given location (2)");
                            }
                        }
                    }else{
                        throw new Exception("Faile to update the record in the user table(1)");
                    }
                }else{
                    throw new Exception("image type is invalid");
                }
                
            }else{
                $sql2="UPDATE user SET fname=?, lname=?, age=?, gender=?, contact=?, updated_at=NOW() WHERE account_id=?";
                $stmt2=mysqli_prepare($conn,$sql2);
                mysqli_stmt_bind_param($stmt2,'ssissi',$fname,$lname,$age,$gender,$contact,$id);
                mysqli_stmt_execute($stmt2);
                if(mysqli_stmt_affected_rows($stmt2)>0){
                    mysqli_commit($conn);
                    header("location:edit.php?id=$id");
                    unset($_SESSION['update_id']);
                    exit;
                }else{
                    throw new Exception("Faile to update the record in the user table(2)");
                }
            }
        }else{
            throw new Exception("failed to  update the record in account table");
        }
    }catch(Exception $e){
        mysqli_rollback($conn);
        echo $e->getMessage();
        header('location:');
        exit;
    }

}

if(isset($_POST['changepass'])){
    echo $ID=$_SESSION['update_id'];
    echo $newpass=trim($_POST['newpass']);
    echo $cpass=trim($_POST['cpass']);
    
    if($newpass===$cpass &&  preg_match("/[A-Za-z0-9%_\-\\@()]{8,30}/", $cpass)){
        $pass=sha1($cpass);

    try{
        mysqli_begin_transaction($conn);
        $sql="UPDATE account SET password=? WHERE account_id=?";
        $stmt=mysqli_prepare($conn,$sql);
        mysqli_stmt_bind_param($stmt,'si',$pass,$ID);
        mysqli_stmt_execute($stmt);

        if(mysqli_stmt_affected_rows($stmt)){
            mysqli_commit($conn);
            header("location:edit.php?id=$id");
            exit;
        }else{
            throw new Exception("failed to update the password");
        }

    }catch(Exception $e){
        mysqli_rollback($conn);
        echo $e->getMessage();

    }
}

}
?>