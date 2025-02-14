<?php 
include("../../resources/database/config.php");

if(isset($_POST['create'])){

echo $fname=trim(ucwords($_POST['fname']));
echo $lname=trim(ucwords($_POST['lname']));
echo $age=$_POST['age'];
echo $gender=$_POST['gender'];
echo $contact=$_POST['contact'];
echo $username = trim($_POST['username']);
echo $password= sha1($_POST['password']);
echo $role=trim($_POST['role']);
echo $img_path= $_FILES['file']['name'];
echo $img_tmp= $_FILES['file']['tmp_name'];
$allowed=array('jpg','jpeg','png','webp');


try{
    mysqli_begin_transaction($conn);
    $sql1="INSERT INTO account(username,password,role,created_at)VALUES(?,?,?,now())";
    $stmt1=mysqli_prepare($conn,$sql1);
    mysqli_stmt_bind_param($stmt1,'sss',$username,$password,$role);
    mysqli_stmt_execute($stmt1);

    if(mysqli_stmt_affected_rows($stmt1)>0){
       echo $last_id=mysqli_insert_id($conn);
        if($_FILES['file']['error']==0){
            $file_ext=explode('.',$img_path);
            $extension=strtolower(end($file_ext));
            if(in_array($extension,$allowed)){
                $newfile=uniqid('',true).".".$extension;
                $location="../../resources/assets/images/".$newfile;

                $sql2="INSERT INTO user(account_id,fname,lname,age,gender,contact,profile_img,created_at)VALUES(?,?,?,?,?,?,?,now())";
                $stmt2=mysqli_prepare($conn,$sql2);
                mysqli_stmt_bind_param($stmt2,'issisis',$last_id,$fname,$lname,$age,$gender,$contact,$newfile);
                mysqli_stmt_execute($stmt2);
                
                if(mysqli_stmt_affected_rows($stmt2)>0){
                    if(move_uploaded_file($img_tmp,$location)){
                        mysqli_commit($conn);
                        header("location:../../login.php");
                        exit;
                    }else{
                        throw new Exception("falied to move the file");
                    }
                }else{
                    throw new  Exception("failed to insert in user table");
                }
                
                
            }else{
                throw new Exception("Invalid File type");
            }

        }else{
            $sql2="INSERT INTO user(account_id,fname,lname,age,gender,contact,profile_img,created_at)VALUES(?,?,?,?,?,?,?,now())";
                $stmt2=mysqli_prepare($conn,$sql2);
                mysqli_stmt_bind_param($stmt2,'issisis',$last_id,$fname,$lname,$age,$gender,$contact,$newfile);
                mysqli_stmt_execute($stmt2);
            if(mysqli_stmt_affected_rows($stmt2)>0){
                mysqli_commit($conn);
                header("location:../../login.php");
            }
        }


    }else{
        throw new Exception("failed to insert in account table");
    }


   
 
    
   
}catch(Exception $e){
    mysqli_rollback($conn);
    echo $e->getMessage();
}


}



?>