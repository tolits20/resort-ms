<?php 
include ('../includes/template.html');
include('../../resources/database/config.php');

$sql="SELECT * FROM customer_booking ORDER BY id DESC;";
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




.popup-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .popup-content {
        background: white;
        padding: 20px;
        width: 400px;
        text-align: start;
        border-radius: 8px;
        position: relative;
    }

    .popup-content input{
        border:solid 1px;
    }
    .popup-content input[name='yes']:hover{
        border:solid 1px;
        background-color: red;
        color: #fff;
    }
    .popup-content button[name='no']:hover{
        border:solid 1px;
        background-color: green;
        color: #fff;
    }


    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
        color: red;
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
                        <button class='btn btn-danger' type='button' onclick='openPopup({$row['id']})'><i class ='fas fa-trash'></i></button>
                             <div class='popup-overlay' id='popup-{$row['id']}' style='color: black; display: none;'>
                                <div class='popup-content'>
                                    <form action='delete.php?id={$row['id']}' method='post'>
                                        <span class='close-btn' onclick='closePopup({$row['id']})'>&times;</span>
                                        <h6>Are you sure you want to <strong style='color: red;'>Delete</strong> this Account?</h6>  
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
