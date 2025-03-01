<?php 

include ('../includes/template.html');
include('../../resources/database/config.php');

?>
<style>
/* Content Box */
.content {
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

/* Table Styling */
.table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #ddd;
}

.table th {
    background: #007bff !important;
    color: white !important;
    padding: 15px !important;
    text-transform: uppercase;
    font-weight: bold;
    border: none !important;
}

.table td {
    padding: 15px !important;
    border-bottom: 1px solid #ddd !important;
    font-size: 16px !important;
}

/* Hover Effect */
.table tr:hover {
    background: #f0f8ff !important; /* Light Blue */
    transition: 0.3s ease-in-out;
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 8px 14px !important;
    border-radius: 6px !important;
    font-weight: 500;
    text-decoration: none;
    transition: 0.3s ease-in-out;
    font-size: 14px !important;
}

/* Edit Button */
.btn-primary {
    background: #007bff !important;
    color: white !important;
    border: none !important;
}

.btn-primary:hover {
    background: #0056b3 !important;
}

/* Delete Button */
.btn-danger {
    background: #dc3545 !important;
    color: white !important;
    border: none !important;
}

.btn-danger:hover {
    background: #a71d2a !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .table th, .table td {
        padding: 10px !important;
        font-size: 14px !important;
    }

    .btn {
        padding: 6px 12px !important;
        font-size: 14px !important;
    }
}
</style>

<div class="content" style="color:black;">
    <?php
    include ('alert.php');
    include ('filter.php'); ?>
    <br>
    <table class="table table-striped" style="text-align: center;">
        <tr>
        <th>Room Code</th>
        <th>Status</th>
        <th>Price</th>
        <th>Action</th>
        </tr>
        <?php 
       while($row=mysqli_fetch_assoc($result)){
        $discount=($row['discounted_price']>0 ? $row['discounted_price']." (".$row['discount_percentage']."% Off)" : $row['price']);
        print "<tr>
        <td>{$row['room_code']}</td>
        <td>{$row['room_status']}</td>
        <td>₱{$discount}</td>
        <td>
        <a href='edit.php?id={$row['room_id']}' class='btn btn-primary'><i class='fas fa-edit'></i></a>
        <a href='delete.php?id={$row['room_id']}&index_click=true' class='btn btn-danger'><i class='fas fa-trash'></i></a></td>
        
        </tr>";
       }
        ?>
    </table>
</div>
