<?php 
include('../../resources/database/config.php');

if($_GET['click']='true'){
    try{
        $image=$_GET['image'];
        $id=$_GET['room_id'];
        $sql1="DELETE FROM room_gallery WHERE room_img=?";
        $stmt1=mysqli_prepare($conn,$sql1);
        mysqli_stmt_bind_param($stmt1,'s',$image);
        mysqli_stmt_execute($stmt1);

        if(mysqli_stmt_affected_rows($stmt1)>0){
            if(file_exists("../../resources/assets/room_images/".$image)){
                if(unlink("../../resources/assets/room_images/".$image)){
                    mysqli_commit($conn);
                    header("location:edit.php?id=$id");
                    exit;
                }else{
                    throw new Exception("failed to delete the image");
                }
            }else{
                throw new Exception("failed to locate the image");
            }
        }else{
            throw new Exception("failed to delete the image to the database");
        }
        
    }catch(exception $e){
        mysqli_rollback($conn);
        print $e->getMessage();
        exit;
    }
    
}


?>