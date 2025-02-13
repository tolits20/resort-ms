<?php 

include('admin/includes/bootstrap.html');

if(isset($_POST['login'])){
    $username=trim($_POST['username']);
    $password=trim(sha1($_POST['password']));

    try{

        $sql="SELECT username, password FROM users WHERE username=? && password=? LIMIT 1";
        $stmt=mysqli_prepare($conn,$sql);
        mysqli_stmt_bind_param($stmt,'ss',$username,$password);
        mysqli_stmt_execute($smt);

        if(mysqli_stmt_affected_rows($stmt)===0){
            $_SESSION['user'];
            $_SESSION['ID'];
            $_SESSION['role'];
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
</head>
<style>

body{
    margin: 0%;
    display: flex;
    justify-content: center;    
    padding: 30px;
}

.container{
    margin: 100px;
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
        <form action="authentication.php" method="post">
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