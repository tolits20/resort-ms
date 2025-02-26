<?php 

if(isset($_SESSION["account_check"])){
    print"<div style='
    padding: 15px; 
    border: solid 2px #f44336; 
    border-radius: 8px; 
    background-color: #f8d7da; 
    color: #721c24;
    font-family: Arial, sans-serif;
    height:55px;
    width: 100%;
    text-align: center;
    opacity: 0.95;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
'>
    <strong>Access Denied:</strong> This account does not exist.
</div>";
    unset($_SESSION['account_check']);

}


?>