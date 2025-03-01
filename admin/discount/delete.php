<?php
include('../../resources/database/config.php');

$discount_id = $_GET['id'];

try {
    mysqli_begin_transaction($conn);
    
    // Prepare DELETE statement
    $sql = "DELETE FROM discount WHERE discount_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $discount_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt)) {
        mysqli_commit($conn);
        $_SESSION["discount_delete"] = "yes";
    } else {
        throw new Exception("Failed to delete discount.");
    }

    header("location: index.php"); 
    exit;
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Error: " . $e->getMessage();
}
?>
