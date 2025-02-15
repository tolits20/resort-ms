<?php 
include('../../resources/database/config.php');

if(isset($_GET['click'])){
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

if(isset($_GET['index_click'])){
    echo $id=$_GET['id'];
    try{
        mysqli_begin_transaction($conn);
        $sql="SELECT * FROM room_gallery WHERE room_id=$id";
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            while($del=mysqli_fetch_assoc($result)){
                if(!unlink("../../resources/assets/room_images/".$del['room_img'])){
                    throw new Exception("failed to delete this file");
                }
            }
            $sql2="DELETE FROM room WHERE room_id=?";
            $stmt=mysqli_prepare($conn,$sql2);
            mysqli_stmt_bind_param($stmt,'i',$id);
            mysqli_stmt_execute($stmt);

            if(mysqli_stmt_affected_rows($stmt)>0){
                mysqli_commit($conn);
                header("location:index.php");
                exit;
            }
        }else{
            $sql2="DELETE FROM room WHERE room_id=?";
            $stmt=mysqli_prepare($conn,$sql2);
            mysqli_stmt_bind_param($stmt,'i',$id);
            mysqli_stmt_execute($stmt);

            if(mysqli_stmt_affected_rows($stmt)>0){
                mysqli_commit($conn);
                header("location:index.php");
                exit;
            }
        }
    }catch(Exception $e){
        mysqli_rollback($conn);
         print $e->getMessage();
        exit;
    }
}

?>