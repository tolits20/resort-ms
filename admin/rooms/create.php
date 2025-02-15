<?php 

include ('../includes/template.html');
include('../../resources/database/config.php');

?>

<div class="content">
<style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            max-width: 600px;
            margin: auto;
            border-radius: 10px;
        }
        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .preview-container img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h3 class="text-center mb-4">🛏️ Add Room</h3>
        
        <form action="store.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label fw-bold">Room Code</label>
                <input type="text" name="room_number" class="form-control" placeholder="Enter room number" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Room Type</label>
                <select name="type" class="form-select" required>
                    <option value="standard">Standard</option>
                    <option value="premium">Premium</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Status</label>
                <select name="status" class="form-select" required>
                    <option value="available">Available</option>
                    <option value="booked">Booked</option>
                    <option value="under_maintenance">Under Maintenance</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Upload Room Images</label>
                <input type="file" name="images[]" class="form-control" multiple id="imageUpload" required>
                <div class="mt-2 preview-container" id="imagePreview"></div>
            </div>

            <button type="submit" name="submit" class="btn btn-primary w-100">Save Room</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('imageUpload').addEventListener('change', function(event) {
        let preview = document.getElementById('imagePreview');
        preview.innerHTML = '';

        Array.from(event.target.files).forEach(file => {
            let reader = new FileReader();
            reader.onload = function(e) {
                let img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
</div>