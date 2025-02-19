<?php 
include ('../includes/template.html');
include('../../resources/database/config.php');

$sql="SELECT * FROM customer_booking;";
$result=mysqli_query($conn,$sql);


?>
<style>
    .booking-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.booking-table thead {
    background: #222;
    color: white;
}

.booking-table th, .booking-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.booking-table tbody tr:hover {
    background: #f9f9f9;
}

/* Buttons */
.btn-primary {
    background: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    transition: background 0.3s;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    transition: background 0.3s;
}

.btn-danger:hover {
    background: #b02a37;
}

/* Form Styling */
.booking-form {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.booking-form label {
    font-weight: bold;
}

.booking-form input, .booking-form select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Cards */
.card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}
</style>
<div class="content">   
    <!-- Booking Table -->
    <div class="card">
        <h4 class="mb-3">Existing Bookings</h4>
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
                        print "<tr>
                    <td>{$row['fname']} {$row['lname']}</td>
                    <td>{$row['room_code']}</td>
                    <td>{$row['check_in']}</td>
                    <td>{$row['check_out']}</td>
                    <td><span class='badge bg-success'>{$row['status']}</span></td>
                    <td>
                        <a href='edit.php?id={$row['id']}' class='btn btn-primary btn-sm'><i class='fas fa-edit'></i></a>
                        <a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a>

                    </td>
                </tr>";
                    }
                }
                ?>
            
            
            
            
            
            <!-- <tr>
                    <td>John Doe</td>
                    <td>Deluxe</td>
                    <td>2025-03-10</td>
                    <td>2025-03-15</td>
                    <td><span class="badge bg-success">Confirmed</span></td>
                    <td>
                        <button class="btn btn-primary btn-sm">Edit</button>
                        <button class="btn btn-danger btn-sm">Cancel</button>
                    </td> -->
                </tr>
            </tbody>
        </table>
    </div>
</div>
