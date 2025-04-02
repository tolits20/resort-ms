<?php
require('../../resources/fpdf186/fpdf.php');

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');
$action = isset($_POST['action']) ? $_POST['action'] : 'download';
// Parameter to determine which view to show
$view_type = isset($_POST['view_type']) ? $_POST['view_type'] : 'buyer'; // Options: 'buyer' or 'accounts'

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
    http_response_code(400);
    echo "Invalid date format";
    exit;
}

// Include database configuration
include('../../resources/database/config.php');

try {
    // Create a new PDF instance with custom header/footer
    class PDF extends FPDF {
        // Page header
        function Header() {
            global $view_type, $start_date, $end_date;
            
            // Logo
            
            // Set font
            $this->SetFont('Arial', 'B', 20);
            
            // Title based on view type
            $title_suffix = ($view_type == 'buyer') ? 'Buyer Report' : 'Accounts Payable Report';
            $this->Cell(0, 15, $title_suffix, 0, 1, 'C');
            
            // Date range subtitle
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, 'Period: ' . date('F j, Y', strtotime($start_date)) . ' to ' . date('F j, Y', strtotime($end_date)), 0, 1, 'C');
            
            // Line separator
            $this->SetDrawColor(200, 200, 200);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(5);
        }
        
        // Page footer
        function Footer() {
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial', 'I', 8);
            // Page number and generation timestamp
            $this->Cell(0, 10, 'Generated on ' . date('F j, Y, g:i a') . ' - Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
    }
    
    // Create PDF instance
    $pdf = new PDF();
    $pdf->AliasNbPages(); // For page numbers with {nb}
    
    // Set title based on view type
    $title_suffix = ($view_type == 'buyer') ? 'Buyer Report' : 'Accounts Payable Report';
    $pdf->SetTitle($title_suffix . ' ' . $start_date . ' to ' . $end_date);
    
    // Add a page
    $pdf->AddPage();
    
    // Adjust end_date to include the full day
    $end_date_inclusive = date('Y-m-d', strtotime($end_date . ' +1 day'));

    // Common data for both views
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

    // ===== BUYER VIEW =====
    if ($view_type == 'buyer') {
        // Buyer Summary section with buyer-focused metrics
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Buyer Summary', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        
        // Summary table
        $pdf->SetFillColor(240, 240, 240);
        
        $pdf->Cell(95, 10, 'Total Bookings:', 1, 0, 'L', true);
        $pdf->Cell(95, 10, number_format($total_bookings), 1, 1, 'R');
        
        $pdf->Cell(95, 10, 'Total Revenue:', 1, 0, 'L', true);
        $pdf->Cell(95, 10, 'Php' . number_format($total_earnings, 2), 1, 1, 'R');
        
        // Get average stay duration
        $avg_stay_sql = "SELECT AVG(DATEDIFF(check_out, check_in)) as avg_stay 
                         FROM booking 
                         WHERE created_at BETWEEN ? AND ?";
        $stmt = $conn->prepare($avg_stay_sql);
        $stmt->bind_param('ss', $start_date, $end_date_inclusive);
        $stmt->execute();
        $result = $stmt->get_result();
        $avg_stay_result = $result->fetch_assoc();
        $avg_stay = round($avg_stay_result['avg_stay'], 1);
        
        $pdf->Cell(95, 10, 'Average Stay Duration:', 1, 0, 'L', true);
        $pdf->Cell(95, 10, $avg_stay . ' days', 1, 1, 'R');
        
        // Get booking completion rate
        $completion_sql = "SELECT 
                          COUNT(CASE WHEN book_status = 'Check-in' THEN 1 END) as completed_bookings,
                          COUNT(*) as total,
                          ROUND(COUNT(CASE WHEN book_status = 'Check-in' THEN 1 END) / COUNT(*) * 100, 1) as completion_rate
                        FROM booking
                        WHERE created_at BETWEEN ? AND ?";
        $stmt = $conn->prepare($completion_sql);
        $stmt->bind_param('ss', $start_date, $end_date_inclusive);
        $stmt->execute();
        $result = $stmt->get_result();
        $completion_data = $result->fetch_assoc();
        
        $pdf->Cell(95, 10, 'Booking Completion Rate:', 1, 0, 'L', true);
        $pdf->Cell(95, 10, $completion_data['completion_rate'] . '%', 1, 1, 'R');
        
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
            $pdf->Cell(0, 10, 'Room Type Performance', 0, 1);
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
            $total_room_revenue = 0;
            
            foreach ($room_data as $row) {
                $pdf->Cell(70, 8, ucwords($row['room_type']), 1, 0, 'L', $fill);
                $pdf->Cell(60, 8, $row['booking_count'], 1, 0, 'C', $fill);
                $pdf->Cell(60, 8, 'Php' . number_format($row['revenue'], 2), 1, 1, 'R', $fill);
                
                $total_room_revenue += $row['revenue'];
                $fill = !$fill; // Toggle fill for next row
            }
            
            // Add a total row
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(130, 8, 'Total', 1, 0, 'R', true);
            $pdf->Cell(60, 8, 'Php' . number_format($total_room_revenue, 2), 1, 1, 'R', true);
        }
        
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
            $pdf->Cell(0, 10, 'Monthly Booking Trends', 0, 1);
            $pdf->SetFont('Arial', 'B', 11);
            
            // Table header - blue header
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
            $total_monthly_bookings = 0;
            $total_checked_in = 0;
            $total_checked_out = 0;
            $total_cancelled = 0;
            
            foreach ($monthly_data as $row) {
                $month_name = date('F Y', strtotime($row['month'] . '-01'));
                
                $pdf->Cell(40, 8, $month_name, 1, 0, 'L', $fill);
                $pdf->Cell(40, 8, $row['bookings'], 1, 0, 'C', $fill);
                $pdf->Cell(40, 8, $row['checked_in'], 1, 0, 'C', $fill);
                $pdf->Cell(40, 8, $row['checked_out'], 1, 0, 'C', $fill);
                $pdf->Cell(30, 8, $row['cancelled'], 1, 1, 'C', $fill);
                
                $total_monthly_bookings += $row['bookings'];
                $total_checked_in += $row['checked_in'];
                $total_checked_out += $row['checked_out'];
                $total_cancelled += $row['cancelled'];
                
                $fill = !$fill; // Toggle fill for next row
            }
            
            // Add a total row
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(40, 8, 'Total', 1, 0, 'R', true);
            $pdf->Cell(40, 8, $total_monthly_bookings, 1, 0, 'C', true);
            $pdf->Cell(40, 8, $total_checked_in, 1, 0, 'C', true);
            $pdf->Cell(40, 8, $total_checked_out, 1, 0, 'C', true);
            $pdf->Cell(30, 8, $total_cancelled, 1, 1, 'C', true);
        }
        
        $pdf->Ln(10);
        
        // Guest demographics - another section relevant for buyers
        $demographics_sql = "SELECT 
                            COUNT(DISTINCT guest_id) as unique_guests,
                            COUNT(CASE WHEN book_status = 'Check-in' THEN 1 END) as completed_bookings,
                            ROUND(COUNT(CASE WHEN book_status = 'Check-in' THEN 1 END) / COUNT(*) * 100, 1) as completion_rate
                          FROM booking
                          WHERE created_at BETWEEN ? AND ?";
        $stmt = $conn->prepare($demographics_sql);
        $stmt->bind_param('ss', $start_date, $end_date_inclusive);
        $stmt->execute();
        $result = $stmt->get_result();
        $demographics = $result->fetch_assoc();
        
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Guest Insights', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(95, 10, 'Unique Guests:', 1, 0, 'L', true);
        $pdf->Cell(95, 10, number_format($demographics['unique_guests']), 1, 1, 'R');
        
        $pdf->Cell(95, 10, 'Completed Bookings:', 1, 0, 'L', true);
        $pdf->Cell(95, 10, number_format($demographics['completed_bookings']), 1, 1, 'R');
        
        $pdf->Cell(95, 10, 'Booking Completion Rate:', 1, 0, 'L', true);
        $pdf->Cell(95, 10, $demographics['completion_rate'] . '%', 1, 1, 'R');
        
        // Add new section: Top Spending Guests
        $top_guests_sql = "SELECT 
                          g.fname || ' ' || g.lname as guest_name,
                          COUNT(b.book_id) as booking_count,
                          SUM(p.amount) as total_spent
                        FROM booking b
                        JOIN guest g ON b.guest_id = g.guest_id
                        JOIN payment p ON b.book_id = p.book_id
                        WHERE b.created_at BETWEEN ? AND ? AND p.payment_status = 'paid'
                        GROUP BY g.guest_id, guest_name
                        ORDER BY total_spent DESC
                        LIMIT 5";
        $stmt = $conn->prepare($top_guests_sql);
        $stmt->bind_param('ss', $start_date, $end_date_inclusive);
        $stmt->execute();
        $result = $stmt->get_result();
        $top_guests_data = $result->fetch_all(MYSQLI_ASSOC);
        
        if (count($top_guests_data) > 0) {
            $pdf->Ln(10);
            
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Top Spending Guests', 0, 1);
            $pdf->SetFont('Arial', 'B', 11);
            
            // Table header
            $pdf->SetFillColor(155, 89, 182); // Purple header
            $pdf->SetTextColor(255, 255, 255); // White text
            $pdf->Cell(80, 10, 'Guest Name', 1, 0, 'C', true);
            $pdf->Cell(50, 10, 'Bookings', 1, 0, 'C', true);
            $pdf->Cell(60, 10, 'Total Spent', 1, 1, 'C', true);
            
            // Table data
            $pdf->SetFillColor(245, 245, 245);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 10);
            
            $fill = false;
            foreach ($top_guests_data as $row) {
                $pdf->Cell(80, 8, $row['guest_name'], 1, 0, 'L', $fill);
                $pdf->Cell(50, 8, $row['booking_count'], 1, 0, 'C', $fill);
                $pdf->Cell(60, 8, 'Php' . number_format($row['total_spent'], 2), 1, 1, 'R', $fill);
                
                $fill = !$fill;
            }
        }
    }
    // ===== ACCOUNTS PAYABLE VIEW =====
    else {
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
        
        // Financial Summary section specific to accounts view
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Financial Summary', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        
        // Financial summary table
        $pdf->SetFillColor(240, 240, 240);
        
        $pdf->Cell(95, 10, 'Total Revenue:', 1, 0, 'L', true);
        $pdf->Cell(95, 10, 'Php' . number_format($total_earnings, 2), 1, 1, 'R');
        
        $pdf->Cell(95, 10, 'Total Discounts:', 1, 0, 'L', true);
        $pdf->Cell(95, 10, 'Php' . number_format($total_discounts, 2), 1, 1, 'R');
        
        $pdf->Cell(95, 10, 'Pending Payments:', 1, 0, 'L', true);
        $pdf->Cell(95, 10, 'Php' . number_format($pending_payments, 2), 1, 1, 'R');
        
        $pdf->Cell(95, 10, 'Net Income:', 1, 0, 'L', true);
        $pdf->Cell(95, 10, 'Php' . number_format($total_earnings - $total_discounts, 2), 1, 1, 'R');
        
        // Calculate profit margin
        $profit_margin = ($total_earnings > 0) ? (($total_earnings - $total_discounts) / $total_earnings * 100) : 0;
        $pdf->Cell(95, 10, 'Profit Margin:', 1, 0, 'L', true);
        $pdf->Cell(95, 10, number_format($profit_margin, 2) . '%', 1, 1, 'R');
        
        $pdf->Ln(10);
        
        // Get payment method breakdown
        $payment_method_sql = "SELECT 
                              p.payment_method,
                              COUNT(*) as transaction_count,
                              SUM(p.amount) as total_amount
                            FROM payment p
                            JOIN booking b ON p.book_id = b.book_id
                            WHERE b.created_at BETWEEN ? AND ? AND p.payment_status = 'paid'
                            GROUP BY p.payment_method
                            ORDER BY total_amount DESC";
        $stmt = $conn->prepare($payment_method_sql);
        $stmt->bind_param('ss', $start_date, $end_date_inclusive);
        $stmt->execute();
        $result = $stmt->get_result();
        $payment_method_data = $result->fetch_all(MYSQLI_ASSOC);
        
        // Payment Method Breakdown - specific to accounts payable view
        if (count($payment_method_data) > 0) {
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Payment Method Analysis', 0, 1);
            $pdf->SetFont('Arial', 'B', 11);
            
            // Table header
            $pdf->SetFillColor(142, 68, 173); // Purple header for accounts
            $pdf->SetTextColor(255, 255, 255); // White text
            $pdf->Cell(70, 10, 'Payment Method', 1, 0, 'C', true);
            $pdf->Cell(60, 10, 'Transactions', 1, 0, 'C', true);
            $pdf->Cell(60, 10, 'Total Amount', 1, 1, 'C', true);
            
            // Table data
            $pdf->SetFillColor(245, 245, 245);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 10);
            
            $fill = false;
            $total_transactions = 0;
            $total_payment_amount = 0;
            
            foreach ($payment_method_data as $row) {
                $pdf->Cell(70, 8, ucwords($row['payment_method']), 1, 0, 'L', $fill);
                $pdf->Cell(60, 8, $row['transaction_count'], 1, 0, 'C', $fill);
                $pdf->Cell(60, 8, 'Php' . number_format($row['total_amount'], 2), 1, 1, 'R', $fill);
                
                $total_transactions += $row['transaction_count'];
                $total_payment_amount += $row['total_amount'];
                
                $fill = !$fill;
            }
            
            // Add a total row
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 8, 'Total', 1, 0, 'R', true);
            $pdf->Cell(60, 8, $total_transactions, 1, 0, 'C', true);
            $pdf->Cell(60, 8, 'Php' . number_format($total_payment_amount, 2), 1, 1, 'R', true);
        }
        
        $pdf->Ln(10);
        
        // Get daily payment summary for cash flow analysis
        $daily_payment_sql = "SELECT 
                             DATE(p.payment_date) as payment_day,
                             COUNT(*) as transactions,
                             SUM(p.amount) as daily_total
                           FROM payment p
                           JOIN booking b ON p.book_id = b.book_id
                           WHERE b.created_at BETWEEN ? AND ? AND p.payment_status = 'paid'
                           GROUP BY DATE(p.payment_date)
                           ORDER BY payment_day";
        $stmt = $conn->prepare($daily_payment_sql);
        $stmt->bind_param('ss', $start_date, $end_date_inclusive);
        $stmt->execute();
        $result = $stmt->get_result();
        $daily_payment_data = $result->fetch_all(MYSQLI_ASSOC);
        
        // Daily Payment Cash Flow - specific to accounts payable
        if (count($daily_payment_data) > 0) {
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Daily Cash Flow', 0, 1);
            $pdf->SetFont('Arial', 'B', 11);
            
            // Table header
            $pdf->SetFillColor(41, 128, 185); // Different blue for accounts
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(60, 10, 'Date', 1, 0, 'C', true);
            $pdf->Cell(60, 10, 'Transactions', 1, 0, 'C', true);
            $pdf->Cell(70, 10, 'Daily Revenue', 1, 1, 'C', true);
            
            // Table data
            $pdf->SetFillColor(245, 245, 245);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 10);
            
            $fill = false;
            $total_daily_transactions = 0;
            $total_daily_revenue = 0;
            
            foreach ($daily_payment_data as $row) {
                $pdf->Cell(60, 8, date('F j, Y', strtotime($row['payment_day'])), 1, 0, 'L', $fill);
                $pdf->Cell(60, 8, $row['transactions'], 1, 0, 'C', $fill);
                $pdf->Cell(70, 8, 'Php' . number_format($row['daily_total'], 2), 1, 1, 'R', $fill);
                
                $total_daily_transactions += $row['transactions'];
                $total_daily_revenue += $row['daily_total'];
                
                $fill = !$fill;
            }
            
            // Add a total row
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(60, 8, 'Total', 1, 0, 'R', true);
            $pdf->Cell(60, 8, $total_daily_transactions, 1, 0, 'C', true);
            $pdf->Cell(70, 8, 'Php' . number_format($total_daily_revenue, 2), 1, 1, 'R', true);
        }
        
        $pdf->Ln(10);
        
        // Outstanding invoices section - only for accounts payable
        $invoices_sql = "SELECT 
                        b.book_id,
                        g.first_name || ' ' || g.last_name as guest_name,
                        b.check_in,
                        b.check_out,
                        p.amount as amount_due,
                        p.payment_date
                      FROM booking b
                      JOIN guest g ON b.guest_id = g.guest_id
                      JOIN payment p ON b.book_id = p.book_id
                      WHERE b.created_at BETWEEN ? AND ? 
                      AND p.payment_status = 'pending'
                      ORDER BY p.payment_date";
        $stmt = $conn->prepare($invoices_sql);
        $stmt->bind_param('ss', $start_date, $end_date_inclusive);
        $stmt->execute();
        $result = $stmt->get_result();
        $invoices_data = $result->fetch_all(MYSQLI_ASSOC);
        
        if (count($invoices_data) > 0) {
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Outstanding Invoices', 0, 1);
            $pdf->SetFont('Arial', 'B', 11);
            
            // Table header
            $pdf->SetFillColor(231, 76, 60); // Red header for unpaid invoices
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(15, 10, 'ID', 1, 0, 'C', true);
            $pdf->Cell(60, 10, 'Guest Name', 1, 0, 'C', true);
            $pdf->Cell(35, 10, 'Check In', 1, 0, 'C', true);
            $pdf->Cell(35, 10, 'Check Out', 1, 0, 'C', true);
            $pdf->Cell(45, 10, 'Amount Due', 1, 1, 'C', true);
            
            // Table data
            $pdf->SetFillColor(245, 245, 245);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 10);
            
            $fill = false;
            $total_amount_due = 0;
            
            foreach ($invoices_data as $row) {
                $pdf->Cell(15, 8, $row['book_id'], 1, 0, 'C', $fill);
                $pdf->Cell(60, 8, $row['guest_name'], 1, 0, 'L', $fill);
                $pdf->Cell(35, 8, date('M j, Y', strtotime($row['check_in'])), 1, 0, 'C', $fill);
                $pdf->Cell(35, 8, date('M j, Y', strtotime($row['check_out'])), 1, 0, 'C', $fill);
                $pdf->Cell(45, 8, 'Php' . number_format($row['amount_due'], 2), 1, 1, 'R', $fill);
                
                $total_amount_due += $row['amount_due'];
                $fill = !$fill;
            }
            
            // Add a total row
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(145, 8, 'Total Outstanding', 1, 0, 'R', true);
            $pdf->Cell(45, 8, 'Php' . number_format($total_amount_due, 2), 1, 1, 'R', true);
        }
        // Add aging analysis section - accounts payable specific
        $aging_sql = "SELECT 
                     CASE 
                        WHEN JULIANDAY('now') - JULIANDAY(p.payment_date) <= 30 THEN '0-30 days'
                        WHEN JULIANDAY('now') - JULIANDAY(p.payment_date) <= 60 THEN '31-60 days'
                        WHEN JULIANDAY('now') - JULIANDAY(p.payment_date) <= 90 THEN '61-90 days'
                        ELSE 'Over 90 days'
                     END as aging_bucket,
                     COUNT(*) as invoice_count,
                     SUM(p.amount) as total_amount
                   FROM payment p
                   JOIN booking b ON p.book_id = b.book_id
                   WHERE b.created_at BETWEEN ? AND ? AND p.payment_status = 'pending'
                   GROUP BY aging_bucket
                   ORDER BY aging_bucket";
        $stmt = $conn->prepare($aging_sql);
        $stmt->bind_param('ss', $start_date, $end_date_inclusive);
        $stmt->execute();
        $result = $stmt->get_result();
        $aging_data = $result->fetch_all(MYSQLI_ASSOC);
        
        if (count($aging_data) > 0) {
            $pdf->Ln(10);
            
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Accounts Receivable Aging', 0, 1);
            $pdf->SetFont('Arial', 'B', 11);
            
            // Table header
            $pdf->SetFillColor(230, 126, 34); // Orange header
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(70, 10, 'Age', 1, 0, 'C', true);
            $pdf->Cell(60, 10, 'Invoice Count', 1, 0, 'C', true);
            $pdf->Cell(60, 10, 'Total Amount', 1, 1, 'C', true);
            
            // Table data
            $pdf->SetFillColor(245, 245, 245);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 10);
            
            $fill = false;
            $total_aging_invoices = 0;
            $total_aging_amount = 0;
            
            foreach ($aging_data as $row) {
                $pdf->Cell(70, 8, $row['aging_bucket'], 1, 0, 'L', $fill);
                $pdf->Cell(60, 8, $row['invoice_count'], 1, 0, 'C', $fill);
                $pdf->Cell(60, 8, 'Php' . number_format($row['total_amount'], 2), 1, 1, 'R', $fill);
                
                $total_aging_invoices += $row['invoice_count'];
                $total_aging_amount += $row['total_amount'];
                
                $fill = !$fill;
            }
            
            // Add a total row
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 8, 'Total', 1, 0, 'R', true);
            $pdf->Cell(60, 8, $total_aging_invoices, 1, 0, 'C', true);
            $pdf->Cell(60, 8, 'Php' . number_format($total_aging_amount, 2), 1, 1, 'R', true);
        }
    }
    
    // Final notes section for both views
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 5, 'Note: This report was generated automatically by the system. Please verify all data for accuracy.', 0, 1);
    $pdf->Cell(0, 5, 'For inquiries regarding this report, please contact the system administrator.', 0, 1);
    
    // Output the PDF as specified by the action parameter
    if ($action == 'view') {
        // Display the PDF in the browser
        $pdf->Output('I', $view_type . '_report_' . $start_date . '_to_' . $end_date . '.pdf');
    } else {
        // Download the PDF
        $pdf->Output('D', $view_type . '_report_' . $start_date . '_to_' . $end_date . '.pdf');
    }
}
catch (Exception $e) {
    // Handle any errors that occur during PDF generation
    http_response_code(500);
    echo "Error generating PDF report: " . $e->getMessage();
}
finally {
    // Close the database connection if it exists
    if (isset($conn) && $conn) {
        $conn->close();
    }
}
?>