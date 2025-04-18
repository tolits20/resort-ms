<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include("../includes/system_update.php");

if (isset($_POST['status'])) {
   echo $stat = $_POST['status'];
   echo $applicable_room = $_POST['applicable_room'];
    $id = $_GET['id'];

    if ($stat == 'activate') {
        $check = "SELECT * FROM discount WHERE discount_status='activate' AND applicable_room='$applicable_room' AND discount_start <= NOW() AND discount_end>=NOW()";
        $result = mysqli_query($conn, $check);
        $count = mysqli_num_rows($result);
        echo'hello';
        if ($count == 0) {
            try {
                echo 'hello';
                mysqli_begin_transaction($conn);
                $sql = "UPDATE discount SET discount_status=? WHERE discount_id=?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'si', $stat, $id);
                mysqli_stmt_execute($stmt);

                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    mysqli_commit($conn);
                    header("location:index.php");
                    exit;
                } else {
                    throw new Exception("Failed to update discount status.");
                }
            } catch (Exception $e) {
                mysqli_rollback($conn);
                echo "Error: " . $e->getMessage();
                exit;
            }
        } else {
            $_SESSION['alert_message'] = "There is already an active discount for this type of room.";
            header("Location: index.php");
            exit;
        }
    }else{
        try {
            mysqli_begin_transaction($conn);
            $sql = "UPDATE discount SET discount_status=? WHERE discount_id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $stat, $id);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                mysqli_commit($conn);
                header("location:index.php");
                exit;
            } else {
                throw new Exception("Failed to update discount status.");
            }
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "Error: " . $e->getMessage();
            exit;
        }
    }
}

if (isset($_POST['update'])) {
    $name = $_POST['discount_name'];
    $percentage = $_POST['percentage'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $type = $_POST['applicable_room'];
    $discount_id = $_POST['update'];

    try {
        mysqli_begin_transaction($conn);
        $update_sql = "UPDATE discount 
                       SET discount_name=?, discount_percentage=?, discount_start=?, discount_end=?, applicable_room=? 
                       WHERE discount_id=?";
        $stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt, "sisssi", $name, $percentage, $start, $end, $type, $discount_id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) >= 0) {
            mysqli_commit($conn);
            $_SESSION["discount_update"] = "yes";
            header("location:index.php");
            exit;
        } else {
            throw new Exception("Failed to update.");
        }
    } catch(Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
}




?>

