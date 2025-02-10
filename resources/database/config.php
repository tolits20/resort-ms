<?php 

$conn=mysqli_connect('localhost','root','','resort_ms');

if ($conn){
    session_start();
}else{
    echo 'Please connect to the Database first!';
}

?>