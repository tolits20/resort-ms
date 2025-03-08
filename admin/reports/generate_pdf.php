<?php
require('../../resources/fpdf186/fpdf.php');

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');
$action = isset($_POST['action']) ? $_POST['action'] : 'download';

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
    http_response_code(400);
    echo "Invalid date format";
    exit;
}

// Fetch the data using the same logic as get_booking_data.php
include('../../resources/database/config.php');

try {
    // Create a new PDF instance
    $pdf = new FPDF();
    $pdf->AliasNbPages(); // For page numbers with {nb}
    $pdf->SetTitle('Booking Report ' . $start_date . ' to ' . $end_date);
    
    // Add a page
    $pdf->AddPage();
    
    // Set font
    $pdf->SetFont('Arial', 'B', 20);
    
    // Add header
    $pdf->Cell(0, 15, 'Booking Report', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Period: ' . date('F j, Y', strtotime($start_date)) . ' to ' . date('F j, Y', strtotime($end_date)), 0, 1, 'C');
    $pdf->Ln(5);
    
    // Add company logo if available
    // $pdf->Image('path/to/logo.png', 10, 10, 30);
    
    // Line separator
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(5);
    
    // Adjust end_date to include the full day
    $end_date_inclusive = date('Y-m-d', strtotime($end_date . ' +1 day'));

    // Fetch summary data
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
    
    // Add summary section
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Summary', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    
    // Summary table
    // Set fill colors for summary table
    $pdf->SetFillColor(240, 240, 240);
    
    $pdf->Cell(95, 10, 'Total Bookings:', 1, 0, 'L', true);
    $pdf->Cell(95, 10, number_format($total_bookings), 1, 1, 'R');
    
    $pdf->Cell(95, 10, 'Total Earnings:', 1, 0, 'L', true);
    $pdf->Cell(95, 10, 'Php' . number_format($total_earnings, 2), 1, 1, 'R');
    
    $pdf->Cell(95, 10, 'Total Discounts:', 1, 0, 'L', true);
    $pdf->Cell(95, 10, 'Php' . number_format($total_discounts, 2), 1, 1, 'R');
    
    $pdf->Cell(95, 10, 'Pending Payments:', 1, 0, 'L', true);
    $pdf->Cell(95, 10, 'Php' . number_format($pending_payments, 2), 1, 1, 'R');
    
    $pdf->Ln(10);
    
    // Get monthly booking data for the detailed table
    $monthly_sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as bookings,
                    (SELECT COUNT(*) FROM booking b2 WHERE DATE_FORMAT(b2.created_at, '%Y-%m') = DATE_FORMAT(b.created_at, '%Y-%m') AND b2.book_status = 'Check-in') as checked_in,
                    (SELECT COUNT(*) FROM booking b2 WHERE DATE_FORMAT(b2.created_at, '%Y-%m') = DATE_FORMAT(b.created_at, '%Y-%m') AND b2.book_status = 'Check-out') as checked_out,
                    (SELECT COUNT(*) FROM booking b2 WHERE DATE_FORMAT(b2.created_at, '%Y-%m') = DATE_FORMAT(b.created_at, '%Y-%m') AND b2.book_status = 'Cancel') as cancelled
                  FROM booking b
                  WHERE created_at BETWEEN ? AND ?
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                  ORDER BY month";
    
    $stmt = $conn->prepare($monthly_sql);
    $stmt->bind_param('ss', $start_date, $end_date_inclusive);
    $stmt->execute();
    $result = $stmt->get_result();
    $monthly_data = $result->fetch_all(MYSQLI_ASSOC);
    
    // Add monthly breakdown table if data exists
    if (count($monthly_data) > 0) {
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Monthly Breakdown', 0, 1);
        $pdf->SetFont('Arial', 'B', 11);
        
        // Table header - blue header in FPDF
        $pdf->SetFillColor(52, 152, 219); // Blue header
        $pdf->SetTextColor(255, 255, 255); // White text
        $pdf->Cell(40, 10, 'Month', 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Total Bookings', 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Checked In', 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Checked Out', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Cancelled', 1, 1, 'C', true);
        
        // Table data
        $pdf->SetFillColor(245, 245, 245); // Light gray for alternating rows
        $pdf->SetTextColor(0, 0, 0); // Back to black text
        $pdf->SetFont('Arial', '', 10);
        
        $fill = false;
        foreach ($monthly_data as $row) {
            $month_name = date('F Y', strtotime($row['month'] . '-01'));
            
            $pdf->Cell(40, 8, $month_name, 1, 0, 'L', $fill);
            $pdf->Cell(40, 8, $row['bookings'], 1, 0, 'C', $fill);
            $pdf->Cell(40, 8, $row['checked_in'], 1, 0, 'C', $fill);
            $pdf->Cell(40, 8, $row['checked_out'], 1, 0, 'C', $fill);
            $pdf->Cell(30, 8, $row['cancelled'], 1, 1, 'C', $fill);
            
            $fill = !$fill; // Toggle fill for next row
        }
    }
    
    $pdf->Ln(10);
    
    // Get room type distribution data
    $room_sql = "SELECT 
                r.room_type, 
                COUNT(b.book_id) as booking_count,
                SUM(p.amount) as revenue
              FROM booking b
              JOIN room r ON b.room_id = r.room_id
              LEFT JOIN payment p ON b.book_id = p.book_id AND p.payment_status = 'paid'
              WHERE b.created_at BETWEEN ? AND ?
              GROUP BY r.room_type
              ORDER BY booking_count DESC";
    
    $stmt = $conn->prepare($room_sql);
    $stmt->bind_param('ss', $start_date, $end_date_inclusive);
    $stmt->execute();
    $result = $stmt->get_result();
    $room_data = $result->fetch_all(MYSQLI_ASSOC);
    
    // Add room distribution section if data exists
    if (count($room_data) > 0) {
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Room Type Distribution', 0, 1);
        $pdf->SetFont('Arial', 'B', 11);
        
        // Table header with green color
        $pdf->SetFillColor(46, 204, 113); // Green header
        $pdf->SetTextColor(255, 255, 255); // White text
        $pdf->Cell(70, 10, 'Room Type', 1, 0, 'C', true);
        $pdf->Cell(60, 10, 'Number of Bookings', 1, 0, 'C', true);
        $pdf->Cell(60, 10, 'Revenue', 1, 1, 'C', true);
        
        // Table data
        $pdf->SetFillColor(245, 245, 245); // Light gray for alternating rows
        $pdf->SetTextColor(0, 0, 0); // Back to black text
        $pdf->SetFont('Arial', '', 10);
        
        $fill = false;
        foreach ($room_data as $row) {
            $pdf->Cell(70, 8, ucwords($row['room_type']), 1, 0, 'L', $fill);
            $pdf->Cell(60, 8, $row['booking_count'], 1, 0, 'C', $fill);
            $pdf->Cell(60, 8, 'Php' . number_format($row['revenue'], 2), 1, 1, 'R', $fill);
            
            $fill = !$fill; // Toggle fill for next row
        }
    }
    
    // Add footer with timestamp and page numbers
    $pdf->SetY(-15);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 10, 'Generated on ' . date('F j, Y, g:i a') . ' - Page ' . $pdf->PageNo() . '/{nb}', 0, 0, 'C');
    
    // Output the PDF based on action
    if ($action == 'preview') {
        // For preview in browser
        $pdf->Output('I', 'booking_report.pdf');
    } else {
        // For download
        $pdf->Output('D', 'booking_report_' . $start_date . '_to_' . $end_date . '.pdf');
    }
    
} catch (Exception $e) {
    // Log error
    error_log('PDF Generation Error: ' . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo "Error generating PDF: " . $e->getMessage();
} finally {
    $conn->close();
}
?>