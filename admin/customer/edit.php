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
        box-shadow:  0 0 30px rgba(0, 0, 0, 0.5);

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
    .popup-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .popup-content {
        background: white;
        padding: 20px;
        width: 400px;
        text-align: start;
        border-radius: 8px;
        position: relative;
    }

    .popup-content input{
        border:solid 1px;
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
        color: red;
    }
</style>
<div class="content">
    <?php include("alert.php") ?>
    <form action="update.php" method="post" enctype="multipart/form-data" class="form-control">
        <div class="con1">
            <div class="div3">
                <label for="">Profile Photo:</label>
                <br>
                <img src="../../resources/assets/images/<?php echo $row['profile_img']?>" alt="../../resources/assets/images/<?php echo $row['profile_img']?>" >
                <br>
                <input type="file" name="file" class="form-control">
            </div>
            <div class="div1">
                <div class="firstname">
                    <label for="" class="form-label">First name:</label>
                    <input type="text" name="fname" class="form-control" value="<?php echo $row['fname'] ?>" required>
                </div>
                <br>
                <div class="lastname">
                    <label for="" class="form-label">Last name:</label>
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
                    <input type="text" class="form-control" name="contact" value="<?php echo $row['contact'] ?>">
                </div>
            
            </div>
            <div class="div2">
                <div class="gender">
                    <label for="" class="form-label">Gender:</label>
                <select name="gender" class="form-select">
                        <option value="male" <?php echo ($row['gender']=='male' ? 'selected' : '') ?>>Male</option>
                        <option value="female" <?php echo ($row['gender']=='female' ? 'selected' : '') ?>>Female</option>
                    </select>
                    <input type="hidden" name="current_img" value="<?php echo $row['profile_img'] ?>">
                </div>  
                <br>
                <div class="username">
                    <label for="" class="form-label">Username:</label>
                    <input type="email" class="form-control" name="username" value="<?php echo $row['username'] ?>" required>
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
                <br>
            <div class="password" style="width:100%;" >
                <button class="btn btn-primary" type="button" onclick="openPopup()">Change Password</button>
            </div>
                <br>
            </div>
        </div>
        <div class="con2">
            <input type="submit" name="update" class="btn btn-primary" value="Update">
        </div>

    <!-- POPUP -->
    <div class="popup-overlay" id="popup">
    <div class="popup-content">
        <form action="update.php" method="post">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <h2>Change Password</h2>
       <label for="" class="form-label">Enter a new password:</label>
        <input type="password" class="form-control" name="newpass">
        <br>
        <label for="" class="form-label">Confirm the password:</label>
        <input type="password" class="form-control" name="cpass">
        <br>
        <button class="btn btn-primary" name="changepass" onclick="closePopup()">Save</button>
        </form>
    </div>
</div>
<script>
    function openPopup() {
        document.getElementById('popup').style.display = 'flex';
    }

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
    }
</script>
    </form>
</div>