<?php 
if((!isset($_SESSION['ID']) && !isset($_SESSION['role'])) || $_SESSION['role']=='user'){
    echo "hello";
    header("location:http:/resort-ms/login.php");
    exit;
}

?>