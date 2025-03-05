<?php
require('../../resources/fpdf186/fpdf.php');
include('../../resources/database/config.php');
include('../includes/page_authentication.php');

class PDF extends FPDF
{
    function Header()
    {
        if (file_exists('../../resources/images/logo.png')) {
            $this->Image('../../resources/images/logo.png', 10, 6, 30);
        }
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(190, 10, 'Resort Management System', 0, 1, 'C');
        
        $this->SetFont('Arial', '', 10);
        $this->Cell(190, 5, '123 Beachfront Road, Paradise Island', 0, 1, 'C');
        $this->Cell(190, 5, 'Email: info@resort.com | Phone: (123) 456-7890', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-30);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(190, 10, 'Thank you for choosing our resort!', 0, 1, 'C');
        $this->Cell(190, 10, 'For inquiries, contact us at (123) 456-7890', 0, 1, 'C');

        $this->SetFont('Arial', 'I', 8);
        $this->Cell(190, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'INVOICE', 0, 1, 'C');
$pdf->Ln(5);

$booking_id = $_GET['id'];
$sql = "SELECT * FROM summary_payment WHERE booking_id = $booking_id";
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 7, 'Invoice No: INV-' . $row['booking_id'], 0, 0, 'L');
    $pdf->Cell(90, 7, 'Invoice Date: ' . date('Y-m-d'), 0, 1, 'R');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(100, 7, 'Billed To:', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100, 7, 'Customer Name: ' . $row['NAME'], 0, 1, 'L');
    $pdf->Cell(100, 7, 'Room: ' . $row['room_code'] . ' (' . $row['room_type'] . ')', 0, 1, 'L');
    $pdf->Cell(100, 7, 'Check-in: ' . $row['check_in'], 0, 1, 'L');
    $pdf->Cell(100, 7, 'Check-out: ' . $row['check_out'], 0, 1, 'L');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Cell(130, 10, 'Description', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Price', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Total', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(130, 10, 'Room Booking - ' . $row['room_type'], 1);
    $pdf->Cell(30, 10, "PHP " . number_format($row['price'], 2), 1, 0, 'C');
    $pdf->Cell(30, 10, "PHP " . number_format($row['price'], 2), 1, 1, 'C');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(130, 10, 'Total Amount Due:', 1, 0, 'R');
    $pdf->Cell(60, 10, "PHP " . number_format($row['price'], 2), 1, 1, 'C');

    $pdf->Cell(130, 10, 'Amount Paid:', 1, 0, 'R');
    $pdf->Cell(60, 10, "PHP " . number_format($row['amount_paid'], 2), 1, 1, 'C');

    $balance = $row['price'] - $row['amount_paid'];
    $pdf->Cell(130, 10, 'Balance Due:', 1, 0, 'R', true);
    $pdf->Cell(60, 10, "PHP " . number_format($balance, 2), 1, 1, 'C', true);

    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Payment Status: ' . strtoupper($row['payment_status']), 0, 1, 'C');
} else {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'No invoice found.', 1, 1, 'C');
}

$conn->close();

$download = isset($_GET['download']) ? $_GET['download'] : 0;
$filename = isset($row['NAME']) ? 'Invoice_' . $row['NAME'] . '.pdf' : 'Invoice.pdf';

if ($download) {
    $pdf->Output('D', $filename);
} else {
    $pdf->Output();
}
?>
