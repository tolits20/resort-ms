<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include ('../includes/template.php');
include("../includes/system_update.php");

$id = $_GET['id'];

$sql1 = "SELECT * FROM user INNER JOIN account USING(account_id) WHERE account_id=?";
$stmt1 = mysqli_prepare($conn, $sql1);
mysqli_stmt_bind_param($stmt1, 'i', $id);
mysqli_stmt_execute($stmt1);
$result1 = mysqli_stmt_get_result($stmt1);
$user = mysqli_fetch_assoc($result1);

$sql2 = "SELECT * FROM account_notification WHERE account_id=? ORDER BY Date DESC";
$stmt2 = mysqli_prepare($conn, $sql2);
mysqli_stmt_bind_param($stmt2, 'i', $id);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$activities = mysqli_fetch_all($result2, MYSQLI_ASSOC);

function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    if (!$timestamp || $timestamp == 0) {
        return "Unknown";
    }

    $current_time = time();
    $diff = $current_time - $timestamp;

    if ($diff < 60) {
        return "just now";
    } elseif ($diff < 3600) { 
        return floor($diff / 60) . " mins ago";
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . " hrs ago";
    } elseif ($diff < 7 * 86400) { 
        return floor($diff / 86400) . " days ago";
    } elseif ($diff < 30 * 86400) { 
        return floor($diff / (7 * 86400)) . " weeks ago";
    } elseif ($diff < 365 * 86400) { 
        return floor($diff / (30 * 86400)) . " months ago";
    } else { 
        return floor($diff / (365 * 86400)) . " years ago";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f5f7fa;
            --text-color: #2c3e50;
            --border-radius: 12px;
        }

        .content {
            background-color: var(--secondary-color);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 15px;
            line-height: 1.6;
        }

        .profile-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
            padding: 40px;
            transition: all 0.3s ease;
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

        .profile-info {
            flex-grow: 1;
        }

        .profile-info h2 {
            margin: 0;
            color: var(--text-color);
        }

        .profile-info p {
            margin: 5px 0;
            color: #666;
        }

        .activity-list {
            list-style: none;
            padding: 0;
        }

        .activity-list li {
            background-color: var(--secondary-color);
            border-radius: var(--border-radius);
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .activity-list li span {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-photo-container">
                    <img src="../../resources/assets/images/<?php echo $user['profile_img']; ?>" alt="Profile Photo" class="profile-photo">
                </div>
                <div class="profile-info">
                    <h2><?php echo $user['fname'] . " " . $user['lname']; ?></h2>
                    <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
                    <p><strong>Email:</strong> <?php echo $user['username']; ?></p>
                    <p><strong>Contact:</strong> <?php echo $user['contact']; ?></p>
                    <p><strong>Gender:</strong> <?php echo ucfirst($user['gender']); ?></p>
                    <p><strong>Age:</strong> <?php echo $user['age']; ?></p>
                    <p><strong>Last Active:</strong> <?php echo timeAgo($user['last_active']); ?></p>
                </div>
            </div>

            <h3>Recent Activities</h3>
            <ul class="activity-list">
                <?php foreach ($activities as $activity): ?>
                    <li>
                        <span><?php echo ucfirst($activity['account_notification']); ?></span>
                        <span><?php echo timeAgo($activity['Date']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>     