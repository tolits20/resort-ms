<?php 
include('../../resources/database/config.php');
include("../includes/system_update.php");

if(isset($_POST['create'])){

    $fname = trim(ucwords($_POST['fname']));
    $lname = trim(ucwords($_POST['lname']));
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $username = trim($_POST['username']);
    $password = sha1($_POST['password']);
    $auto_admin="SELECT * FROM account WHERE role='admin'";
    $result = mysqli_query($conn, $auto_admin);
    $role;
    if(mysqli_num_rows($result) == 0){
        $role = 'admin';
    } else {
    $role = 'user';
    }
    $img_path = $_FILES['file']['name'];
    $img_tmp = $_FILES['file']['tmp_name'];
    $allowed = array('jpg','jpeg','png','webp');

    try {
        mysqli_begin_transaction($conn);
        $sql1 = "INSERT INTO account(username,password,role,created_at) VALUES (?,?,?,now())";
        $stmt1 = mysqli_prepare($conn, $sql1);
        mysqli_stmt_bind_param($stmt1, 'sss', $username, $password, $role);
        mysqli_stmt_execute($stmt1);

        if(mysqli_stmt_affected_rows($stmt1) > 0) {
            $last_id = mysqli_insert_id($conn);
            if($_FILES['file']['error'] == 0) {
                $file_ext = explode('.', $img_path);
                $extension = strtolower(end($file_ext));
                if(in_array($extension, $allowed)) {
                    $newfile = uniqid('', true) . "." . $extension;
                    $location = "../../resources/assets/images/" . $newfile;

                    $sql2 = "INSERT INTO user(account_id,fname,lname,age,gender,contact,profile_img) VALUES (?,?,?,?,?,?,?)";
                    $stmt2 = mysqli_prepare($conn, $sql2);
                    mysqli_stmt_bind_param($stmt2, 'issisis', $last_id, $fname, $lname, $age, $gender, $contact, $newfile);
                    mysqli_stmt_execute($stmt2);
                    
                    $notif = "INSERT INTO account_notification(account_id,account_notification) VALUES ($last_id,'create')";

                    if(mysqli_stmt_affected_rows($stmt2) > 0 && mysqli_query($conn, $notif)) {
                        if(move_uploaded_file($img_tmp, $location)) {
                            $_SESSION['create_success'] = 'yes';
                            mysqli_commit($conn);
                            if(isset($_SESSION['ID'])) {
                                header("location:index.php");
                                exit;
                            } else {  
                                header("location:../../login.php");
                                exit; 
                            }
                        } else {
                            throw new Exception("Failed to move the file");
                        }
                    } else {
                        throw new Exception("Failed to insert in user table");
                    }
                } else {
                    throw new Exception("Invalid file type");
                }
            } else {
                $sql2 = "INSERT INTO user(account_id,fname,lname,age,gender,contact,profile_img) VALUES (?,?,?,?,?,?,?)";
                $stmt2 = mysqli_prepare($conn, $sql2);
                mysqli_stmt_bind_param($stmt2, 'issisis', $last_id, $fname, $lname, $age, $gender, $contact, $newfile);
                mysqli_stmt_execute($stmt2);

                $notif = "INSERT INTO account_notification(account_id,account_notification) VALUES ($last_id,'create')";

                if(mysqli_stmt_affected_rows($stmt2) > 0 && mysqli_query($conn, $notif)) {
                    $_SESSION['create_success'] = 'yes';
                    mysqli_commit($conn);
                    header("location:../../login.php");
                }
            }
        } else {
            throw new Exception("Failed to insert in account table");
        }
    } catch(Exception $e) {
        mysqli_rollback($conn);
        echo $e->getMessage();
    }
}
?>