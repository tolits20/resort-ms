<?php 
include('../../resources/database/config.php');
include('../includes/template.php');
include("../../admin/includes/system_update.php");

$sql="SELECT * FROM customer_booking 
WHERE status NOT IN ('completed', 'cancelled') 
ORDER BY id DESC;";
$result=mysqli_query($conn,$sql);
$_SESSION['identifier']='user';
$switch=$_SESSION['identifier'];
if(isset($_GET['switch'])){
    if($_GET['switch']=='user'){
        $sql="SELECT * FROM customer_booking 
                WHERE status NOT IN ('completed', 'cancelled') 
                ORDER BY id DESC;";
        $result=mysqli_query($conn,$sql);
        $_SESSION['identifier']="user";
    }elseif($_GET['switch']=='guest'){
        $sql="SELECT * FROM guest_booking 
                WHERE status NOT IN ('completed', 'cancelled') 
                ORDER BY id DESC;";
        $result=mysqli_query($conn,$sql);
        $_SESSION['identifier']="guest";
    }
}
$switch=$_SESSION['identifier'];
?>
<div class="content">
    <div class="booking-management-container">
        <div class="booking-header">
            <h1>Booking Management</h1>
            <div class="booking-switcher">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">
                    <select name="switch" class="booking-type-select" onchange="this.form.submit()">
                        <option value="user" <?php echo ($_GET['switch']=='user' ? 'selected' : '') ?>>User Bookings</option>
                        <option value="guest" <?php echo ($_GET['switch']=='guest' ? 'selected' : '') ?>>Guest Bookings</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="booking-list-container">
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Room</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($result)>0){
                        while($row=mysqli_fetch_assoc($result)){
                            $check_in = date("F d, Y h:i A", strtotime($row['check_in']));
                            $check_out= date("F d, Y h:i A", strtotime($row['check_out']));
                            print "<tr>
                                <td>
                                    <div class='customer-info'>
                                        <div class='customer-name'>{$row['fname']} {$row['lname']}</div>
                                    </div>
                                </td>
                                <td>{$row['room_code']}</td>
                                <td>$check_in</td>
                                <td>$check_out</td>
                                <td><span class='status-badge status-{$row['status']}'>{$row['status']}</span></td>
                                <td>
                                    <div class='action-buttons'>
                                        <a href='../payment/index.php?id={$row['id']}' class='action-btn payment-btn' title='Process Payment'>
                                            <i class='fas fa-credit-card'></i>
                                        </a>
                                         <a href='edit.php?switch=$switch&&id={$row['id']}' class='action-btn edit-btn' title='Edit Booking'>
                                             <i class='fas fa-edit'></i>
                                         </a>
                                        <button class='action-btn delete-btn' type='button' onclick='openPopup({$row['id']})' title='Delete Booking'>
                                            <i class='fas fa-trash'></i>
                                        </button>
                                        <div class='popup-overlay' id='popup-{$row['id']}' style='color: black; display: none;'>
                                            <div class='popup-content'>
                                                <form action='delete.php?id={$row['id']}' method='post'>
                                                    <span class='close-btn' onclick='closePopup({$row['id']})'>&times;</span>
                                                    <h6>Are you sure you want to <strong style='color: red;'>Delete</strong> this Booking?</h6>  
                                                    <input type='hidden' name='id' value='{$row['id']}'>
                                                    <br>
                                                    <input type='submit' value='YES' class='form-control' name='yes'>
                                                    <hr> 
                                                    <button type='submit' name='no' class='form-control' onclick='closePopup({$row['id']})'>NO</button>
                                                    <br>
                                                </form>
                                            </div>
                                        </div>
                                        <script>
                                        function openPopup(accountId) {
                                            document.getElementById('popup-' + accountId).style.display = 'flex';
                                        }

                                        function closePopup(accountId) {
                                            document.getElementById('popup-' + accountId).style.display = 'none';
                                        }
                                        </script>
                                    </div>
                                </td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

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
    background-color: var(--secondary-color);
    padding: 20px;
    min-height: 100vh;
}

.booking-management-container {
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    padding: 30px;
}

.booking-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    border-bottom: 2px solid var(--border-color);
    padding-bottom: 20px;
}

.booking-header h1 {
    color: var(--primary-color);
    margin: 0;
    font-weight: 600;
}

.booking-type-select {
    padding: 10px 15px;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    background-color: white;
    font-size: 16px;
    transition: all 0.3s ease;
}

.booking-type-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.1);
}

.booking-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    color: #333333;
}

.booking-table thead {
    background-color: var(--table-header-bg);
    color: white;
}

.booking-table th, .booking-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.booking-table th {
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1px;
    font-weight: 600;
}

.booking-table tbody tr:hover {
    background-color: var(--table-row-hover);
}

.customer-info {
    display: flex;
    align-items: center;
}

.customer-name {
    font-weight: 500;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.status-badge.status-confirmed {
    background-color: #4caf50;
    color: white;
}

.status-badge.status-pending {
    background-color: #ff9800;
    color: white;
}

.status-badge.status-cancelled {
    background-color: #f44336;
    color: white;
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

.payment-btn {
    background-color: #28a745;
    color: white;
}

.edit-btn {
    background-color: #17a2b8;
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

@media (max-width: 768px) {
    .booking-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .booking-type-select {
        width: 100%;
        margin-top: 15px;
    }

    .booking-table {
        font-size: 14px;
    }

    .booking-table th, .booking-table td {
        padding: 10px;
    }
}
</style>