<?php 

include('../includes/template.html');
include('../../resources/database/config.php');

$id=$_GET['id'];

$sql1="SELECT * FROM user INNER JOIN account using(account_id) WHERE account_id=$id";
$result=mysqli_query($conn,$sql1);
$row=mysqli_fetch_assoc($result);
$_SESSION['update_id']=$id;



?> 
<style>
    .content form .con2, .btn{
        width: 100%;
    }
   .content form .con1{
    
        padding: 50px;
        display: flex;
        justify-content: center;
        gap: 100px;
    }
    img{
        height: 250px;
        width: 300px;
        border-radius: 10px;
    }

    .content form .div3{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: start;
        text-align: center;
    }
    .form-control:focus{
        border-color: orange;
        box-shadow: 0 0 5px rgba(255, 165, 0, 0.75);
        outline: none;
        transition: all 0.3s ease-in-out;
    }
</style>
<div class="content">
    <form action="update.php" method="post" enctype="multipart/form-data" class="form-control">
        <div class="con1">
            <div class="div3">
                <label for="">Profile Photo:</label>
                <img src="../../resources/assets/images/<?php echo $row['profile_img']?>" alt="../../resources/assets/images/<?php echo $row['profile_img']?>" >
                <br>
                <input type="file" name="pfp" class="form-control">
            </div>
            <div class="div1">
                <div class="firstname">
                    <label for="" class="form-label">Firstname:</label>
                    <input type="text" name="fname" class="form-control" value="<?php echo $row['fname'] ?>" required>
                </div>
                <br>
                <div class="lastname">
                    <label for="" class="form-label">Lastname:</label>
                    <input type="text" name="lname" class="form-control" value="<?php echo $row['lname'] ?>" required>
                </div>
                <br>
                <div class="age">
                    <label for="" class="form-label">Age:</label>
                    <input type="text" name="age" class="form-control" value="<?php echo $row['age'] ?>" required>
                </div>
                <br>
                <div class="contact">
                    <label for="" class="form-label">Contact:</label>
                    <input type="number" class="form-control" name="contact" value="<?php echo $row['contact'] ?>">
                </div>
            
            </div>
            <div class="div2">
                <div class="username">
                    <label for="" class="form-label">Username:</label>
                    <input type="text" class="form-control" name="username" value="<?php echo $row['username'] ?>" required>
                </div>
                <br>
                <div class="role">
                    <label for="" class="form-label">Role:</label>
                    <select name="role" class="form-select">
                        <option value="admin" <?php echo ($row['role']=='admin' ? 'selected' : '') ?>>Admin</option>
                        <option value="user"  <?php echo ($row['role']=='user' ? 'selected' : '') ?>>User</option>
                    </select>
                </div>
                <br>
            <div class="status">
                <label for="" class="form-label">Status:</label>
            <select name="status" class="form-select">
                    <option value="activate" <?php echo ($row['status']=='activate' ? 'selected' : '') ?>>Activate</option>
                    <option value="deactivate" <?php echo ($row['status']=='deactivate' ? 'selected' : '') ?>>Deactivate</option>
                </select>
            </div>  
            <br>
            <br>
            <div class="password" style="width:100%;" >
                <a href="change_password.php" class="btn btn-primary" style="text-decoration: none; color:#ffff; width:100%;">Change Password</a>
            </div>
            <br>
        </div>
        </div>
        <div class="con2">
            <input type="submit" name="update" class="btn btn-primary" value="Update">
        </div>
    </form>
</div>