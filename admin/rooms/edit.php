<?php 

include ('../includes/template.html');
include('../../resources/database/config.php');

$id=$_GET['id'];
$sql="SELECT * FROM room  WHERE room_id = $id";
$result=mysqli_query($conn,$sql);
$room=mysqli_fetch_assoc($result);
$sql1="SELECT * FROM room_gallery WHERE room_id=$id";
$result1=mysqli_query($conn,$sql1)
?>
<style>
.content {
    max-width: 1000px;
    margin: 40px auto;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
}

.content h2 {
    text-align: center;
    color: #333;
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 20px;
}

.content .room-details {
    display: flex;
    gap: 20px;
    justify-content: space-between;
}

.content .room-details .form-group {
    width: 32%;
}

.content .form-label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
    color: #444;
}

.content .form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
    transition: 0.3s ease-in-out;
}

.content .form-control:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0px 0px 8px rgba(0, 123, 255, 0.2);
}

.content .gallery-section {
    margin-top: 30px;
}

.content .gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 10px;
}

.content .gallery-item {
    position: relative;
    width: 180px;
    height: 120px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #ddd;
    transition: 0.3s;
}

.content .gallery-item:hover {
    transform: scale(1.05);
    border-color: #007bff;
}

.content .gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.content .delete-icon {
    position: absolute;
    top: 5px;
    right: 5px;
    background: red;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
}

.content .delete-icon:hover {
    background: darkred;
}

.content input[type="file"] {
    margin-top: 10px;
}

.content .btn-success {
    width: 100%;
    padding: 12px;
    background: #28a745;
    color: white;
    font-size: 18px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
    display: block;
    text-align: center;
}

.content .btn-success:hover {
    background: #218838;
}
       
</style>
</head>
<body>
<div class="content">
    <h2>Edit Room</h2>
    <form action="update.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">

        <div class="room-details">
            <div class="form-group">
                <label for="room_number" class="form-label">Room Code</label>
                <input type="text" class="form-control" name="room_code" value="<?php echo $room['room_code']; ?>" required>
            </div>

            <div class="form-group">
                <label for="type" class="form-label">Room Type</label>
                <select class="form-select" name="type" required>
                    <option value="standard" <?php if($room['type'] == 'standard') echo 'selected'; ?>>Standard</option>
                    <option value="premium" <?php if($room['type'] == 'premium') echo 'selected'; ?>>Premium</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" name="status" required>
                    <option value="available" <?php if($room['status'] == 'available') echo 'selected'; ?>>Available</option>
                    <option value="booked" <?php if($room['status'] == 'booked') echo 'selected'; ?>>Booked</option>
                    <option value="under maintenance" <?php if($room['status'] == 'under maintenance') echo 'selected'; ?>>Under Maintenance</option>
                </select>
            </div>
        </div>

        <div class="gallery-section">
            <h3>Gallery</h3>
            <div class="gallery">
                <?php while ($img=mysqli_fetch_assoc($result1)){ ?>
                    <div class="gallery-item">
                        <img src="../../resources/assets/room_images/<?php echo $img['room_img']; ?>" alt="Room Image">
                        <button type="button" class="delete-icon" onclick="deleteImage('<?php echo $img['room_img']; ?>')">&times;</button>
                    </div>
                <?php } ?>
            </div>
            <input type="file" name="images[]" multiple class="form-control mt-2">
        </div>
        <br>
        <button type="submit" class="btn btn-success">Save Changes</button>
    </form>
</div>

<script>
    function deleteImage(imageName) {
            window.location.href = "delete.php?image=" + imageName + "&room_id=<?php echo $room['room_id']; ?>&click=true";
        
    }
</script>