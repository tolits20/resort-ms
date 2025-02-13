<?php 
include ('../includes/bootstrap.html');

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
    padding: 2%;
    margin: 0%;
    
}

body .container{
    margin-top: 50px;
height: 600px;
width: 750px;
border: solid thin;
border-radius: 8px;
padding: 40px;

}
form{
    display: flex;
    gap: 20px;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
.content{
    display: flex;
    justify-content: center;
    gap: 50px;
}
.btn{
    width: 100%;
}
</style>
<body>
   <center> <h2>Create Your Account Now!</h2></center>
    <div class="container">
        
        <form action="store.php" method="post" enctype="multipart/form-data">
        <div class="content">
        <div class="div1">
                <div class="firstname">
                    <label for="" class="form-label">First Name</label>
                    <input type="text" name="fname" class="form-control" required>
                </div>
                <br>
                <div class="lastname">
                    <label for="" class="form-label">Last Name</label>
                     <input type="text" class="form-control" name="lname" required>
                </div>
                <br>
                <div class="age">
                    <label for="" class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" required>
                </div>
                <br>
                <div class="gender">
                    <label for="" class="form-label">Gender</label>
                    <select name="gender" id="" class="form-select" >
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <br>
                <div class="contact">
                    <label for="" class="form-label">Contacts</label>
                    <input type="textsss" name="contact" class="form-control" required >
                </div>
            </div>
            <div class="div2">
            <div class="username">
                    <label for="" class="form-label">Email</label>
                     <input type="text" class="form-control" name="username" placeholder="User@example.com" required>
                </div>
                <br>
                <div class="password">
                    <label for="" class="form-label">Password</label>
                     <input type="password" class="form-control" name="password" required >
                </div>
                <br>
                <div class="role">
                    <label for="" class="form-label">Role</label>
                    <select name="role" id="" class="form-select">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <br>
                <div class="profile">
                    <label for="" class="form-label">Profile</label>
                    <input type="file" name="file" class="form-control">
                </div>
            </div>
            </div>
            <div class="btn">
                    <button type="submit" class="btn btn-primary" name='create' value="create">Create</button>
                </div>
            </form> 
           

    </div>
</body>
</html>
