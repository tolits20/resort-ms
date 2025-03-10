<?php 
include('resources/database/config.php');
include("admin/customer/alert.php");
if(isset($_POST['login'])){
    $username=trim($_POST['username']);
    $password=trim(sha1($_POST['password']));

    try{
        mysqli_begin_transaction($conn);    
        $sql="SELECT account_id, role FROM account WHERE username=? && password=? && deleted_at IS NULL LIMIT 1";
        $stmt=mysqli_prepare($conn,$sql);
        mysqli_stmt_bind_param($stmt,'ss',$username,$password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt,$ID,$role);
        
        if(mysqli_stmt_num_rows($stmt)>0){
            mysqli_stmt_fetch($stmt);
            $active="UPDATE account SET last_active=now() WHERE account_id=?";
            $stmt1=mysqli_prepare($conn,$active);
            mysqli_stmt_bind_param($stmt1,'i',$ID);
            mysqli_stmt_execute($stmt1);
           if($role==='admin' ){
           echo $_SESSION['ID']=$ID;
           echo $_SESSION['role']=$role;
           $_SESSION["login_success"]='yes';
            header('location:admin/index.php');
           }elseif($role=='user' ){
            $_SESSION['ID']=$ID;
            $_SESSION['role']=$role;
            header('location:user/view/home.php');
            }elseif($role=='staff' ){
                $_SESSION['ID']=$ID;
                $_SESSION['role']=$role;
                header('location:staff/dashboard.php');
                }else{
                    $_SESSION["status_check"]="yes";
                    include("alert.php");
                }
           }else{
            $_SESSION["status_check"]="yes";
             include("alert.php");

           }
           mysqli_commit($conn);
    }catch(Exception $e){   
        mysqli_rollback($conn);
        echo $e->getMessage();
        header('location:'.$_SERVER['PHP_SELF']);
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Management System - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            width: 400px;
            max-width: 90%;
            margin: 0 auto;
        }

        .login-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .login-header {
            background: #4a6bfd;
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }

        .login-header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 1.8rem;
        }

        .login-header p {
            margin: 10px 0 0;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .login-form {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: all 0.3s;
            background-color: #f9f9f9;
        }

        .form-group input:focus {
            border-color: #4a6bfd;
            box-shadow: 0 0 0 3px rgba(74, 107, 253, 0.2);
            background-color: #fff;
        }

        .form-group i {
            position: absolute;
            top: 43px;
            left: 15px;
            color: #666;
        }

        .form-group input {
            padding-left: 40px;
        }

        .btn-login {
            background: #4a6bfd;
            border: none;
            padding: 14px;
            border-radius: 8px;
            width: 100%;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-login:hover {
            background: #3655e0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 107, 253, 0.4);
        }

        .login-footer {
            padding: 15px 30px;
            text-align: center;
            border-top: 1px solid #eee;
            font-size: 0.9rem;
            color: #666;
        }

        .login-footer a {
            color: #4a6bfd;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .login-footer a:hover {
            color: #3655e0;
            text-decoration: underline;
        }

        .logo {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-umbrella-beach"></i>
                </div>
                <h2>Resort Management</h2>
                <p>Enter your credentials to access your account</p>
            </div>
            
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" name="login" class="btn-login">
                    Login <i class="fas fa-sign-in-alt ms-2"></i>
                </button>
            </form>
            
            <div class="login-footer">
                <p>Don't have an account? <a href="admin/customer/create.php">Create one now</a></p>
                <p><a href="password_recovery.php">Forgot password?</a></p>
            </div>
        </div>
    </div>
</body>
</html>