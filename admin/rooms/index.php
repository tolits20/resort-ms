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

/* Card Layout */
.room-card-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.room-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #eaeaea;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.room-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

/* Room Image */
.room-image {
    position: relative;
    width: 100%;
    height: 180px;
    overflow: hidden;
    background-color: #f8f9fa;
}

.room-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.room-card:hover .room-image img {
    transform: scale(1.05);
}

.no-image {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #6c757d;
    font-size: 16px;
}

.no-image i {
    font-size: 40px;
    margin-bottom: 10px;
}

/* Room Status Indicator */
.status-indicator {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    z-index: 2;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.available .status-indicator {
    background: #28a745;
    color: white;
}

.occupied .status-indicator {
    background: #dc3545;
    color: white;
}

.maintenance .status-indicator {
    background: #ffc107;
    color: #212529;
}

/* Room Header */
.room-header {
    background: #f8f9fa;
    padding: 15px;
    border-bottom: 1px solid #eaeaea;
}

.room-code {
    font-size: 18px;
    font-weight: 700;
    color: #343a40;
    margin: 0;
}

/* Room Content */
.room-content {
    padding: 15px;
    flex-grow: 1;
}

.price-container {
    margin-bottom: 15px;
}

.price-label {
    font-size: 13px;
    color: #6c757d;
    margin-bottom: 3px;
}

.price {
    font-size: 20px;
    font-weight: 700;
    color: #343a40;
}

.discount {
    display: inline-block;
    background: #dc3545;
    color: white;
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 12px;
    margin-left: 8px;
    vertical-align: middle;
}

/* Action Buttons */
.room-actions {
    display: flex;
    gap: 10px;
    padding: 15px;
    border-top: 1px solid #eaeaea;
    margin-top: auto;
}

.btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 14px;
    border: none;
    cursor: pointer;
}

.btn i {
    margin-right: 8px;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0069d9;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

/* List View Toggle */
.view-toggle {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 15px;
}

.toggle-btn {
    padding: 8px 12px;
    border-radius: 6px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s ease;
    margin-left: 10px;
}

.toggle-btn:hover, .toggle-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
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
    vertical-align: middle;
}

.table tr:hover {
    background: #f0f8ff !important;
    transition: 0.3s ease-in-out;
}

/* Table Image */
.table-img {
    width: 60px;
    height: 60px;
    border-radius: 6px;
    object-fit: cover;
}

/* Status Badge for Table */
.status-badge {
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    display: inline-block;
    min-width: 100px;
    text-align: center;
}

