<?php
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])){
    header("location: ../../login.php");
    exit;
}

// if (isset($_SESSION['ID'])){
//     if(isset($_POST['update_password'])){
//         mysqli_begin_transaction($conn);
//         try {
//             $old = sha1(trim($_POST['old_password']));
//             $ID = $_SESSION['ID'];

//             // Fetch stored password for validation
//             $sql_fetch = "SELECT password FROM account WHERE account_id = ?";
//             $stmt_fetch = mysqli_prepare($conn, $sql_fetch);
//             if (!$stmt_fetch) throw new Exception("Failed to prepare statement for fetching password.");

//             mysqli_stmt_bind_param($stmt_fetch, 'i', $ID);
//             mysqli_stmt_execute($stmt_fetch);
//             $result = mysqli_stmt_get_result($stmt_fetch);
//             $row = mysqli_fetch_assoc($result);

//             if (!$row) throw new Exception("User not found.");
//             if ($row['password'] !== $old) throw new Exception("Current password didn't match.");

//             $npass = trim($_POST['new_password']);
//             $cpass = trim($_POST['confirm_password']);

//             if (preg_match("/[a-zA-Z0-9#%@_-]/", $cpass) && strlen($cpass) >= 8 && $npass == $cpass) {
//                 $final = sha1($cpass);
//                 $sql_pass = "UPDATE account SET password = ? WHERE account_id = ?";
//                 $stmt_pass = mysqli_prepare($conn, $sql_pass);
//                 if (!$stmt_pass) throw new Exception("Failed to prepare statement for updating password.");

//                 mysqli_stmt_bind_param($stmt_pass, 'si', $final, $ID);
//                 if (!mysqli_stmt_execute($stmt_pass)) throw new Exception("Failed to update password.");

//                 mysqli_commit($conn);
//                 header("location: edit.php");
//                 exit;
//             } else {
//                 throw new Exception("New password didn't match.");
//             }
//         } catch (Exception $e) {
//             mysqli_rollback($conn);
//             echo "Error: " . $e->getMessage();
//         }
//     }
// }

if (isset($_POST['update'])){
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
        echo $filename = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $allowed = array('png', 'jpg', 'jpeg');
        $file_delete = trim($_POST['current']);
        echo $current_path = "../../resources/assets/images/" . ((!empty($file_delete)) ? $file_delete : 'default.png');

        if (
            preg_match("/^[a-zA-Z\s]+$/", $fname) &&
            preg_match("/^[a-zA-Z\s]+$/", $lname) &&
            ($age >= 12 && $age <= 120) 
            // strlen($contact) == 11 &&
            // preg_match("/[a-zA-Z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}/", $username)
        ) {
            echo 'Valid';
            mysqli_begin_transaction($conn);

            $sql2 = "UPDATE user SET fname = ?, lname = ?, age = ?, gender = ?, updated_at = NOW() WHERE account_id = ?";
            $stmt2 = mysqli_prepare($conn, $sql2);
            if (!$stmt2) throw new Exception("Failed to prepare statement for updating user info.");

            mysqli_stmt_bind_param($stmt2, 'ssisi', $fname, $lname, $age, $gender, $ID);
            if (!mysqli_stmt_execute($stmt2)) throw new Exception("Failed to execute user update.");

            if (mysqli_stmt_affected_rows($stmt2) >= 0) {
                if ($_FILES['file']['error'] == 0) {
                    $file_ext = explode('.', $filename);
                    $extension = strtolower(end($file_ext));

                    if (in_array($extension, $allowed)) {
                        $newfile = uniqid('', true) . "." . $extension;
                        $location = "../../resources/assets/images/" . $newfile;

                        $sql3 = "UPDATE account SET username = ? WHERE account_id = ?";
                        $stmt3 = mysqli_prepare($conn, $sql3);
                        
                        mysqli_stmt_bind_param($stmt3, 'si', $username, $ID);
                        mysqli_stmt_execute($stmt3);
                        if (mysqli_stmt_affected_rows($stmt3)>0) {
                            echo 'valid 2';
                            if (file_exists($current_path) && unlink($current_path)) {
                                throw new Exception("Failed to delete existing profile picture.");
                            }

                            if (!move_uploaded_file($file_tmp, $location)) {
                                throw new Exception("Failed to move uploaded file.");
                            }else{
                                mysqli_commit($conn);
                                header("location: edit.php");
                            }

                           
                            exit;
                        } else {
                            throw new Exception("Failed to update account.$conn->error");
                        }
                    } else {
                        throw new Exception("Invalid image format.");
                    }
                } else {
                    throw new Exception("File upload error: " . $_FILES['file']['error']);
                }
            } else {
                throw new Exception("User update failed.");
            }
        } else {
           throw new Exception("The Value is not valid");
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage(),$e->getFile().$e->getCode();
    }
}
?>
