<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include ('../includes/template.php');
include("../includes/system_update.php");

function timeAgo($datetime) {
    date_default_timezone_set('Asia/Manila'); // Change to your timezone if needed
    $timestamp = strtotime($datetime);
    if (!$timestamp || $timestamp == 0) {
        return "Last active: Unknown";
    }

    $current_time = time();
    
    $diff = $current_time - $timestamp;
    if ($diff < 60) {
        return "Last active just now";
    } elseif ($diff < 3600) { 
        return "Last active " . floor($diff / 60) . " mins ago";
    } elseif ($diff < 86400) {
        return "Last active " . floor($diff / 3600) . " hrs ago";
    } elseif ($diff < 7 * 86400) { 
        return "Last active " . floor($diff / 86400) . " days ago";
    } elseif ($diff < 30 * 86400) { 
        return "Last active " . floor($diff / (7 * 86400)) . " weeks ago"; // Fix: divide by 7
    } elseif ($diff < 365 * 86400) { 
        return "Last active " . floor($diff / (30 * 86400)) . " months ago";
    } else { 
        return "Last active " . floor($diff / (365 * 86400)) . " years ago";
    }
}


$sql = "SELECT account_id, username, role, last_active FROM account";
$result = mysqli_query($conn, $sql);
?>
<title>Customer Management</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
:root {
    --primary-color: #3a7bd5;
    --secondary-color: #f4f7f6;
    --text-color: #333;
    --border-color: #e0e4e7;
    --table-header-bg: #2c3e50;
    --table-row-hover: #f9f9f9;
}

.content {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    padding: 30px;
}

.account-management-header {
    margin-bottom: 30px;
    border-bottom: 2px solid var(--border-color);
    padding-bottom: 20px;
}

.account-management-header h1 {
    color: var(--primary-color);
    margin: 0;
    font-weight: 600;
}

.account-table {
    background-color: white;
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.account-table thead {
    background-color: var(--table-header-bg);
    color: white;
}

.account-table th, .account-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.account-table th {
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1px;
    font-weight: 600;
}

.account-table tbody tr:hover {
    background-color: var(--table-row-hover);
}

.user-profile {
    display: flex;
    align-items: center;
}

.username {
    font-weight: 500;
}

.role-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    display: inline-block;
}

.role-badge.role-admin {
    background-color: #ff6b6b;
    color: white;
}

.role-badge.role-user {
    background-color: #4ecdc4;
    color: white;
}

.role-badge.role-staff {
    background-color: #f39c12;
    color: white;
}

.activity-status {
    display: flex;
    align-items: center;
}

.last-active-text {
    font-size: 14px;
    color: #666;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    border-radius: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
}

.view-btn {
    background-color: #17a2b8;
    color: white;
}

.edit-btn {
    background-color: #28a745;
    color: white;
}

.delete-btn {
    background-color: #dc3545;
    color: white;
    border: none;
    cursor: pointer;
}

.action-btn:hover {
    transform: scale(1.1);
    opacity: 0.9;
}

/* Popup Styles */
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.popup-content {
    background: white;
    padding: 30px;
    border-radius: 12px;
    width: 400px;
    text-align: center;
    position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #dc3545;
}

.confirm-btn, .cancel-btn {
    padding: 12px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.confirm-btn {
    background-color: #dc3545;
    color: white;
}

.cancel-btn {
    background-color: #28a745;
    color: white;
}

.confirm-btn:hover {
    background-color: #a71d2a;
}

.cancel-btn:hover {
    background-color: #218838;
}

@media (max-width: 768px) {
    .account-management-container {
        padding: 15px;
    }

    .account-table {
        font-size: 14px;
    }

    .account-table th, .account-table td {
        padding: 10px;
    }

    .action-buttons {
        flex-direction: column;
        gap: 5px;
    }

    .action-btn {
        width: 100%;
    }
}
</style>

<script>
    function openPopup(accountId) {
        document.getElementById('popup-' + accountId).style.display = 'flex';
    }

    function closePopup(accountId) {
        document.getElementById('popup-' + accountId).style.display = 'none';
    }
</script>

</head>
<body>
    <div class="content">
        <?php
        include("alert.php");
        include('filter.php');
        ?>
        <br>
        <table class="account-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Active Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>
                        <div class='user-profile'>
                            <div class='username'>{$row['username']}</div>
                        </div>
                    </td>
                    <td>
                        <span class='role-badge role-{$row['role']}'>{$row['role']}</span>
                    </td>
                    <td>
                        <div class='activity-status'>
                            <span class='last-active-text'>" . timeAgo($row['last_active']) . "</span>
                        </div>
                    </td>
                    <td>
                        <div class='action-buttons'>
                            <a href='view.php?id={$row['account_id']}' class='action-btn view-btn' title='View Details'>
                                <i class='fas fa-eye'></i>
                            </a>
                            <a href='edit.php?id={$row['account_id']}' class='action-btn edit-btn' title='Edit Account'>
                                <i class='fas fa-edit'></i>
                            </a>
                            <button class='action-btn delete-btn' type='button' onclick='openPopup({$row['account_id']})' title='Delete Account'>
                                <i class='fas fa-trash'></i>
                            </button>
                            
                            <div class='popup-overlay' id='popup-{$row['account_id']}' style='color: black; display: none;'>
                                <div class='popup-content'>
                                    <form action='delete.php?id={$row['account_id']}' method='post'>
                                        <span class='close-btn' onclick='closePopup({$row['account_id']})'>&times;</span>
                                        <h6>Are you sure you want to <strong style='color: red;'>Delete</strong> this Account?</h6>  
                                        <input type='hidden' name='account_id' value='{$row['account_id']}'>
                                        <br>
                                        <input type='submit' value='YES' class='form-control confirm-btn' name='yes'>
                                        <hr> 
                                        <button type='button' class='form-control cancel-btn' onclick='closePopup({$row['account_id']})'>NO</button>
                                        <br>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
    </div>
</body>
</html>