.available .status-badge,
.status-badge.available {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.occupied .status-badge,
.status-badge.occupied {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.maintenance .status-badge,
.status-badge.maintenance {
    background-color: rgba(255, 193, 7, 0.1);
    color: #856404;
}

/* Price Styling for Table */
.price-tag {
    font-weight: 600;
    color: #28a745;
}

.discount-text {
    font-size: 13px;
    color: #dc3545;
    margin-left: 5px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .room-card-container {
        grid-template-columns: 1fr;
    }
    
    .table th, .table td {
        padding: 10px !important;
        font-size: 14px !important;
    }
    
    .room-image {
        height: 150px;
    }
}
</style>

<div class="content" style="color:black;">
    <?php
    include ('alert.php');
    include ('filter.php'); ?>
    
    <div class="view-toggle">
        <button class="toggle-btn active" onclick="toggleView('grid')" id="gridBtn">
            <i class="fas fa-th-large"></i> Grid View
        </button>
        <button class="toggle-btn" onclick="toggleView('list')" id="listBtn">
            <i class="fas fa-list"></i> List View
        </button>
    </div>
    
    <!-- Grid View -->
    <div class="room-card-container" id="gridView">
        <?php 
        mysqli_data_seek($result, 0); // Reset result pointer
        while($row=mysqli_fetch_assoc($result)){
            $statusClass = strtolower($row['room_status']);
            $rimg="SELECT * FROM room_gallery WHERE room_id={$row['room_id']} LIMIT 1";
            $rimg_result=mysqli_query($conn,$rimg);     
            $rimg_row=mysqli_fetch_assoc($rimg_result);
            $rimg_count=mysqli_num_rows($rimg_result);
            // Format price display
            if($row['discounted_price'] > 0) {
                $priceDisplay = "₱{$row['discounted_price']}";
                $discountDisplay = "<span class='discount'>{$row['discount_percentage']}% Off</span>";
            } else {
                $priceDisplay = "₱{$row['price']}";
                $discountDisplay = "";
            }
            
            // Check if room image exists
            $roomImage = "../../resources/assets/room_images/{$rimg_row['room_img']}"; // Assuming images are stored with room_id as filename
            $defaultImage = "../../assets/images/room-placeholder.jpg"; // A default placeholder image
            
            $imagePath = file_exists($roomImage) ? $roomImage : $defaultImage;
            $hasImage = file_exists($roomImage) || file_exists($defaultImage);
        ?>
        <div class="room-card <?php echo $statusClass; ?>">
            <div class="room-image">
                <?php if($hasImage): ?>
                    <img src="<?php echo $imagePath; ?>" alt="<?php echo $row['room_code']; ?>">
                <?php else: ?>
                    <div class="no-image">
                        <div class="text-center">
                            <i class="fas fa-bed"></i><br>
                            No Image Available
                        </div>
                    </div>
                <?php endif; ?>
                <div class="status-indicator"><?php echo $row['room_status']; ?></div>
            </div>
            <div class="room-header">
                <h3 class="room-code"><?php echo $row['room_code']; ?></h3>
            </div>
            <div class="room-content">
                <div class="price-container">
                    <div class="price-label">Price</div>
                    <div class="price"><?php echo $priceDisplay; ?> <?php echo $discountDisplay; ?></div>
                </div>
            </div>
            <div class="room-actions">
                <a href='edit.php?id=<?php echo $row['room_id']; ?>' class='btn btn-primary'>
                    <i class='fas fa-edit'></i> Edit
                </a>
                <a href='delete.php?id=<?php echo $row['room_id']; ?>&index_click=true' class='btn btn-danger'>
                    <i class='fas fa-trash'></i> Delete
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
    
    <!-- List View (Table) -->
    <div class="table-responsive" id="listView" style="display: none;">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Room Code</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                mysqli_data_seek($result, 0); // Reset result pointer
                while($row=mysqli_fetch_assoc($result)){
                    $statusClass = strtolower($row['room_status']);
                    
                    // Format price display
                    if($row['discounted_price'] > 0) {
                        $discount = "<span class='price-tag'>₱{$row['discounted_price']}</span> <span class='discount-text'>({$row['discount_percentage']}% Off)</span>";
                    } else {
                        $discount = "<span class='price-tag'>₱{$row['price']}</span>";
                    }
                    
                    // Check if room image exists
                    $roomImage = "../../uploads/rooms/{$row['room_id']}.jpg";
                    $defaultImage = "../../assets/images/room-placeholder.jpg";
                    
                    $imagePath = file_exists($roomImage) ? $roomImage : $defaultImage;
                    $hasImage = file_exists($roomImage) || file_exists($defaultImage);
                ?>
                <tr>
                    <td>
                        <?php if($hasImage): ?>
                            <img src="<?php echo $imagePath; ?>" alt="<?php echo $row['room_code']; ?>" class="table-img">
                        <?php else: ?>
                            <div class="no-table-img">
                                <i class="fas fa-bed"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><span class="room-code"><?php echo $row['room_code']; ?></span></td>
                    <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $row['room_status']; ?></span></td>
                    <td><?php echo $discount; ?></td>
                    <td class="action-buttons">
                        <a href='edit.php?id=<?php echo $row['room_id']; ?>' class='btn btn-primary'><i class='fas fa-edit'></i></a>
                        <a href='delete.php?id=<?php echo $row['room_id']; ?>&index_click=true' class='btn btn-danger'><i class='fas fa-trash'></i></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleView(viewType) {
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const gridBtn = document.getElementById('gridBtn');
    const listBtn = document.getElementById('listBtn');
    
    if (viewType === 'grid') {
        gridView.style.display = 'grid';
        listView.style.display = 'none';
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
        // Save preference
        localStorage.setItem('roomViewPreference', 'grid');
    } else {
        gridView.style.display = 'none';
        listView.style.display = 'block';
        gridBtn.classList.remove('active');
        listBtn.classList.add('active');
        // Save preference
        localStorage.setItem('roomViewPreference', 'list');
    }
}

// Check saved preference on load
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('roomViewPreference');
    if (savedView === 'list') {
        toggleView('list');
    }
});

</script>