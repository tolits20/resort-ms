<?php
include('../../resources/database/config.php');
include("../../admin/includes/system_update.php"); 
include("../includes/template.php");

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

// Fetch all available rooms
$roomsQuery = "SELECT 
    r.room_id,
    r.room_code,
    r.room_type,
    r.price,
    r.status,
    d.discount_percentage,
    ROUND(r.price - (r.price * (d.discount_percentage / 100)), 2) AS discounted_price,
    (SELECT room_img FROM room_gallery WHERE room_id = r.room_id LIMIT 1) as main_image
FROM room r
LEFT JOIN discount d
    ON r.room_type = d.applicable_room 
    AND d.discount_status = 'activate'
    AND NOW() BETWEEN d.discount_start AND d.discount_end
ORDER BY r.room_code";

$roomsResult = mysqli_query($conn, $roomsQuery);
?>

<!-- Add this inside the #content div from template.php -->
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Available Rooms</h4>
                    <a href="index.php" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Back to Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <?php while ($room = mysqli_fetch_assoc($roomsResult)): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <?php if ($room['main_image']): ?>
                        <img src="../../resources/assets/room_images/<?php echo $room['main_image']; ?>" 
                             class="card-img-top room-image" alt="<?php echo $room['room_code']; ?>">
                    <?php else: ?>
                        <div class="card-img-top room-image-placeholder d-flex align-items-center justify-content-center bg-light">
                            <i class="fas fa-bed fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($room['room_code']); ?></h5>
                        <p class="card-text">
                            <span class="badge bg-info"><?php echo htmlspecialchars($room['room_type']); ?></span>
                        </p>
                        
                        <div class="price-section mb-3">
                            <?php if ($room['discount_percentage'] > 0): ?>
                                <p class="mb-0">
                                    <span class="text-decoration-line-through text-muted">
                                        ₱<?php echo number_format($room['price'], 2); ?>
                                    </span>
                                    <span class="h5 text-primary ms-2">
                                        ₱<?php echo number_format($room['discounted_price'], 2); ?>
                                    </span>
                                    <span class="badge bg-success ms-2">
                                        <?php echo $room['discount_percentage']; ?>% OFF
                                    </span>
                                </p>
                            <?php else: ?>
                                <p class="h5 text-primary mb-0">
                                    ₱<?php echo number_format($room['price'], 2); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="status-section mb-3">
                            <?php
                            $statusClass = $room['status'] === 'Available' ? 'success' : 'danger';
                            ?>
                            <span class="badge bg-<?php echo $statusClass; ?>">
                                <?php echo $room['status']; ?>
                            </span>
                        </div>

                        <div class="d-grid">
                            <?php if ($room['status'] === 'Available'): ?>
                                <a href="booking_form.php?room_id=<?php echo $room['room_id']; ?>" 
                                   class="btn btn-primary">
                                    <i class="fas fa-calendar-plus me-2"></i>Book Now
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-lock me-2"></i>Not Available
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.room-image, .room-image-placeholder {
    height: 200px;
    object-fit: cover;
    border-bottom: 1px solid #eee;
}

.room-image-placeholder {
    background-color: #f8f9fa;
}

.price-section {
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    padding: 10px 0;
}

.status-section {
    text-align: right;
}

.btn-primary {
    background-color: #3498db;
    border-color: #3498db;
}

.btn-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
}

@media (max-width: 768px) {
    .col-md-4 {
        margin-bottom: 20px;
    }
}
</style>

<?php
// Close the content div and add the closing body and html tags
echo "</div></body></html>";
?>