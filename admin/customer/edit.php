<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include ('../includes/template.php');
include("../includes/system_update.php");

$id=$_GET['id'];

$sql1="SELECT * FROM user INNER JOIN account using(account_id) WHERE account_id=$id";
$result=mysqli_query($conn,$sql1);
$row=mysqli_fetch_assoc($result);
$_SESSION['update_id']=$id;
?> 

<title>Profile Update</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #4a90e2;
        --secondary-color: #f4f7f6;
        --text-color: #333;
        --border-radius: 12px;
    }

    .content {
        background-color: var(--secondary-color);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        color: var(--text-color);
        line-height: 1.6;
    }

    .profile-container {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        padding: 40px;
        margin-top: 50px;
        max-width: 800px;
    }

    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--secondary-color);
    }

    .profile-photo-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin-right: 30px;
    }

    .profile-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid var(--primary-color);
    }

    .photo-upload {
        position: absolute;
        bottom: 0;
        right: 0;
        background-color: var(--primary-color);
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .form-control, .form-select {
        background-color: var(--secondary-color);
        border: 1px solid #e0e4e7;
        border-radius: 8px;
        padding: 12px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(74,144,226,0.25);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #3a7bd5;
        transform: translateY(-2px);
    }

    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .popup-content {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        width: 400px;
        padding: 30px;
        position: relative;
    }

    .close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 24px;
        color: #999;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close-btn:hover {
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .profile-photo-container {
            margin-right: 0;
            margin-bottom: 20px;
        }
    }
</style>
</head>
<div class="content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 profile-container">
                <form action="update.php" method="post" enctype="multipart/form-data">
                    <div class="profile-header">
                        <div class="profile-photo-container">
                            <img src="../../resources/assets/images/<?php echo $row['profile_img']?>" 
                                 alt="Profile Photo" 
                                 class="profile-photo">
                            <label for="file-upload" class="photo-upload">
                                <i class="fas fa-camera"></i>
                                <input type="file" id="file-upload" name="file" class="d-none" accept="image/*">
                            </label>
                        </div>
                        <div>
                            <h2><?php echo $row['fname']." ".$row['lname'] ?></h2>
                            <p class="text-muted">Update your personal information</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fname" class="form-label">First Name</label>
                            <input type="text" name="fname" class="form-control" 
                                   value="<?php echo $row['fname'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lname" class="form-label">Last Name</label>
                            <input type="text" name="lname" class="form-control" 
                                   value="<?php echo $row['lname'] ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" name="age" class="form-control" 
                                   value="<?php echo $row['age'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label">Contact Number</label>
                            <input type="tel" name="contact" class="form-control" 
                                   value="<?php echo $row['contact'] ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="male" <?php echo ($row['gender']=='male' ? 'selected' : '') ?>>Male</option>
                                <option value="female" <?php echo ($row['gender']=='female' ? 'selected' : '') ?>>Female</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Email Address</label>
                            <input type="email" name="username" class="form-control" 
                                   value="<?php echo $row['username'] ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="admin" <?php echo ($row['role']=='admin' ? 'selected' : '') ?>>Admin</option>
                                <option value="user" <?php echo ($row['role']=='user' ? 'selected' : '') ?>>User</option>
                                <option value="staff" <?php echo ($row['role']=='staff' ? 'selected' : '') ?>>Staff</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100" type="button" onclick="openPopup()">
                                Change Password
                            </button>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col text-center">
                            <input type="submit" name="update" class="btn btn-primary px-5" value="Save Changes">
                        </div>
                    </div>
                    <input type="hidden" name="current_img" value="<?php echo $row['profile_img'] ?>">
                </form>
            </div>
        </div>
    </div>

    <!-- Password Change Popup -->
    <div class="popup-overlay" id="popup">
        <div class="popup-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <h3 class="mb-4 text-center">Change Password</h3>
            <form action="update.php?id=<?php echo $_GET['id'] ?>" method="post">
                <div class="mb-3">
                    <label for="newpass" class="form-label">New Password</label>
                    <input type="password" class="form-control" name="newpass" required>
                </div>
                <div class="mb-4">
                    <label for="cpass" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" name="cpass" required>
                </div>
                <button class="btn btn-primary w-100" name="changepass" onclick="closePopup()">
                    Update Password
                </button>
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

        // Simple client-side password validation
        document.querySelector('form[name="changepass"]').addEventListener('submit', function(e) {
            const newPass = document.querySelector('input[name="newpass"]').value;
            const confirmPass = document.querySelector('input[name="cpass"]').value;
            
            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    </div>
</document_content>