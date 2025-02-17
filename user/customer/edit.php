<?php
include ('../../resources/database/config.php'); 

if (!isset($_SESSION['ID'])) {
    header("Location:../login.php");
    exit;
}

$account_id = $_SESSION['ID'];

$sql = "SELECT u.*, a.username FROM user u 
        JOIN account a ON u.account_id = a.account_id 
        WHERE u.account_id = '$account_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #f4f4f4; }
        .profile-container { max-width: 400px; margin: 50px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); }
        input, select { width: 100%; padding: 8px; margin: 10px 0; }
        .profile-img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; }
        button { background: #27ae60; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #219150; }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Edit Profile</h2>
        <form action="update.php" method="POST" enctype="multipart/form-data">
            <img src="../../resources/assets/images/<?php echo $user['profile_img']; ?>" class="profile-img" alt="Profile Image"><br>
            <label>Upload New Profile Picture:</label>
            <input type="file" name="profile_img"><br>

            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

            <label>New Password (leave blank to keep current password):</label>
            <input type="password" name="password"><br>

            <label>Age:</label>
            <input type="number" name="age" value="<?php echo $user['age']; ?>" required><br>

            <label>Gender:</label>
            <select name="gender" required>
                <option value="Male" <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
            </select><br>

            <label>Contact:</label>
            <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>" required><br>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>
