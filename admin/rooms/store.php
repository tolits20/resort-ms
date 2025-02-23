<?php 
include('../../resources/database/config.php');
print "<pre>";
var_dump($_FILES);
var_dump($_POST);
print "</pre>";

$room_num=trim($_POST['room_number']);
$type=trim($_POST['type']);
$status=trim($_POST['status']);
$price=$_POST['price'];
$allowed=array('jpg','jpeg','png','webp');
$c;

try{
    mysqli_begin_transaction($conn);
    $sql1="INSERT INTO room(room_code,room_type,room_status,price,created_at)VALUES(?,?,?,?,now())  ";
    $stmt1=mysqli_prepare($conn,$sql1);
    mysqli_stmt_bind_param($stmt1,'sssi',$room_num,$type,$status,$price);
    mysqli_stmt_execute($stmt1);
    $last_id=mysqli_insert_id($conn);
    if(mysqli_stmt_affected_rows($stmt1)>0){
       if(!empty($_FILES['images']['name'][0])){
            $locaton="../../resources/assets/images/";
            foreach($_FILES['images']['tmp_name'] as $key => $tmp){
                $name=$_FILES['images']['name'][$key];
                $file_ext=explode('.',$name);
                $extension=strtolower(end($file_ext));
                if(in_array($extension,$allowed)){
                    $newfile=uniqid(''.true).".".$extension;
                    $locaton="../../resources/assets/room_images/".$newfile;

                    $sql2="INSERT INTO room_gallery (room_id,room_img,created_at) VALUES (?,?,now()) ";
                    $stmt2=mysqli_prepare($conn,$sql2);
                    mysqli_stmt_bind_param($stmt2,'is',$last_id,$newfile);
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
                mysqli_commit($conn);
                header('location:index.php');
                exit;
            }
       }else{
        mysqli_commit($conn);
        header('location:index.php');
        exit;
       }
    }else{
        throw new Exception("faild to Insert the room");
    }

}catch(exception $e){
    mysqli_rollback($conn);
    print $e->getMessage();
}


?>