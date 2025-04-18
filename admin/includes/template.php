<?php 
include('page_authentication.php');
include('system_update.php');
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            display: grid;
            grid-template-columns: 250px 100px 100px auto; 
            grid-template-rows: 80px 100px auto 100px; 
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1E1E2E;
            color: #E0E0E0;
        }

        .sidebar {
            grid-row: 1 / 5; 
            background: #181825;
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            width: 250px;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            padding: 10px;
            color: #CBA6F7;
            font-weight: 600;
            letter-spacing: 1px;
            border-bottom: 1px solid #313244;
        }

        .sidebar ul {
            list-style: none;
            padding-left: 0;
        }

        .sidebar ul li {
            padding: 8px 15px;
            margin-bottom: 5px;
        }

        .sidebar ul li a {
            color: #CDD6F4;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s;
            margin-right: 15px;
        }

        .sidebar ul li a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1em;
            color: #89B4FA;
        }

        .sidebar ul li a:hover {
            background: #313244;
            transform: translateX(5px);
            border-left: 4px solid #F5C2E7;
            color: #F5C2E7;
        }

        .sidebar ul li a:hover i {
            color: #F5C2E7;
        }

        .header {
            grid-column: 2 / 5; 
            grid-row: 1/2;
            background: linear-gradient(135deg, #1E1E2E 0%, #302D41 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #313244;
        }

        .header h2 {
            font-weight: 600;
            font-size: 1.5rem;
            color: #CBA6F7;
            letter-spacing: 1px;
        }

        .admin-info {
            display: flex;
            align-items: center;
            background: #313244;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .admin-info:hover {
            background: #45475A;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .admin-info i {
            margin-right: 10px;
            color: #F5C2E7;
            font-size: 1.1em;
        }

        .content {
            grid-column: 2 / 5;
            grid-row: 2 / 5;
            width: 100%;
            padding: 20px;
            background: #1E1E2E;
            box-shadow: inset 0 5px 15px rgba(0, 0, 0, 0.1);
            color: black;
        }

        /* Adding some cool pulse animation to the sidebar hover */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(245, 194, 231, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(245, 194, 231, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 194, 231, 0); }
        }

        .sidebar ul li a:active {
            animation: pulse 1s;
        }
        
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="http:/resort-ms/admin/index.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="http:/resort-ms/admin/booking/index.php?switch="><i class="fas fa-calendar-check"></i> Bookings</a></li>
            <li><a href="http:/resort-ms/admin/customer/index.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="http:/resort-ms/admin/rooms/index.php"><i class="fas fa-bed"></i> Rooms</a></li>
            <li><a href="http:/resort-ms/admin/task/task_assigned_list.php"><i class="fas fa-thumbtack"></i>Task</a></li>
            <li><a href="http:/resort-ms/admin/discount/index.php"><i class="fas fa-tags"></i> Discount</a></li>
            <li><a href="http:/resort-ms/admin/reports/index.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li><a href="http:/resort-ms/admin/activity_logs/index.php"><i class="fas fa-scroll"></i> Activity Logs</a></li>
            <li><a href="http:/resort-ms/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="header">
        <h2>Resort Management System</h2>
        <div class="admin-info">
            <i class="fas fa-user"></i> Admin
        </div>
    </div>
</body>
</html>