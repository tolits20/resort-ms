<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include("../includes/system_update.php");

if(isset($_POST['update'])){
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $age = $_POST['age'];
    $gender = trim($_POST['gender']);
    $contact = trim($_POST['contact']);
    $username = trim($_POST['username']);
    $role = trim($_POST['role']);
    $id = $_SESSION['update_id'];
    $current_img = trim($_POST['current_img']);
    $img_path = $_FILES['file']['name'];
    $img_tmp = $_FILES['file']['tmp_name'];
    $allowed = array('jpg','jpeg','png','webp');

    try {
        mysqli_begin_transaction($conn);
        $sq1 = "UPDATE account SET username=?, role=?, updated_at=NOW() WHERE account_id=?";
        $stmt1 = mysqli_prepare($conn, $sq1);
        mysqli_stmt_bind_param($stmt1, 'ssi', $username, $role, $id);
        mysqli_stmt_execute($stmt1);

        if(mysqli_stmt_affected_rows($stmt1) > 0) {
            if($_FILES['file']['error'] == 0) {
                $file_ext = explode('.', $img_path);
                $extension = strtolower(end($file_ext));

                if(in_array($extension, $allowed)) {
                    $newfile = uniqid('', true) . "." . $extension;
                    $location = "../../resources/assets/images/" . $newfile;

                    $sql2 = "UPDATE user SET fname=?, lname=?, age=?, gender=?, contact=?, profile_img=? WHERE account_id=?";
                    $stmt2 = mysqli_prepare($conn, $sql2);
                    mysqli_stmt_bind_param($stmt2, 'ssisssi', $fname, $lname, $age, $gender, $contact, $newfile, $id);
                    mysqli_stmt_execute($stmt2);
                    
                    $notif = "INSERT INTO account_notification(account_id, message) VALUES ($id, '$fname $lname updated successfully!')";

                    if(mysqli_stmt_affected_rows($stmt2) > 0 && mysqli_query($conn, $notif)) {
                        if(file_exists("../../resources/assets/images/" . $current_img && !empty($current_img))) {
                            if(unlink("../../resources/assets/images/" . $current_img)) {
                                if(move_uploaded_file($img_tmp, $location)) {
                                    mysqli_commit($conn);
                                    header("location:edit.php?id=$id");
                                    $_SESSION['customer_update_success'] = 'yes';
                                    unset($_SESSION['update_id']);
                                    exit;
                                } else {
                                    throw new Exception("Failed to move the new image to the given location (1)");
                                }
                            } else {
                                throw new Exception('Failed to delete the current Profile Photo');
                            }
                        } else {
                            if(move_uploaded_file($img_tmp, $location)) {
                                mysqli_commit($conn);
                                header("location:edit.php?id=$id");
                                exit;
                            } else {
                                throw new Exception("Failed to move the new image to the given location (2)");
                            }
                        }
                    } else {
                        throw new Exception("Failed to update the record in the user table (1)");
                    }
                } else {
                    throw new Exception("Image type is invalid");
                }
            } else {
                $sql2 = "UPDATE user SET fname=?, lname=?, age=?, gender=?, contact=? WHERE account_id=?";
                $stmt2 = mysqli_prepare($conn, $sql2);
                mysqli_stmt_bind_param($stmt2, 'ssissi', $fname, $lname, $age, $gender, $contact, $id);
                $message="'$fname $lname Account is Updated Succesfully'";
                $notif = "INSERT INTO account_notification(account_id, message) VALUES ($id, $message)";
        
                if(mysqli_stmt_execute($stmt2) && mysqli_query($conn, $notif)) {
                    mysqli_commit($conn);
                    header("location:edit.php?id=$id");
                    $_SESSION['customer_update_success'] = 'yes';
                    unset($_SESSION['update_id']);
                    exit;
                } else {
                    throw new Exception("Failed to update the record in the user table (2)");
                }
            }
        } else {
            throw new Exception("Failed to update the record in account table");
        }
    } catch(Exception $e) {
        mysqli_rollback($conn);
        echo $e->getMessage();
        $_SESSION['customer_error'] = "yes";
        exit;
    }
}

if(isset($_POST['changepass'])){
    $ID = $_SESSION['update_id'];
    $newpass = trim($_POST['newpass']);
    $cpass = trim($_POST['cpass']);
    
    if($newpass === $cpass && preg_match("/[A-Za-z0-9%_\-\\@()]{8,30}/", $cpass)){
        $pass = sha1($cpass);

        try {
            mysqli_begin_transaction($conn);
            $sql = "UPDATE account SET password=? WHERE account_id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $pass, $ID);
            mysqli_stmt_execute($stmt);

            if(mysqli_stmt_affected_rows($stmt)) { 
                mysqli_commit($conn);
                header("location:edit.php?id=$ID");
                exit;   
            } else {
                throw new Exception("Failed to update the password");
            }
        } catch(Exception $e) {
            mysqli_rollback($conn);
            echo $e->getMessage();
        }
    }
}


?>