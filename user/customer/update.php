<?php
include ('../../resources/database/config.php');
include("../../admin/includes/system_update.php");

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

// Function to display error popup and go back
function showErrorPopup($message) {
    echo "<script>
        alert('Error: $message');
        window.history.back();
    </script>";
    exit;
}

// Function to display success popup
function showSuccessPopup($message, $redirectUrl = null) {
    echo "<script>
        alert('Success: $message');
        " . ($redirectUrl ? "window.location.href = '$redirectUrl';" : "") . "
    </script>";
    exit;
}

if (isset($_POST['update'])) {
    try {
        $UID = $_SESSION['update_id'];
        $ID = $_POST['ID'];
        $fname = trim(ucwords($_POST['fname']));
        $lname = trim(ucwords($_POST['lname']));
        $age = trim($_POST['age']);
        $gender = trim($_POST['gender']);
        $contact = trim($_POST['contact']);
        $username = trim($_POST['username']);
        $current_image = trim($_POST['current']);
        $filename = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $allowed = array('png', 'jpg', 'jpeg');
        $file_delete = trim($_POST['current']);

        // Input validation
        if (empty($fname)) throw new Exception("First name is required");
        if (empty($lname)) throw new Exception("Last name is required");
        if (!preg_match("/^[a-zA-Z\s]+$/", $fname)) throw new Exception("First name contains invalid characters");
        if (!preg_match("/^[a-zA-Z\s]+$/", $lname)) throw new Exception("Last name contains invalid characters");
        if ($age < 12 || $age > 120) throw new Exception("Age must be between 12 and 120");
        if (empty($contact)) throw new Exception("Contact number is required");
        if (empty($username)) throw new Exception("Username is required");

        mysqli_begin_transaction($conn);
        
        $sql_account = "UPDATE account SET username=?, updated_at=NOW() WHERE account_id=?";
        $stmt_account = mysqli_prepare($conn, $sql_account);
        mysqli_stmt_bind_param($stmt_account, 'si', $username, $ID);
        mysqli_stmt_execute($stmt_account);

        if (mysqli_stmt_affected_rows($stmt_account) <= 0) {
            throw new Exception("No changes made to account information");
        }

        if ($_FILES['file']['error'] == 0) {
            $file_ext = explode('.', $filename);
            $extension = strtolower(end($file_ext));

            if (!in_array($extension, $allowed)) {
                throw new Exception("Only PNG, JPG, and JPEG files are allowed");
            }

            $newfile = uniqid('.', true) . "." . $extension;
            $location = "../../resources/assets/images/" . $newfile;

            $sql_with_img = "UPDATE user
                            SET fname=?, lname=?, age=?, gender=?, contact=?, profile_img=?
                            WHERE account_id=?";
            $stmt_with_img = mysqli_prepare($conn, $sql_with_img);
            mysqli_stmt_bind_param($stmt_with_img, 'ssisisi', $fname, $lname, $age, $gender, $contact, $newfile, $ID);
            mysqli_stmt_execute($stmt_with_img);

            if (mysqli_stmt_affected_rows($stmt_with_img) <= 0) {
                throw new Exception("Failed to update user profile with image");
            }

            if (file_exists("../../resources/assets/images/" . $current_image) && !empty($file_delete)) {
                if (!unlink("../../resources/assets/images/" . $current_image)) {
                    throw new Exception("Failed to delete the current profile picture");
                }
            }

            if (!move_uploaded_file($file_tmp, $location)) {
                throw new Exception("Failed to upload new profile picture");  
            }

            mysqli_commit($conn);
            showSuccessPopup("Profile updated successfully!", "edit.php?id=$ID");
            unset($_SESSION['update_id']);
        } else {
            $sql_no_img = "UPDATE user SET fname=?, lname=?, age=?, gender=?, contact=? WHERE account_id=?";
            $stmt_no_img = mysqli_prepare($conn, $sql_no_img);
            mysqli_stmt_bind_param($stmt_no_img, 'ssisii', $fname, $lname, $age, $gender, $contact, $ID);
            mysqli_stmt_execute($stmt_no_img);
            
            if (mysqli_stmt_affected_rows($stmt_no_img) <= 0) {
                throw new Exception("Failed to update user profile");
            }

            mysqli_commit($conn);
            showSuccessPopup("Profile updated successfully!", "edit.php?id=$ID");
            unset($_SESSION['update_id']);
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        showErrorPopup($e->getMessage());
    }
}

if (isset($_SESSION['ID'])) {
    if(isset($_POST['update_password'])) {
        mysqli_begin_transaction($conn);
        try {
            // Validate old password
            if (empty($_POST['old_password'])) {
                throw new Exception("Current password is required");
            }
            $old = sha1(trim($_POST['old_password']));
            $ID = $_SESSION['ID'];

            // Fetch current password
            $sql_fetch = "SELECT password FROM account WHERE account_id = ?";
            $stmt_fetch = mysqli_prepare($conn, $sql_fetch);
            if (!$stmt_fetch) {
                throw new Exception("System error. Please try again.");
            }

            mysqli_stmt_bind_param($stmt_fetch, 'i', $ID);
            mysqli_stmt_execute($stmt_fetch);
            $result = mysqli_stmt_get_result($stmt_fetch);
            $row = mysqli_fetch_assoc($result);

            if (!$row) {
                throw new Exception("User not found.");
            }
            
            // Verify old password
            if ($row['password'] !== $old) {
                throw new Exception("Current password is incorrect");
            }

            // Validate new passwords
            if (empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
                throw new Exception("New password and confirmation are required");
            }

            $npass = trim($_POST['new_password']);
            $cpass = trim($_POST['confirm_password']);

            if (strlen($npass) < 8) {
                throw new Exception("Password must be at least 8 characters");
            }
            
            if ($npass !== $cpass) {
                throw new Exception("New passwords do not match");
            }

            if (!preg_match("/[a-zA-Z0-9#%@_-]/", $npass)) {
                throw new Exception("Password contains invalid characters");
            }

            // Update password
            $final = sha1($npass);
            $sql_pass = "UPDATE account SET password = ? WHERE account_id = ?";
            $stmt_pass = mysqli_prepare($conn, $sql_pass);
            if (!$stmt_pass) {
                throw new Exception("System error. Please try again.");
            }

            mysqli_stmt_bind_param($stmt_pass, 'si', $final, $ID);
            if (!mysqli_stmt_execute($stmt_pass)) {
                throw new Exception("Failed to update password. Please try again.");
            }

            // Check if password was actually updated
            if (mysqli_stmt_affected_rows($stmt_pass) <= 0) {
                throw new Exception("No changes made to password");
            }

            mysqli_commit($conn);
            showSuccessPopup("Password updated successfully!", "edit.php");
            
        } catch (Exception $e) {
            mysqli_rollback($conn);
            showErrorPopup($e->getMessage());
        }
    }
}
?>