<?php 

if(isset($_SESSION['create_success'])){
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
    <strong>Success!</strong> Account has been created successfully.
</div>";
    unset($_SESSION['create_success']);
}

if(isset($_SESSION['customer_update_success'])){
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
    <strong>Success!</strong> Account has been updated successfully.
</div>";
    unset($_SESSION['customer_update_success']);
}


// if(isset($_SESSION["customer_error"])){
//     print"<div style='
//     padding: 15px; 
//     border: solid 2px #f44336; 
//     border-radius: 8px; 
//     background-color: #f8d7da; 
//     color: #721c24;
//     font-family: Arial, sans-serif;
//     height:55px;
//     width: 100%;
//     text-align: center;
//     opacity: 0.95;
//     box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
// '>
//     <strong>Failed:</strong> Invalid value/inputs.
// </div>";
//     unset($_SESSION['customer_error']);

// }





?>