<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include ('../includes/template.php');
include("../includes/system_update.php");
?>

<style>
/* Content Box */
.content {
    width: 100%;
    padding: 25px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
}

/* Form Container */
.form-container {
    max-width: 800px;
    margin: 0 auto;
}

/* Form Header */
.form-header {
    text-align: center;
    margin-bottom: 30px;
    position: relative;
}

.form-title {
    font-size: 24px;
    font-weight: 700;
    color: #343a40;
    margin-bottom: 10px;
    position: relative;
    display: inline-block;
}

.form-title:after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background: #007bff;
    border-radius: 2px;
}

.form-subtitle {
    font-size: 16px;
    color: #6c757d;
    margin-top: 15px;
}

/* Form Card */
.form-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    border: 1px solid #eaeaea;
}

/* Form Sections */
.form-section {
    padding: 25px;
    border-bottom: 1px solid #eaeaea;
}

.form-section:last-child {
    border-bottom: none;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #343a40;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eaeaea;
}

/* Form Fields */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #495057;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    font-size: 16px;
    border: 1px solid #ced4da;
    border-radius: 8px;
    background-color: #f8f9fa;
    transition: all 0.2s ease;
}

.form-control:focus {
    background-color: #fff;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.form-select {
    width: 100%;
    padding: 12px 15px;
    font-size: 16px;
    border: 1px solid #ced4da;
    border-radius: 8px;
    background-color: #f8f9fa;
    transition: all 0.2s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23343a40' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 16px 12px;
}

.form-select:focus {
    background-color: #fff;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.form-textarea {
    width: 100%;
    min-height: 150px;
    padding: 12px 15px;
    font-size: 16px;
    border: 1px solid #ced4da;
    border-radius: 8px;
    background-color: #f8f9fa;
    transition: all 0.2s ease;
    resize: vertical;
}

