<?php
include ('../../resources/database/config.php');

if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit;
}

$account_id = $_SESSION['ID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $profile_img = "";

    if (!empty($_FILES['profile_img']['name'])) {
        $target_dir = "../../assets";
        $profile_img = basename($_FILES["profile_img"]["name"]);
        $target_file = $target_dir . $profile_img;
        move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file);

        $sql = "UPDATE user SET profile_img='$profile_img' WHERE account_id='$account_id'";
        $conn->query($sql);
    }

    $sql = "UPDATE user SET age='$age', gender='$gender', contact='$contact' WHERE account_id='$account_id'";
    $conn->query($sql);

    $sql = "UPDATE account SET username='$username' WHERE account_id='$account_id'";
    $conn->query($sql);

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE account SET password='$hashed_password' WHERE account_id='$account_id'";
        $conn->query($sql);
    }

    header("Location: profile.php");
    exit;
}

$conn->close();
?>
