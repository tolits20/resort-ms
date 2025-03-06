<?php 
include('resources/database/config.php');

if(isset($_POST['remail'])){
    $email=$_POST['email'];
    $email=trim($email);
    $sql="SELECT * FROM account WHERE username='$email' LIMIT 1";
    $sql_set=mysqli_query($conn,$sql);
    if(mysqli_num_rows($sql_set)>0){
        $result=mysqli_fetch_assoc($sql_set);
        $otp=rand(1000,9999);
        $otp=trim($otp);
        $ID=$result['account_id'];
        $email=$result['username'];
        echo $recover =("INSERT INTO password_recovery (account_id, otp_code, created_at, expire_at) 
        VALUES (?, ?, now(), DATE_ADD(now(), INTERVAL 5 MINUTE))");
        $stmt=mysqli_prepare($conn,$recover);
        mysqli_stmt_bind_param($stmt,"ii", $ID, $otp); // "ii" = both are integers
        mysqli_stmt_execute($stmt);
        if(mysqli_stmt_affected_rows($stmt)>0){
            $to = $email;
            $subject = "Password Recovery OTP";
            $message = "Your OTP is $otp";
            if(mail($to,$subject,$message)){
                header('location:password_recovery.php?step=2&&email=true');
                exit();
            }
        }
        }
    }

    if(isset($_POST['otp_confirm'])){
        $otp=$_POST['otp'];
        $otp=trim($otp);
        $sql="SELECT * FROM password_recovery WHERE otp_code='$otp' LIMIT 1";
        $sql_set=mysqli_query($conn,$sql);
        if(mysqli_num_rows($sql_set)>0){
            $result=mysqli_fetch_assoc($sql_set);
            $ID=$result['account_id'];
            $sql="SELECT * FROM account WHERE account_id='$ID' LIMIT 1";
            $sql_set=mysqli_query($conn,$sql);
            if(mysqli_num_rows($sql_set)>0){
                $_SESSION['account_id']=$ID;
                $result=mysqli_fetch_assoc($sql_set);
                $email=$result['username'];
                header('location:password_recovery.php?step=3&&email=true&&otp=true');
                exit();
            }
        }
    }

    if(isset($_POST['password_set']) && $_POST['npassword']==$_POST['cpassword']){
        $password=$_POST['npassword'];
        $password=sha1(trim($password));
        $ID=$_SESSION['account_id'];
        $sql="UPDATE account SET password='$password' WHERE account_id='$ID'";
        $sql_set=mysqli_query($conn,$sql);
        if($sql_set){
            $sql="DELETE FROM password_recovery WHERE account_id='$ID'";
            $sql_set=mysqli_query($conn,$sql);
            if($sql_set){
                header('location:login.php');
                exit();
            }
        }

        
    }

    include('admin/includes/system_update.php');

?>