.form-textarea:focus {
    background-color: #fff;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

/* Pricing Fields */
.price-group {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.price-field {
    flex: 1;
    min-width: 150px;
}

.price-input {
    position: relative;
}

.price-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.price-input .form-control {
    padding-left: 35px;
}

/* Image Upload */
.upload-container {
    margin-top: 20px;
}

.upload-area {
    border: 2px dashed #ced4da;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    background-color: #f8f9fa;
}

.upload-area:hover {
    border-color: #007bff;
    background-color: rgba(0, 123, 255, 0.05);
}

.upload-icon {
    font-size: 40px;
    color: #007bff;
    margin-bottom: 10px;
}

.upload-text {
    font-size: 16px;
    color: #6c757d;
    margin-bottom: 5px;
}

.upload-subtext {
    font-size: 14px;
    color: #adb5bd;
}

.file-input {
    display: none;
}

/* Image Preview */
.preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 20px;
}

.preview-item {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-remove {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 24px;
    height: 24px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.preview-item:hover .preview-remove {
    opacity: 1;
}

/* Status Indicators */
.status-options {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 10px;
}

.status-option {
    flex: 1;
    min-width: 150px;
    position: relative;
}

.status-radio {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.status-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px;
    border: 2px solid #eaeaea;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.status-icon {
    font-size: 24px;
    margin-bottom: 8px;
}

.status-text {
    font-weight: 500;
}

.status-radio:checked + .status-label {
    border-color: #007bff;
    background-color: rgba(0, 123, 255, 0.05);
}

.status-available .status-icon {
    color: #28a745;
}

.status-booked .status-icon {
    color: #dc3545;
}

.status-maintenance .status-icon {
    color: #ffc107;
}

/* Submit Button */
.submit-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    background-color: #f8f9fa;
}

.submit-btn {
    padding: 12px 25px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.submit-btn:hover {
    background-color: #0069d9;
}

.cancel-btn {
    padding: 12px 25px;
    background-color: #f8f9fa;
    color: #6c757d;
    border: 1px solid #ced4da;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.cancel-btn:hover {
    background-color: #e9ecef;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-section {
        padding: 20px;
    }
    
    .section-title {
        font-size: 16px;
    }
    
    .form-label {
        font-size: 14px;
    }
    
    .form-control, .form-select, .form-textarea {
        padding: 10px 12px;
        font-size: 14px;
    }
    
    .upload-area {
        padding: 20px;
    }
    
    .upload-icon {
        font-size: 32px;
    }
    
    .preview-item {
        width: 100px;
        height: 100px;
    }
}
</style>

<div class="content">
    <div class="form-container">
        <div class="form-header">
            <h2 class="form-title">Add New Room</h2>
            <p class="form-subtitle">Fill in the details below to add a new room to the system</p>
        </div>
        
        <form action="store.php" method="post" enctype="multipart/form-data">
            <div class="form-card">
                <!-- Basic Information Section -->
                <div class="form-section">
                    <h3 class="section-title">Basic Information</h3>
                    
                    <div class="form-group">
                        <label class="form-label" for="room_number">Room Code</label>
                        <input type="text" name="room_number" id="room_number" class="form-control" placeholder="Enter room code (e.g. RM-101)" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="room_type">Room Type</label>
                        <select name="type" id="room_type" class="form-select" required>
                            <option value="">Select room type</option>
                            <option value="standard">Standard</option>
                            <option value="premium">Premium</option>
                            <option value="deluxe">Deluxe</option>
                            <option value="suite">Suite</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Room Status</label>
                        <div class="status-options">
                            <div class="status-option">
                                <input type="radio" id="status_available" name="status" value="available" class="status-radio" checked>
                                <label for="status_available" class="status-label status-available">
                                    <span class="status-icon"><i class="fas fa-check-circle"></i></span>
                                    <span class="status-text">Available</span>
                                </label>
                            </div>
                            
                            <div class="status-option">
                                <input type="radio" id="status_booked" name="status" value="booked" class="status-radio">
                                <label for="status_booked" class="status-label status-booked">
                                    <span class="status-icon"><i class="fas fa-ban"></i></span>
                                    <span class="status-text">Booked</span>
                                </label>
                            </div>
                            
                            <div class="status-option">
                                <input type="radio" id="status_maintenance" name="status" value="under maintenance" class="status-radio">
                                <label for="status_maintenance" class="status-label status-maintenance">
                                    <span class="status-icon"><i class="fas fa-tools"></i></span>
                                    <span class="status-text">Maintenance</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Description Section -->
                <div class="form-section">
                    <h3 class="section-title">Room Description</h3>
                    
                    <div class="form-group">
                        <label class="form-label" for="room_description">Description</label>
                        <textarea name="description" id="room_description" class="form-textarea" placeholder="Provide a detailed description of the room (features, amenities, size, etc.)" rows="5"></textarea>
                    </div>
                </div>
                
                <!-- Pricing Section -->
                <div class="form-section">
                    <h3 class="section-title">Pricing Information</h3>
                    
                    <div class="price-group">
                        <div class="price-field">
                            <div class="form-group">
                                <label class="form-label" for="room_price">Regular Price</label>
                                <div class="price-input">
                                    <span class="price-icon">₱</span>
                                    <input type="number" name="price" id="room_price" class="form-control" placeholder="0.00" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Room Images Section -->
                <div class="form-section">
                    <h3 class="section-title">Room Images</h3>
                    
                    <div class="upload-container">
                        <div class="upload-area" id="uploadArea">
                            <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                            <p class="upload-text">Drag and drop images here or click to browse</p>
                            <p class="upload-subtext">Upload up to 5 images (Max 2MB each)</p>
                            <input type="file" name="images[]" id="fileInput" class="file-input" accept="image/*" multiple>
                        </div>
                        
                        <div class="preview-container" id="previewContainer">
                            <!-- Image previews will be added here dynamically -->
                        </div>
                    </div>
                </div>               
                
                <!-- Form Actions -->
                <div class="submit-section">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="submit" class="submit-btn">Add Room</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // JavaScript for image upload preview
    const fileInput = document.getElementById('fileInput');
    const uploadArea = document.getElementById('uploadArea');
    const previewContainer = document.getElementById('previewContainer');
    
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('active');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('active');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('active');
        
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            updatePreview();
        }
    });
    
    fileInput.addEventListener('change', updatePreview);
    
    function updatePreview() {
        previewContainer.innerHTML = '';
        
        const files = fileInput.files;
        
        if (files) {
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = (e) => {
                        const preview = document.createElement('div');
                        preview.className = 'preview-item';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        
                        const removeBtn = document.createElement('div');
                        removeBtn.className = 'preview-remove';
                        removeBtn.innerHTML = '×';
                        removeBtn.addEventListener('click', () => {
                            preview.remove();
                            // Note: This doesn't actually remove the file from the input
                            // For that, you would need a more complex solution
                        });
                        
                        preview.appendChild(img);
                        preview.appendChild(removeBtn);
                        previewContainer.appendChild(preview);
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        }
    }
</script>