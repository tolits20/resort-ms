<?php 
include("../../resources/database/config.php");
include("../../admin/includes/system_update.php");

if (isset($_SESSION['ID'])) {
    $account_id = $_SESSION['ID'];

    $sql1 = "SELECT u.*, a.username, a.password FROM user u 
        JOIN account a ON u.account_id = a.account_id 
        WHERE u.account_id = ?";
    $stmt = $conn->prepare($sql1);
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "User data not found.";
        exit;
    }

    $_SESSION['update_id'] = $account_id;
} else {
    echo "User not logged in.";
    exit;
}
if (!isset($row['password'])) {
    echo "Error: Password field not found.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paradise Resort | Update Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #e67e22;
            --light: #f8f9fa;
            --dark: #2c3e50;
            --success: #27ae60;
            --white: #ffffff;
        }
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f7fc;
}

.container {
    width: 60%;
    margin: 50px auto;
    padding: 30px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    position: relative;
}
h2 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

form {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
}

.content {
    display: flex;
    gap: 30px;
    width: 100%;
}

input[type="text"], input[type="email"], input[type="number"], input[type="tel"], select, input[type="file"], input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 4px;
    border: 1px solid #ddd;
    box-sizing: border-box;
    font-size: 14px;
}

input[type="file"] {
    padding: 3px;
}

label {
    font-weight: bold;
    margin-bottom: 5px;
    font-size: 14px;
}

select {
    padding: 12px;
    font-size: 14px;
}

.div3 {
    text-align: center;
    width: 25%;
}

.div3 img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 2px solid #ddd;
    margin-bottom: 10px;
}

.div3 input[type="file"] {
    margin-top: 10px;
}

.div1, .div2 {
    width: 48%;
    padding: 20px;
}

.div1 {
    border-right: 1px solid #ddd;
}

.btn {
    display: flex;  
    justify-content: center; 
    margin-top: 20px; 
}

.btn button {
    width: 100%;
    padding: 12px 25px;
    background-color: #28a745;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn button:hover {
    background-color: #218838;
}
.change-password-section button {
    width: 100%;
    padding: 12px 25px;
    background-color: #5bc0de;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.change-password-section button:hover {
    background-color: #31b0d5;
}
.back-button {
    position: absolute;
    top: 20px;
    left: 20px; 
    font-size: 24px; 
    color: #333; 
    text-decoration: none;
}

.back-button i {
    margin-right: 5px;
}

.back-button:hover {
    color: #007bff;
}
.main-container {
            max-width: 1200px;
            margin: 100px auto 2rem;
            padding: 2rem;
        }

        .info-bar {
            background: var(--white);
            padding: 1.5rem 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.8rem;
            color: var(--primary);
            font-weight: 600;
        }

        .datetime-info {
            text-align: right;
            color: var(--secondary);
            font-size: 0.9rem;
        }

        /* Update container styles */
        .container {
            width: 80%;
            max-width: 1200px;
            margin: 2rem auto;
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }

            .info-bar {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .datetime-info {
                text-align: center;
            }

            .container {
                width: 95%;
                padding: 1rem;
            }

            .content {
                flex-direction: column;
            }

            .div1, .div2, .div3 {
                width: 100%;
            }
        }
</style>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body> 
   <?php include("../view/navbar.php"); ?>

<div class="main-container">
    <!-- Info Bar -->
    <div class="info-bar">
        <div class="page-title">Update Account</div>
        <div class="datetime-info">
            <div>2025-03-10 14:01:54 UTC</div>
            <div>Welcome, <?php echo $user_data['username'];?></div>
        </div>
    </div>


        <form action="update.php" method="post" enctype="multipart/form-data">
            <div class="content">
                <div class="div3">
                    <img src="../../resources/assets/images/<?php echo $row['profile_img'] ?>" alt="Profile Image">
                    <input type="hidden" name="current" value="<?php echo $row['profile_img'] ?>">
                    <input type="hidden" name="ID" value="<?php echo $row['account_id'] ?>">
                    <label for="file" class="form-label">Profile Image</label>
                    <input type="file" name="file" class="form-control">
                </div>
                
                <div class="div1">
                    <div class="firstname">
                        <label for="fname" class="form-label">First Name</label>
                        <input type="text" name="fname" class="form-control" value="<?php echo $row['fname'] ?>" >
                    </div>
                    <div class="lastname">
                        <label for="lname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="lname" value="<?php echo $row['lname'] ?>" >
                    </div>
                    <div class="age">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" name="age" value="<?php echo $row['age'] ?>" class="form-control">
                    </div>
                    <div class="gender">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-select">
                            <option value="male" <?php echo ($row['gender']=='male' ? 'selected': ''); ?> >Male</option>
                            <option value="female" <?php echo ($row['gender']=='female' ? 'selected': ''); ?>>Female</option>
                        </select>
                    </div>
                    <div class="contect">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="number" name="contact" value="<?php echo $row['contact'] ?>" class="form-control">
                    </div>
                    <div class="username">
                        <label for="username" class="form-label">Username</label>
                        <input type="email" class="form-control" name="username" value="<?php echo $row['username'] ?>" placeholder="User@example.com" >
                    </div>

                    <div class="btn">
                        <button type="submit" class="btn btn-primary" name="update">Update</button>
                    </div>
                </div>

                <div class="div2">
                    <form action="update.php" method="post">
                        <input type="hidden" name="ID" value="<?php echo $row['account_id']; ?>">
                        <input type="hidden" name="current_password" value="<?php echo $row['password']; ?>">
                        <div class="form-group">
                            <label for="oldPassword">Old Password</label>
                            <input type="password" id="oldPassword" name="old_password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" id="newPassword" name="new_password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" id="confirmPassword" name="confirm_password" class="form-control">
                        </div>
                        <div class="change-password-section">
                            <button type="submit" name="update_password" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </form>
    </div>
    <?php include("../view/footer.php"); ?>

</body>

