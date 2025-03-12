<?php
include('../../resources/database/config.php');
include("../../admin/includes/system_update.php"); 
include("../includes/template.php");

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

// Fetch guests from database
$query = "SELECT * FROM guest ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!-- Add this inside the #content div from template.php -->
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-users"></i> Guest Management</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="create.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Guest
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $row['guest_id']; ?></td>
                                    <td><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                                    <td><?php echo $row['gender']; ?></td>
                                    <td><?php echo $row['contact']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
    
                                    <td>
                                        <div class="btn-group">
                                            <a href="../booking/create.php?guest_id=<?php echo $row['guest_id']; ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-bed"></i> Book Room
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="7" class="text-center">No guests found</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-group .btn {
        margin-right: 2px;
    }
    
    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .card {
        border: none;
        border-radius: 10px;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
    }
</style>

<!-- <script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this guest?')) {
        window.location.href = 'delete.php?id=' + id;
    }
}
</script> -->

<?php
// Close the content div and add the closing body and html tags
echo "</div></body></html>";
?>