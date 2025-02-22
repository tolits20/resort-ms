<?php 
include ('../includes/template.html');
include('../../resources/database/config.php');

$sql="SELECT * FROM book_payment";
$result=mysqli_query($conn,$sql);


?>
<style>

.card {
    width: 100%;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
}

h2 {
    font-weight: bold;
}

.table {
    margin-top: 15px;
    border-radius: 10px;
    overflow: hidden;
}


.table th {
    text-align: center;
}

.table td {
    vertical-align: middle;
    text-align: center;
}

.badge {
    font-size: 14px;
    padding: 6px 10px;
    border-radius: 5px;
}

.btn {
    font-weight: bold;
    border-radius: 8px;
    transition: 0.3s;
}

.btn-primary {
    background: #007bff;
    border: none;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-success {
    background: #28a745;
    border: none;
}

.btn-success:hover {
    background: #218838;
}

.btn-secondary {
    background: #6c757d;
    border: none;
}

.btn-secondary:hover {
    background: #5a6268;
}

</style>


<div class="content">
    <div class="card p-4">
        <h2 class="mb-4 text-center"><i class="fas fa-credit-card"></i> Payment Management</h2>

        <div class="table-responsive" >
            <table class="table table-striped" style="border-radius: 10px;">
                <thead class="table-dark">
                    <tr>
                        <th>Payment ID</th>
                        <th>Customer Name</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                   <?php
                   while($row=mysqli_fetch_assoc($result)){
                    print " <tr>
                        <td>{$row['ID']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['amount']}</td>
                        <td>{$row['payment_method']}</td>
                        <td><span class='badge bg-warning text-dark'>{$row['payment_status']}</span></td>
                        <td>
                            <a class='btn btn-primary btn-sm' data-bs-toggle='modal' href='edit.php?{$row['ID']}' data-bs-target='#editPaymentModal'>
                                <i class='fas fa-edit'></i>
                            </a>
                             <a class='btn btn-danger btn-sm' data-bs-toggle='modal' href='delete.php?{$row['ID']}' data-bs-target='#editPaymentModal'>
                                <i class='fas fa-trash'></i>
                            </a>
                        </td>
                    </tr>";
                   }
                   
                   ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Payment Modal -->

