<?php

include ('../../resources/database/config.php');
include("../../admin/includes/system_update.php");

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

if (isset($_POST['update'])) {
    try {
        echo $_FILES['file']['error'];
        echo $UID = $_SESSION['update_id'];
        $ID = $_POST['ID'];
        echo $fname = trim(ucwords($_POST['fname']));
        echo $lname = trim(ucwords($_POST['lname']));
        echo $age = trim($_POST['age']);
        echo $gender = trim($_POST['gender']);
        echo $contact = trim($_POST['contact']);
        echo $username = trim($_POST['username']);
        echo $current_image = trim($_POST['current']);
        echo $filename = $_FILES['file']['name'];
        echo "../../resources/assets/images/" . $current_image;
        $file_tmp = $_FILES['file']['tmp_name'];
        $allowed = array('png', 'jpg', 'jpeg');
        $file_delete = trim($_POST['current']);

        if (
            preg_match("/^[a-zA-Z\s]+$/", $fname) &&
            preg_match("/^[a-zA-Z\s]+$/", $lname) &&
            ($age >= 12 && $age <= 120)
        ) {
            mysqli_begin_transaction($conn);
            
            $sql_account = "UPDATE account SET username=?, updated_at=NOW() WHERE account_id=?";
            $stmt_account = mysqli_prepare($conn, $sql_account);
            mysqli_stmt_bind_param($stmt_account, 'si', $username, $ID);
            mysqli_stmt_execute($stmt_account);

            if (mysqli_stmt_affected_rows($stmt_account) > 0) {
                if ($_FILES['file']['error'] == 0) {
                    $file_ext = explode('.', $filename);
                    $extension = strtolower(end($file_ext));

                    if (in_array($extension, $allowed)) {
                        $newfile = uniqid('.', true) . "." . $extension;
                        $location = "../../resources/assets/images/" . $newfile;

                        $sql_with_img = "UPDATE user
                                        SET fname=?, lname=?, age=?, gender=?, contact=?, profile_img=?
                                        WHERE account_id=?";
                        $stmt_with_img = mysqli_prepare($conn, $sql_with_img);
                        mysqli_stmt_bind_param($stmt_with_img, 'ssisisi', $fname, $lname, $age, $gender, $contact, $newfile, $ID);
                        mysqli_stmt_execute($stmt_with_img);

                        if (mysqli_stmt_affected_rows($stmt_with_img) > 0) {
                            if (file_exists("../../resources/assets/images/" . $current_image) && !empty($file_delete)) {
                                if (!unlink("../../resources/assets/images/" . $current_image)) {
                                    throw new Exception("Failed to delete the current profile picture");
                                }
                            }
                            if (move_uploaded_file($file_tmp, $location)) {
                                mysqli_commit($conn);
                                header("location: edit.php?id=$ID");
                                unset($_SESSION['update_id']);
                                exit;
                            } else {
                                throw new Exception("Failed to move the new image to the given location");  
                            }
                        } else {
                            throw new Exception("Failed to update the record in the user table (with image)");
                        }
                    } else {
                        throw new Exception("Image type is invalid");
                    }
                } else {
                    $sql_no_img = "UPDATE user SET fname=?, lname=?, age=?, gender=?, contact=? WHERE account_id=?";
                    $stmt_no_img = mysqli_prepare($conn, $sql_no_img);
                    mysqli_stmt_bind_param($stmt_no_img, 'ssisii', $fname, $lname, $age, $gender, $contact, $ID);
                    mysqli_stmt_execute($stmt_no_img);
                    if (mysqli_stmt_affected_rows($stmt_no_img) > 0) {
                        mysqli_commit($conn);
                        header("location: edit.php?id=$ID");
                        unset($_SESSION['update_id']);
                        exit;
                    } else {
                        throw new Exception("Failed to update the record in the user table (without image)");
                    }
                }
            } else {
                throw new Exception("Failed to update the record in the account table");
            }
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo $e->getMessage();
        header('location:'); 
        exit;
    }
}
if (isset($_SESSION['ID'])){
    if(isset($_POST['update_password'])){
        mysqli_begin_transaction($conn);
        try {
            $old = sha1(trim($_POST['old_password']));
            $ID = $_SESSION['ID'];

            $sql_fetch = "SELECT password FROM account WHERE account_id = ?";
            $stmt_fetch = mysqli_prepare($conn, $sql_fetch);
            if (!$stmt_fetch) throw new Exception("Failed to prepare statement for fetching password.");

            mysqli_stmt_bind_param($stmt_fetch, 'i', $ID);
            mysqli_stmt_execute($stmt_fetch);
            $result = mysqli_stmt_get_result($stmt_fetch);
            $row = mysqli_fetch_assoc($result);

            if (!$row) throw new Exception("User not found.");
            if ($row['password'] !== $old) throw new Exception("Current password didn't match.");

            $npass = trim($_POST['new_password']);
            $cpass = trim($_POST['confirm_password']);

            if (preg_match("/[a-zA-Z0-9#%@_-]/", $cpass) && strlen($cpass) >= 8 && $npass == $cpass) {
                $final = sha1($cpass);
                $sql_pass = "UPDATE account SET password = ? WHERE account_id = ?";
                $stmt_pass = mysqli_prepare($conn, $sql_pass);
                if (!$stmt_pass) throw new Exception("Failed to prepare statement for updating password.");

                mysqli_stmt_bind_param($stmt_pass, 'si', $final, $ID);
                if (!mysqli_stmt_execute($stmt_pass)) throw new Exception("Failed to update password.");

                mysqli_commit($conn);
                header("location: edit.php");
                exit;
            } else {
                throw new Exception("New password didn't match.");
            }
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "Error: " . $e->getMessage();
        }
    }
}

?>
