<?php 
include('resources/database/config.php');
include("admin/customer/alert.php");
if(isset($_POST['login'])){
    $username=trim($_POST['username']);
    $password=trim(sha1($_POST['password']));

    try{

        $sql="SELECT account_id, role, status FROM account WHERE username=? && password=? LIMIT 1";
        $stmt=mysqli_prepare($conn,$sql);
        mysqli_stmt_bind_param($stmt,'ss',$username,$password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt,$ID,$role,$status);
        
        if(mysqli_stmt_num_rows($stmt)>0){
            mysqli_stmt_fetch($stmt);

           if($role==='admin' && $status==='activate'){
           echo $_SESSION['ID']=$ID;
           echo $_SESSION['role']=$role;
           echo $_SESSION['status']=$status;
           $_SESSION["login_success"]='yes';
            header('location:admin/index.php');
           }elseif($role=='user' && $status=='activate'){
            $_SESSION['ID']=$ID;
            $_SESSION['role']=$role;
            $_SESSION['status']=$status;
            header('location:user/view/home.php');
           }else{
            $_SESSION["status_check"]="yes";
             include("alert.php");

           }
        }else{
            $_SESSION["account_check"]="yes";
            include("alert.php");
        }

    }catch(Exception $e){   
        mysqli_rollback($conn);
        echo $e->getMessage();
        // header('location:'.$_SERVER['PHP_SELF']);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<style>

body{
    margin: 0%; 
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    padding: 30px;
}

.container{
    margin: 50px 100px 100px;
    width: 500px;
    height: 500px;
    padding: 0%;
}   

form{
    display: flex;
    flex-direction: column;
    gap: 10px;
    justify-content: center;
    border-radius: 5px;
    padding: 50px;
    box-shadow:  0 0 10px rgba(0, 0, 0, 0.5);
}

</style>
<body>
    <div class="container"> 
        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <div class="username">
                <label for="" class="form-label">Username:</label>
                <input type="text" name="username" class="form-control" placeholder="username" required>
            </div>
            <br>
            <div class="password">
                <label for="" class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <br>
            <div class="btn">
                <input type="submit" name="login" class="btn btn-primary" required style="width: 100%;">
            </div>
            <br>
            <div class="create" style="text-align: center;">
              <p>To create an account, click <a href="admin/customer/create.php">here</a></p>
            </div>

        </form>
    </div>
</body>
</html>