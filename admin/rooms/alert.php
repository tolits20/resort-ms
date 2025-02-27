<?php 
if(isset($_SESSION['room_create'])){
    echo "<div class='flash-success' style='  padding: 15px;
    border: 2px solid rgb(54, 244, 57);
    background-color: rgba(54, 244, 57, 0.2);
    color: rgb(34, 139, 34);
    border-radius: 8px;
    font-family: Arial, sans-serif;
    height: auto;
    width: 100%;
    text-align: center;
    opacity: 0.95;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    font-size: 16px;
    font-weight: bold;
    position: relative;
    margin: 10px 0;
    animation: fadeIn 0.5s ease-in-out;'>
    <strong>Success!</strong> Room has been created successfully.
</div>";
    unset($_SESSION['room_create']);
}

if(isset($_SESSION['room_update'])){
    echo "<div class='flash-success' style='  padding: 15px;
    border: 2px solid rgb(54, 244, 57);
    background-color: rgba(54, 244, 57, 0.2);
    color: rgb(34, 139, 34);
    border-radius: 8px;
    font-family: Arial, sans-serif;
    height: auto;
    width: 100%;
    text-align: center;
    opacity: 0.95;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    font-size: 16px;
    font-weight: bold;
    position: relative;
    margin: 10px 0;
    animation: fadeIn 0.5s ease-in-out;'>
    <strong>Success!</strong> Room has been created successfully.
</div>";
    unset($_SESSION['room_update']);
}



?>