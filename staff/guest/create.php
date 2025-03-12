<?php
include('../../resources/database/config.php');
include("../../admin/includes/system_update.php"); 

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Guest</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px #aaa;
            max-width: 400px;
        }
        label {
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #4cae4c;
        }
    </style>
</head>
<body>

    <h2>Create New Guest</h2>
    <form action="store.php" method="post">
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname" required>

        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname" required>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <label for="contact">Contact:</label>
        <input type="text" id="contact" name="contact" pattern="\d+" title="Only numbers are allowed" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <input type="submit" value="Submit">
    </form>

</body>
</html>
