<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include("../includes/system_update.php");

if(isset($_POST['no'])){
    header('location:index.php');
    exit;
}

if(isset($_POST['yes'])){
    $id = $_GET['id'];
    $sql = "UPDATE account SET deleted_at=now() WHERE account_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    if(mysqli_stmt_affected_rows($stmt) > 0){
        mysqli_commit($conn);
        header('location:index.php');
        exit;
    }
}

if(isset($_GET['restore'])){
    $id = $_GET['restore'];
    $sql = "UPDATE account SET deleted_at=NULL WHERE account_id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    if(mysqli_stmt_affected_rows($stmt) > 0){
        mysqli_commit($conn);
        header('location:../activity_logs/index.php');
        exit;
    }
}

if(isset($_GET['delete_permanent'])){
    $id = $_GET['delete_permanent'];
    $sql1 = "SELECT * FROM user WHERE account_id=?";
    $stmt1 = mysqli_prepare($conn, $sql1);
    mysqli_stmt_bind_param($stmt1, 'i', $id);
    mysqli_stmt_execute($stmt1);
    $result = mysqli_stmt_get_result($stmt1);
    $row = mysqli_fetch_assoc($result);

    $path = "../../resources/assets/images/" . $row['profile_img'];
    try {
        if(file_exists($path)){
            if(unlink($path)){
                $sql = "DELETE FROM account WHERE account_id=?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);

                if(mysqli_stmt_affected_rows($stmt) > 0){
                    mysqli_commit($conn);
                    header('location:index.php');
                    exit;
                } else {
                    throw new Exception("Failed to delete the account");
                }
            } else {
                throw new Exception("Cannot unlink the current profile of the user");
            }
        } else {
            $sql = "DELETE FROM account WHERE account_id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);

            if(mysqli_stmt_affected_rows($stmt) > 0){
                mysqli_commit($conn);
                header('location:index.php');
                exit;
            } else {
                throw new Exception("Failed to delete the account (2)");
            }
        }
    } catch(Exception $e){
        mysqli_rollback($conn);
        print $e->getMessage();
        exit;
    }
}
?>