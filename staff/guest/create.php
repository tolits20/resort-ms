<?php
include('../../resources/database/config.php');
include("../../admin/includes/system_update.php"); 
include("../includes/template.php");

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}
?>

<!-- Add this inside the #content div from template.php -->
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Create New Guest</h4>
                </div>
                <div class="card-body">
                    <form action="store.php" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact" class="form-label">Contact</label>
                                <input type="text" class="form-control" id="contact" name="contact" 
                                       pattern="\d+" title="Only numbers are allowed" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Guest
                            </button>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
    }
    
    .card-header {
        border-radius: 10px 10px 0 0;
        background-color: #3498db !important;
    }
    
    .form-control, .form-select {
        border: 2px solid #e9ecef;
        padding: 0.6rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }
    
    .btn {
        padding: 0.6rem;
    }
    
    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
    }
    
    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }
    
    .btn-secondary {
        background-color: #95a5a6;
        border-color: #95a5a6;
    }
    
    .btn-secondary:hover {
        background-color: #7f8c8d;
        border-color: #7f8c8d;
    }
    
    .form-label {
        font-weight: 500;
        color: #2c3e50;
    }
</style>

<?php
// Close the content div and add the closing body and html tags
echo "</div></body></html>";
?>