<?php
include('../../resources/database/config.php');
// include('../includes/page_authentication.php');

if (isset($_POST['save'])) {
    $method = ($_POST['payment_method'] == 'none' ? NULL : $_POST['payment_method']);
    $status = $_POST['status'];
    $amount = $_POST['amount'];
    $pay_amount=$_POST['pay_amount'];
    $id = $_GET['id'];

    try {
        mysqli_begin_transaction($conn);

        $sql_payment = "UPDATE payment SET payment_type=?, payment_status=?, amount=?, pay_amount=? WHERE book_id=?";
        $stmt = mysqli_prepare($conn, $sql_payment);
        mysqli_stmt_bind_param($stmt, 'ssiii', $method, $status, $amount,$pay_amount, $id);
        mysqli_stmt_execute($stmt);

        if($status=='paid'){
            $sql_auto_complete="UPDATE booking  SET book_status='confirmed' WHERE book_id=$id";
            mysqli_query($conn,$sql_auto_complete);
        }

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            mysqli_commit($conn);
            header("Location: index.php?id=$id");
            exit;
        } else {
            throw new Exception("No rows affected.".$id);
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
}
?>