<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include ('../includes/template.php');
include("../includes/system_update.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --background-light: #f4f6f7;
            --text-dark: #2c3e50;
            --border-soft: #e0e4e8;
        }

        .content {
            max-width: 1200px;
            margin: 20px auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .admin-filter-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 25px;
            background-color: #f8f9fa;
            border-bottom: 1px solid var(--border-soft);
        }

        .admin-tabs-header {
            display: flex;
            gap: 10px;
        }

        .admin-tab {
            padding: 10px 20px;
            border: 1px solid var(--border-soft);
            border-bottom: none;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6c757d;
            font-weight: 600;
            position: relative;
            overflow: hidden;
        }

        .admin-tab::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: transparent;
            transition: background-color 0.3s ease;
        }

        .admin-tab:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        .admin-tab.active {
            color: var(--primary-color);
            border-bottom-color: white;
        }

        .admin-tab.active::before {
            background-color: var(--primary-color);
        }

        .admin-tab-content {
            display: none;
            padding: 25px;
        }

        .admin-tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .admin-tab-content h3 {
            margin-top: 0;
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .admin-data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-data-table th {
            background-color: #f1f3f4;
            color: #B0B0B0;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--border-soft);
        }

        .admin-data-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-soft);
            color: black;
        }

        .admin-action-buttons {
            display: flex;
            gap: 10px;
        }

        .admin-action-buttons button {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-btn-restore {
            background-color: var(--primary-color);
            color: white;
        }

        .admin-btn-delete {
            background-color: #e74c3c;
            color: white;
        }

        .admin-btn-restore:hover {
            background-color: var(--secondary-color);
        }

        .admin-btn-delete:hover {
            background-color: #c0392b;
        }

        .admin-empty-state {
            text-align: center;
            color: #7f8c8d;
            padding: 50px;
            border-radius: 8px;
            background-color: #f9f9f9;
            border: 1px dashed var(--border-soft);
        }

        .admin-empty-state-icon {
            font-size: 48px;
            color: #bdc3c7;
            margin-bottom: 15px;
        }

        .admin-empty-state-message {
            font-size: 16px;
            color: #95a5a6;
        }

        /* Booking Table Styling */
        .booking-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .booking-table th {
            background-color: var(--primary-color);
            color: white;
            padding: 12px;
            text-align: left;
        }

        .booking-table td {
            padding: 10px;
            border-bottom: 1px solid var(--border-soft);
            color: var(--text-dark);
        }

        .booking-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .status-confirmed {
            background-color: #2ecc71;
            color: white;
        }

        .status-pending {
            background-color: #f39c12;
            color: white;
        }

        .status-cancelled {
            background-color: #e74c3c;
            color: white;
        }
    </style>


    <div class="content">
        <div class="admin-filter-section">
            <div class="admin-tabs-header">
                <div class="admin-tab active" onclick="showTab('recently-deleted')">
                    Recently Deleted
                </div>
                <div class="admin-tab" onclick="showTab('booking-history')">
                    Booking History
                </div>
                <div class="admin-tab" onclick="showTab('emails-sent')">
                    Emails Sent
                </div>
            </div>
        </div>

        <div id="recently-deleted" class="admin-tab-content active">
            <h3>Recently Deleted</h3>
            <table class="admin-data-table">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Deleted Date</th>
                    <th>Actions</th>
                </tr>

                <?php
                $sql = "SELECT * FROM account WHERE deleted_at IS NOT NULL";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['account_id']}</td>";
                    echo "<td>{$row['username']}</td>";
                    echo "<td>{$row['deleted_at']}</td>";
                    echo "<td>";
                    echo "<div class='admin-action-buttons'>";
                    echo "<button class='admin-btn-restore' onclick=\"window.location.href='../customer/delete.php?restore={$row['account_id']}'\">Restore</button>";
                    echo "<button class='admin-btn-delete' onclick=\"window.location.href='../customer/delete.php?delete_permanent={$row['account_id']}'\">Delete Permanently</button>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <div id="booking-history" class="admin-tab-content">
            <h3>Booking History</h3>
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
                        $sql = "
                           SELECT * FROM (
                            SELECT 'user' AS identifier, fname, lname, room_code, check_in, check_out, status, id
                            FROM customer_booking
                            UNION
                            SELECT 'guest' AS identifier, fname, lname, room_code, check_in, check_out, status, id
                            FROM guest_booking
                        ) AS combined
                        WHERE status IN ('completed', 'cancelled') 
                        ORDER BY check_in DESC;
                        ";
                        $result = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_assoc($result)){
                                $check_in = date("F d, Y h:i A", strtotime($row['check_in']));
                                $check_out = date("F d, Y h:i A", strtotime($row['check_out']));
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
                                                <a href='../booking/edit.php?id={$row['id']}' class='btn btn-primary' title='Process Payment'>
                                                    <i class='fas fa-eye'></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
        </div>

        <div id="emails-sent" class="admin-tab-content">
            <h3>Emails Sent</h3>
            <div class="admin-empty-state">
                <div class="admin-empty-state-icon">
                    <i class="fas fa-envelope-open"></i>
                </div>
                <div class="admin-empty-state-message">
                    No email logs have been recorded yet.
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabId) {
            document.querySelectorAll('.admin-tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.admin-tab').forEach(tab => tab.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            document.querySelector(`[onclick="showTab('${tabId}')"]`).classList.add('active');
        }
    </script>
</body>
</html>