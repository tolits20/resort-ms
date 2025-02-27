<?php 
include('../../resources/database/config.php');

if(isset($_POST['update'])){
    echo $code=trim($_POST['room_code']);
    echo $type=trim($_POST['type']);
    echo $status=trim($_POST['status']);
    echo  $id=$_POST['room_id'];
    echo $price=$_POST['price'];
    $allowed=array('jpg','jpeg','png','webp');
    $c;

    try{
        mysqli_begin_transaction($conn);
        $sql1="UPDATE room SET room_code=?, room_type=?, room_status=?, price=?, updated_at=now() WHERE room_id=?";
        $notif="INSERT INTO room_notification(room_id,room_notification) Values($id,'update')";
        $stmt1=mysqli_prepare($conn,$sql1);
        mysqli_stmt_bind_param($stmt1,'sssii',$code,$type,$status,$price,$id);
        mysqli_stmt_execute($stmt1);
        if(mysqli_stmt_affected_rows($stmt1)>0 && mysqli_query($conn,$notif)){
            if(!empty($_FILES['images']['name'][0])){
                echo 'hello';
                foreach($_FILES['images']['tmp_name'] as $key => $tmp){
                    $name=$_FILES['images']['name'][$key];
                    $file_ext=explode('.',$name);
                    $extension=strtolower(end($file_ext));
                    if(in_array($extension,$allowed)){
                        $newfile=uniqid(''.true).".".$extension;
                        $locaton="../../resources/assets/room_images/".$newfile;
    
                        $sql2="INSERT INTO room_gallery (room_id,room_img,created_at) VALUES (?,?,now()) ";
                        $stmt2=mysqli_prepare($conn,$sql2);
                        mysqli_stmt_bind_param($stmt2,'is',$id,$newfile);
                        mysqli_stmt_execute($stmt2);
    
                        if(mysqli_stmt_affected_rows($stmt2)>0){
                            if(move_uploaded_file($tmp,$locaton)){
                                $c++;
                            }else{
                                throw new Exception("faild to move this file");
                            }
                        }else{
                            throw new Exception("faild to insert this image");
                        }
    
                    }else{
                        throw new Exception("file type is invalid");
                    }
    
                }
                if($c>=1){
                    $_SESSION['room_update']="yes";
                    mysqli_commit($conn);
                    header('location:index.php');
                    exit;
                }
           }else{
            $_SESSION['room_update']="yes";
            mysqli_commit($conn);
            header("location:edit.php?id=$id");
            exit;
           }
        }else{
            throw new Exception("faile to update the existing record");
        }
    }catch(Exception $e){
        mysqli_rollback($conn);
        $e->getMessage();
        exit;
    }
}

?>