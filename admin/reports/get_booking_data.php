<?php
// get_booking_data.php
header('Content-Type: application/json');
include('../../resources/database/config.php');

// Validate and sanitize input
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
    echo json_encode(['error' => 'Invalid date format']);
    exit;
}

try {
    // Adjust end_date to include the full day
    $end_date_inclusive = date('Y-m-d', strtotime($end_date . ' +1 day'));

    // Get total bookings
    $booking_sql = "SELECT COUNT(*) as total_bookings FROM booking WHERE created_at BETWEEN ? AND ?";
    $stmt = $conn->prepare($booking_sql);
    $stmt->bind_param('ss', $start_date, $end_date_inclusive);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking_result = $result->fetch_assoc();
    $total_bookings = $booking_result['total_bookings'];

    // Get total earnings from payments
    $earnings_sql = "SELECT SUM(p.amount) as total_earnings FROM payment p
                     JOIN booking b ON p.book_id = b.book_id
                     WHERE b.created_at BETWEEN ? AND ? AND p.payment_status = 'paid'";
    $stmt = $conn->prepare($earnings_sql);
    $stmt->bind_param('ss', $start_date, $end_date_inclusive);
    $stmt->execute();
    $result = $stmt->get_result();
    $earnings_result = $result->fetch_assoc();
    $total_earnings = $earnings_result['total_earnings'] ?? 0;

    // Get total discounts
    $discounts_sql = "SELECT SUM(d.discount_percentage * r.price / 100) as total_discounts 
                      FROM booking b
                      JOIN room r ON b.room_id = r.room_id
                      JOIN discount d ON r.room_type = d.applicable_room
                      WHERE b.created_at BETWEEN ? AND ?
                      AND d.discount_status = 'activate'
                      AND b.check_in BETWEEN d.discount_start AND d.discount_end";
    $stmt = $conn->prepare($discounts_sql);
    $stmt->bind_param('ss', $start_date, $end_date_inclusive);
    $stmt->execute();
    $result = $stmt->get_result();
    $discounts_result = $result->fetch_assoc();
    $total_discounts = $discounts_result['total_discounts'] ?? 0;

    // Get pending payments
    $pending_sql = "SELECT SUM(p.amount) as pending_payments FROM payment p
                    JOIN booking b ON p.book_id = b.book_id
                    WHERE b.created_at BETWEEN ? AND ? AND p.payment_status = 'pending'";
    $stmt = $conn->prepare($pending_sql);
    $stmt->bind_param('ss', $start_date, $end_date_inclusive);
    $stmt->execute();
    $result = $stmt->get_result();
    $pending_result = $result->fetch_assoc();
    $pending_payments = $pending_result['pending_payments'] ?? 0;

    // Get dynamic booking data for the chart
    $chart_sql = "SELECT 
                    DATE_FORMAT(b.created_at, '%Y-%m-%d') as day,
                    COUNT(b.book_id) as bookings,
                    COALESCE(SUM(CASE WHEN p.payment_status = 'paid' THEN p.amount ELSE 0 END), 0) as earnings,
                    COALESCE(SUM(CASE WHEN p.payment_status = 'pending' THEN p.amount ELSE 0 END), 0) as pending,
                    COALESCE(SUM(d.discount_percentage * r.price / 100), 0) as discounts
                  FROM booking b
                  LEFT JOIN payment p ON b.book_id = p.book_id
                  LEFT JOIN room r ON b.room_id = r.room_id
                  LEFT JOIN discount d ON r.room_type = d.applicable_room
                    AND d.discount_status = 'activate'
                    AND b.check_in BETWEEN d.discount_start AND d.discount_end
                  WHERE b.created_at BETWEEN ? AND ?
                  GROUP BY DATE_FORMAT(b.created_at, '%Y-%m-%d')
                  ORDER BY day";

    $stmt = $conn->prepare($chart_sql);
    $stmt->bind_param('ss', $start_date, $end_date_inclusive);
    $stmt->execute();
    $result = $stmt->get_result();
    $chart_results = $result->fetch_all(MYSQLI_ASSOC);

    // Prepare dynamic chart data
    $chart_data = [
        'labels' => [],
        'groupBy' => 'Day',
        'data' => [
            'Total Bookings' => [],
            'Total Earnings' => [],
            'Pending Payments' => [],
            'Total Discounts' => []
        ]
    ];

    foreach ($chart_results as $row) {
        $chart_data['labels'][] = $row['day'];
        $chart_data['data']['Total Bookings'][] = (int)$row['bookings'];
        $chart_data['data']['Total Earnings'][] = (float)($row['earnings'] ?? 0);
        $chart_data['data']['Pending Payments'][] = (float)($row['pending'] ?? 0);
        $chart_data['data']['Total Discounts'][] = (float)($row['discounts'] ?? 0);
    }

    // Prepare response
    $response = [
        'success' => true,
        'data' => [
            'total_bookings' => (int)$total_bookings,
            'total_earnings' => (float)$total_earnings,
            'total_discounts' => (float)$total_discounts,
            'pending_payments' => (float)$pending_payments,
            'chart_data' => $chart_data
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());

    echo json_encode([
        'success' => false,
        'error' => 'An error occurred. Please try again later.'
    ]);
} finally {
    $conn->close();
}
?>
