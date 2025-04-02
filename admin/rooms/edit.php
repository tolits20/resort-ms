<?php 
include('../../resources/database/config.php');
include ('../includes/template.php');
include('../includes/page_authentication.php');
include("../includes/system_update.php");

$id = $_GET['id'];
$sql = "SELECT * FROM room WHERE room_id = $id";
$result = mysqli_query($conn, $sql);
$room = mysqli_fetch_assoc($result);
$sql1 = "SELECT * FROM room_gallery WHERE room_id = $id";
$result1 = mysqli_query($conn, $sql1);
?>



    <title>Edit Room Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f5f7fa;
            --text-color: #2c3e50;
            --border-radius: 12px;
        }

     .content{
        width: 100%;
     }

        .room-edit-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            padding: 40px;
            transition: all 0.3s ease;
        }

        .room-edit-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 15px;
        }

        .room-edit-header h2 {
            margin: 0;
            color: var(--text-color);
            font-weight: 600;
        }

        .room-code-badge {
            background-color: var(--primary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
        }

        .form-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .form-label {
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .form-label i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        .form-control, .form-select {
            padding: 12px 15px;
            border: 1px solid #e0e7f3;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .gallery-section {
            margin-top: 30px;
            background-color: var(--secondary-color);
            border-radius: 8px;
            padding: 20px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .gallery-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .gallery-item:hover {
            transform: scale(1.05);
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(231, 76, 60, 0.8);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: rgba(231, 76, 60, 1);
        }

        .file-upload-container {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: background-color 0.3s ease;
        }

        .file-upload-btn:hover {
            background-color: #3a7bd5;
        }

        .btn-save {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .form-section {
                grid-template-columns: 1fr;
            }

            .room-edit-container {
                padding: 20px;
            }
        }
    </style>

<div class="content">
    <div class="room-edit-container">
        <div class="room-edit-header">
            <h2>Edit Room Details</h2>
            <div class="room-code-badge">
                Room Code: <?php echo $room['room_code']; ?>
            </div>
        </div>

        <form action="update.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">

            <div class="form-section">
                <div>
                    <div class="form-group">
                        <label for="room_code" class="form-label">
                            <i class="fas fa-barcode"></i>Name
                        </label>
                        <input type="text" id="room_code" class="form-control" name="room_code" 
                               value="<?php echo $room['room_code']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="type" class="form-label">
                            <i class="fas fa-bed"></i>Type
                        </label>
                        <select id="type" class="form-select" name="type" required>
                            <option value="standard" <?php echo ($room['room_type'] == 'standard') ? 'selected' : ''; ?>>
                                Standard Room
                            </option>
                            <option value="single_room" <?php echo ($room['room_type'] == 'single_room') ? 'selected' : ''; ?>>
                                Premium Room
                            </option>
                            <option value="family_room" <?php echo ($room['room_type'] == 'family_room') ? 'selected' : ''; ?>>
                                Family Room
                            </option>
                            <option value="studio_room" <?php echo ($room['room_type'] == 'studio_room') ? 'selected' : ''; ?>>
                                Studio Room
                            </option>
                            <option value="suite" <?php echo ($room['room_type'] == 'suite') ? 'selected' : ''; ?>>
                                Suite Room
                            </option>
                            <option value="deluxe" <?php echo ($room['room_type'] == 'deluxe') ? 'selected' : ''; ?>>
                                Deluxe Room
                            </option>
                            <option value="deluxe" <?php echo ($room['room_type'] == 'banquet_hall') ? 'selected' : ''; ?>>
                                Banquet Hall
                            </option>
                            <option value="deluxe" <?php echo ($room['room_type'] == 'garden_venue') ? 'selected' : ''; ?>>
                                Garden Venue
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">
                            <i class="fas fa-info-circle"></i> Room Status
                        </label>
                        <select id="status" class="form-select" name="status" required>
                            <option value="available" <?php echo ($room['room_status'] == 'available') ? 'selected' : ''; ?>>
                                Available
                            </option>
                            <option value="booked" <?php echo ($room['room_status'] == 'booked') ? 'selected' : ''; ?>>
                                Booked
                            </option>
                            <option value="under maintenance" <?php echo ($room['room_status'] == 'under maintenance') ? 'selected' : ''; ?>>
                                Under Maintenance
                            </option>
                        </select>
                    </div>
                </div>

                <div>
                    <div class="form-group">
                        <label for="price" class="form-label">
                            <i class="fas fa-dollar-sign"></i> Price per Night
                        </label>
                        <input type="number" id="price" class="form-control" name="price" 
                               value="<?php echo $room['price']; ?>" min="0" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">
                            <i class="fas fa-pencil-alt"></i> Room Description
                        </label>
                        <textarea id="description" class="form-control" name="description" rows="4" 
                                  placeholder="Enter room description (optional)"><?php echo $room['description'] ?? ''; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="gallery-section">
                <h3>Room Gallery</h3>
                <div class="gallery-grid">
                    <?php while ($img = mysqli_fetch_assoc($result1)) { ?>
                        <div class="gallery-item">
                            <img src="../../resources/assets/room_images/<?php echo $img['room_img']; ?>" alt="Room Image">
                            <button type="button" class="delete-btn" onclick="deleteImage('<?php echo $img['room_img']; ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    <?php } ?>
                </div>

                <div class="file-upload-container">
                    <input type="file" name="images[]" multiple class="file-upload-input" id="image-upload" accept="image/*">
                    <label for="image-upload" class="file-upload-btn">
                        <i class="fas fa-cloud-upload-alt"></i> Upload Images
                    </label>
                </div>
            </div>

            <button type="submit" name="update" class="btn-save mt-4">
                Save Changes <i class="fas fa-save"></i>
            </button>
        </form>
    </div>

    <script>
        function deleteImage(imageName) {
            if (confirm(`Are you sure you want to delete the image ${imageName}?`)) {
                // Add AJAX call or form submission to handle image deletion
                window.location.href = `delete.php?id=<?php echo $id; ?>&img=${imageName}&click=1`;
            }
        }

        // File upload preview
        document.getElementById('image-upload').addEventListener('change', function(event) {
            const files = event.target.files;
            const galleryGrid = document.querySelector('.gallery-grid');

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.classList.add('gallery-item', 'preview-item');
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="delete-btn remove-preview">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    const removeBtn = previewItem.querySelector('.remove-preview');
                    removeBtn.addEventListener('click', () => {
                        previewItem.remove();
                    });

                    galleryGrid.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
</div>