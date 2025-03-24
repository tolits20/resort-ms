<?php
include('../../resources/database/config.php');

// Fetch most selected rooms
$mostSelectedRoomsQuery = "SELECT room_type, COUNT(*) as count FROM bookings GROUP BY room_type ORDER BY count DESC LIMIT 4";
$mostSelectedRoomsResult = mysqli_query($conn, $mostSelectedRoomsQuery);
$mostSelectedRooms = [];
while ($row = mysqli_fetch_assoc($mostSelectedRoomsResult)) {
    $mostSelectedRooms[] = $row;
}

// Fetch room booking distribution
$roomBookingDistributionQuery = "SELECT room_category, COUNT(*) as count FROM bookings GROUP BY room_category";
$roomBookingDistributionResult = mysqli_query($conn, $roomBookingDistributionQuery);
$roomBookingDistribution = [];
while ($row = mysqli_fetch_assoc($roomBookingDistributionResult)) {
    $roomBookingDistribution[] = $row;
}

// Combine data into a single array
$data = [
    'mostSelectedRooms' => $mostSelectedRooms,
    'roomBookingDistribution' => $roomBookingDistribution
];

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>