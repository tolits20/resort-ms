<?php 
$staff="SELECT CONCAT(u.fname,' ',u.lname) AS name FROM user u INNER JOIN account a USING(account_id) WHERE u.account_id={$_SESSION['ID']}";
$staff_result=mysqli_query($conn,$staff);
$staff_name=mysqli_fetch_assoc($staff_result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Portal</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --sidebar-width: 250px;
      --primary-color: #3498db;
      --secondary-color: #2980b9;
    }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
    }
    #sidebar {
      position: fixed;
      width: var(--sidebar-width);
      height: 100vh;
      background: #2c3e50;
      color: white;
      transition: all 0.3s;
    }
    #sidebar .sidebar-header {
      padding: 20px;
      background: #1a252f;
    }
    #sidebar ul li a {
      padding: 15px 20px;
      font-size: 1.1em;
      display: block;
      color: white;
      text-decoration: none;
    }
    #sidebar ul li a:hover, #sidebar ul li a.active {
      background: var(--primary-color);
    }
    #content {
      margin-left: var(--sidebar-width);
      transition: all 0.3s;
      padding: 20px;
    }
    #topbar {
      background: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      padding: 10px 20px;
    }

    /* GRID SYSTEM */
    .grid-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }
    .grid-item {
      background: white;
      padding: 20px;
      text-align: center;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
      #sidebar {
        margin-left: -250px;
      }
      #sidebar.active {
        margin-left: 0;
      }
      #content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
  <nav id="sidebar">
    <div class="sidebar-header">
      <h3>Staff Portal</h3>
    </div>
    <ul class="list-unstyled components">
      <li><a href="http:/resort-ms/staff/dashboard.php" id="dashboard-link"><i class="fas fa-home"></i> Dashboard</a></li>
      <li><a href="http://localhost/resort-ms/staff/guest/index.php" id="dashboard-link"><i class="fa-solid fa-user"></i> Guest</a></li>
      <li><a href="http:/resort-ms/staff/booking/index.php?switch=user" id="dashboard-link"><i class="fas fa-home"></i> Bookings</a></li>
      <li><a href="http:/resort-ms/staff/task/index.php" id="tasks-link"><i class="fas fa-tasks"></i> My Tasks</a></li>
      <li><a href="http:/resort-ms/staff/notification/index.php" id="notifications-link"><i class="fas fa-bell"></i> Notifications</a></li>
      <li><a href="http://localhost/resort-ms/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>
  
  <div id="content">
    <nav id="topbar" class="navbar navbar-light">
      <div class="container-fluid">
        <button class="btn btn-light d-lg-none" id="sidebarCollapse"><i class="fas fa-bars"></i></button>
        <span class="ms-auto">Welcome, <?php echo $staff_name['name']?></span>
      </div>
    </nav>

  <script>
    $(document).ready(function() {
      $('#sidebarCollapse').click(function() {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
      });
    });
  </script>
</body>
</html>
