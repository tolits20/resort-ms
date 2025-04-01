<?php

use Dom\Mysql;

 date_default_timezone_set('Asia/Manila'); // Change to your timezone if needed

//automatic email sending
try{
    $now = date("Y-m-d H:i:s");

// 1. Send reminder email 24 hours before check-in
    $sql = "SELECT 
        'user' AS identifier,
        b.book_id,
        b.created_at,
        b.check_in,
        b.check_out,
        b.book_status,
        b.reminder_sent,
        u.fname AS fname, 
        u.lname AS lname, 
        a.username AS email, 
        r.room_code
    FROM booking b
    INNER JOIN user u ON b.account_id = u.account_id
    INNER JOIN room r ON b.room_id = r.room_id
    INNER JOIN account a ON b.account_id = a.account_id
    WHERE b.book_status = 'confirmed' 
    AND b.reminder_sent IS NULL 
    AND b.check_in BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 24 HOUR)

    UNION ALL

    SELECT 
        'guest' AS identifier,
        b.book_id,
        b.created_at,
        b.check_in,
        b.check_out,
        b.book_status,
        b.reminder_sent,
        g.fname AS fname, 
        g.lname AS lname, 
        g.email AS email, 
        r.room_code
    FROM booking b
    INNER JOIN guest g ON b.guest_id = g.guest_id
    INNER JOIN room r ON b.room_id = r.room_id
    WHERE b.book_status = 'confirmed' 
    AND b.reminder_sent IS NULL 
    AND b.check_in BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 24 HOUR);
    ";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $to = $row['email'];
    $subject = "Booking Confirmation - Your Stay at SAMPLE Resort";
    
    $name=$row['fname']." ".$row['lname'];
    $bookingDate = $row['created_at']; 
    $checkInDate = $row['check_in']; 
    $checkout = $row['check_out'];
    $roomNumber = $row['room_code'];
    
    $message = "
    <html>
    <head>
        <title>Booking Confirmation</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .email-container { padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
            h2 { color: #2c3e50; }
            p { color: #333; }
            .footer { font-size: 12px; color: #666; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <h2>Dear $name,</h2>
            <p>Thank you for booking with <strong>Sample Resort</strong>.</p>
            <p>Your reservation details are as follows:</p>
            <ul>
                <li><strong>Booking Date:</strong> $bookingDate</li>
                <li><strong>Check-in Date:</strong> $checkInDate</li>
                <li><strong>Check-out Date:</strong> $checkout</li>
                <li><strong>Room Number:</strong> $roomNumber</li>
            </ul>
            <p>If you have any questions, feel free to contact us.</p>
            <p>We look forward to welcoming you!</p>
            <p class='footer'>Best Regards, <br>Sample Resort Team</p>
        </div>
    </body>
    </html>";
    
    // Email Headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: Sample Resort <no-reply@sampleresort.com>" . "\r\n";
    $headers .= "Reply-To: support@sampleresort.com" . "\r\n";
    
    // Send Email
    if (mail($to, $subject, $message, $headers)) {
        $update = "UPDATE booking SET reminder_sent=NOW() WHERE book_id=" . $row['book_id'];
        mysqli_query($conn, $update);
      
    } else {
        echo "Error: Email not sent!";
    }
    
   
}
}catch(Exception $e){
    echo 'Error: ' . $e->getMessage();
}




// 2. Send completion email after checkout
try{
    mysqli_begin_transaction($conn);
        $sql2 = "SELECT 
        'user' AS identifier,
        b.book_id,
        b.check_in,
        b.check_out,
        b.book_status,
        b.reminder_sent,
        u.fname AS fname, 
        u.lname AS lname, 
        a.username AS email, 
        r.room_code
    FROM booking b
    INNER JOIN user u ON b.account_id = u.account_id
    INNER JOIN room r ON b.room_id = r.room_id
    INNER JOIN account a ON b.account_id = a.account_id
    WHERE b.book_status = 'completed' 
    AND b.completion_sent IS NULL 
    AND b.check_out <= NOW()

    UNION ALL

    SELECT 
        'guest' AS identifier,
        b.book_id,
        b.check_in,
        b.check_out,
        b.book_status,
        b.reminder_sent,
        g.fname AS fname, 
        g.lname AS lname, 
        g.email AS email, 
        r.room_code
    FROM booking b
    INNER JOIN guest g ON b.guest_id = g.guest_id
    INNER JOIN room r ON b.room_id = r.room_id
    WHERE b.book_status = 'completed' 
    AND b.completion_sent IS NULL 
    AND b.check_out<=NOW();
    ";  
$result2 = mysqli_query($conn, $sql2);

while ($row = mysqli_fetch_assoc($result2)) {
    $to = $row['email'];
    $name=$row['fname']." ".$row['lname'];
    $subject = "Booking Completed - Thank You for Staying with Us!";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: Sample Resort <noreply@sampleresort.com>" . "\r\n";

    $message = '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                padding: 20px;
                text-align: center;
            }
            .container {
                background: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                max-width: 500px;
                margin: auto;
            }
            h2 {
                color: #007bff;
            }
            p {
                font-size: 16px;
                color: #333;
            }
            .footer {
                margin-top: 20px;
                font-size: 14px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Booking Completed!</h2>
            <p>Dear <strong>' . $name . '</strong>,</p>
            <p>Your booking at <strong>Our Resort</strong> has been successfully completed!</p>
            <p>We hope you had a wonderful stay. If you have any feedback, we’d love to hear from you.</p>
            <p>Looking forward to welcoming you again soon!</p>
            <br>
            <p class="footer">If you have any questions, feel free to contact us at <a href="mailto:support@yourresort.com">support@yourresort.com</a>.</p>
        </div>
    </body>
    </html>
    ';
    if(empty($to)){
        echo 'email is empty';
    }else{
        if (mail($to,$subject, $message, $headers)) {
            $update = "UPDATE booking SET completion_sent=NOW() WHERE book_id=" . $row['book_id'];
            if(mysqli_query($conn, $update)){
                mysqli_commit($conn);
            }
        }else{
            echo 'failed to update the email fields in booking table';
        }
    }
}

}catch(Exception $e){
    mysqli_rollback($conn);
    echo 'Error: ' . $e->getMessage();
}

//auto booked when meets the check_in date
$auto_booked_query = "SELECT * FROM booking WHERE book_status='pending' AND check_in <= NOW()";
$auto_booked_result = mysqli_query($conn, $auto_booked_query);

if ($auto_booked_result) {
    while ($booking = mysqli_fetch_assoc($auto_booked_result)) {
        $update_booking_status = "UPDATE booking SET book_status='booked' WHERE book_id={$booking['book_id']}";
        if (mysqli_query($conn, $update_booking_status)) {
            $update_room_status = "UPDATE room SET room_status='booked' WHERE room_id={$booking['room_id']}";
            if (!mysqli_query($conn, $update_room_status)) {
                echo "Error updating room status: " . mysqli_error($conn);
            }
        } else {
            echo "Error updating booking status: " . mysqli_error($conn);
        }
    }
}

// Notify users of double bookings and ensure double booking checks are properly implemented

// Check for double bookings
$double_booking_query = "SELECT b1.book_id AS booking1, b2.book_id AS booking2, r.room_code, b1.check_in, b1.check_out, b1.book_status AS status1, b2.book_status AS status2, b2.account_id AS pending_account_id
                         FROM booking b1
                         INNER JOIN booking b2 ON b1.room_id = b2.room_id AND b1.book_id != b2.book_id
                         INNER JOIN room r ON b1.room_id = r.room_id
                         WHERE b1.check_in < b2.check_out 
                         AND b1.check_out > b2.check_in";
$double_booking_result = mysqli_query($conn, $double_booking_query);

if (mysqli_num_rows($double_booking_result) > 0) {
    while ($row = mysqli_fetch_assoc($double_booking_result)) {
        $booking1 = $row['booking1'];
        $booking2 = $row['booking2'];
        $room_code = $row['room_code'];
        $check_in = $row['check_in'];
        $check_out = $row['check_out'];
        $status1 = $row['status1'];
        $status2 = $row['status2'];
        $pending_account_id = $row['pending_account_id'];

        // If one booking is confirmed and the other is pending
        if (($status1 == 'confirmed' && $status2 == 'pending') || ($status1 == 'pending' && $status2 == 'confirmed')) {
            $pending_booking_id = ($status1 == 'pending') ? $booking1 : $booking2;

            // Fetch the email of the user with the pending booking
            $email_query = "SELECT a.username AS email, u.fname, u.lname 
                            FROM account a
                            INNER JOIN user u ON a.account_id = u.account_id
                            WHERE a.account_id = $pending_account_id";
            $email_result = mysqli_query($conn, $email_query);

            if ($email_row = mysqli_fetch_assoc($email_result)) {
                $to = $email_row['email'];
                $name = $email_row['fname'] . " " . $email_row['lname'];
                $subject = "Booking Status Update - Room #$room_code";
                $message = "
                <html>
                <head>
                    <title>Booking Status Update</title>
                </head>
                <body>
                    <p>Dear $name,</p>
                    <p>We regret to inform you that your booking for Room #$room_code from $check_in to $check_out has been cancelled due to a conflict with another confirmed booking.</p>
                    <p>Please contact us for further assistance or to modify your booking.</p>
                    <p>Best regards,<br>Sample Resort Team</p>
                </body>
                </html>";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
                $headers .= "From: Sample Resort <no-reply@sampleresort.com>" . "\r\n";

                // Send email notification
                mail($to, $subject, $message, $headers);

                // Mark the pending booking as cancelled
                $cancel_query = "UPDATE booking SET book_status = 'cancelled' WHERE book_id = $pending_booking_id";
                mysqli_query($conn, $cancel_query);
            }
        }
    }
}

// Notify users with pending downpayments and enforce minimum downpayment rule
$downpayment_query = "SELECT 
    p.payment_id, 
    p.book_id, 
    p.amount AS total_amount, 
    p.pay_amount AS downpayment_amount, 
    (p.amount - p.pay_amount) AS remaining_balance, 
    p.payment_type, 
    p.payment_status, 
    p.created_at, 
    b.check_in, 
    b.check_out
FROM resort_ms.payment p
INNER JOIN booking b ON p.book_id = b.book_id
WHERE (p.amount - p.pay_amount) > 0
AND (p.pay_amount < (p.amount * 0.5))";

$downpayment_result = mysqli_query($conn, $downpayment_query);

if (mysqli_num_rows($downpayment_result) > 0) {
    while ($row = mysqli_fetch_assoc($downpayment_result)) {
        // Check if at least 50% of the total amount is paid
        $minimum_downpayment = $row['total_amount'] * 0.5;
        if ($row['downpayment_amount'] >= $minimum_downpayment) {
            // Booking can be confirmed
            $update_booking_status = "UPDATE booking SET book_status = 'confirmed' WHERE book_id = {$row['book_id']}";
            mysqli_query($conn, $update_booking_status);
        } else {
            // Booking remains pending
            $update_booking_status = "UPDATE booking SET book_status = 'pending' WHERE book_id = {$row['book_id']}";
            mysqli_query($conn, $update_booking_status);
        }
    }
}

//booking real time tracking and update
$check="SELECT * FROM booking INNER JOIN room USING(room_id)";
$check_set=mysqli_query($conn,$check);
$today = date("Y-m-d H:i:s");
while($check_record=mysqli_fetch_assoc($check_set)){
    if($check_record['check_out']<=$today && $check_record['book_status']=="confirmed"){
        echo $set="UPDATE booking SET book_status='completed' WHERE book_id={$check_record['book_id']}";
        $set1=mysqli_query($conn,$set); 
        if(mysqli_affected_rows($conn)>0){
           echo $set2="UPDATE room SET room_status='available' WHERE room_id={$check_record['room_id']}";
            if (!mysqli_query($conn, $set2)) {
                echo "Error updating room status: " . mysqli_error($conn);
            }else{
                $message="'Room #[{$check_record['room_code']}] is now availble'";
                $room_notif="INSERT INTO room_notification(room_id,message,Date)Values({$check_record['room_id']},$message,NOW())";
                $confirm_notif=mysqli_query($conn,$room_notif);
                
            }
        }
    }
}

$sql="UPDATE room 
JOIN booking ON room.room_id = booking.room_id
SET room.room_status = 'available'
WHERE room.room_status = 'booked' 
AND NOW() > booking.check_out;
";
$execute=mysqli_query($conn,$sql);


// Auto set room status to "booked" if the current date is within the check-in and check-out period
$room_status = "SELECT DISTINCT room_id FROM booking WHERE NOW() BETWEEN booking.check_in AND booking.check_out";
$notif = mysqli_query($conn, $room_status);
if ($notif) {
    $room_ids = [];
    while ($status = mysqli_fetch_assoc($notif)) {
        $room_ids[] = $status['room_id']; // Store room_id in an array
    }
    
    if (!empty($room_ids)) {
        // Convert room_ids to a comma-separated string
        $room_id_list = implode(',', $room_ids);
        $room_update = "UPDATE room SET room_status='booked' WHERE room_id IN ($room_id_list)";
        mysqli_query($conn, $room_update);
    }
}
 





//realtime discount tracking
$track_query="SELECT 
(SELECT COUNT(*) FROM discount WHERE discount_start<=NOW() && discount_end>=NOW()) AS active_discount,
(SELECT COUNT(*) FROM discount WHERE discount_start<NOW() && discount_end<NOW()) AS expired_discount,
(SELECT COUNT(*) FROM discount WHERE discount_start>NOW() && discount_end>NOW()) AS upcoming_discount;";



//auto cancel booking after check-in
$check="SELECT * FROM booking";
$check_set=mysqli_query($conn,$check);
$today = date("Y-m-d H:i:s");
while($check_record=mysqli_fetch_assoc($check_set)){
    if($check_record['check_in']<=$today && $check_record['book_status']=="pending"){
        echo $set="UPDATE booking SET book_status='cancelled' WHERE book_id={$check_record['book_id']}";
        $set1=mysqli_query($conn,$set); 
        if(mysqli_affected_rows($conn)>0){
           echo $set2="UPDATE room SET room_status='available' WHERE room_id={$check_record['room_id']}";
            if (!mysqli_query($conn, $set2)) {
                echo "Error updating room status: " . mysqli_error($conn);
            }
        }
    }
}



try {
    mysqli_begin_transaction($conn);

    // Step 1: Fetch old tasks that need to be duplicated
    $fetch_old_tasks = "SELECT id, title, description, priority, recurrence, due_date, template_id FROM tasks 
                        WHERE recurrence <> 'None' 
                        AND due_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
    $old_tasks_result = mysqli_query($conn, $fetch_old_tasks);

    // Store new task IDs
    $task_mapping = [];

    while ($row = mysqli_fetch_assoc($old_tasks_result)) {
        // Step 2: Calculate the new due date
        if ($row['recurrence'] == 'Daily') {
            $new_due_date = date('Y-m-d', strtotime($row['due_date'] . ' +1 day'));
        } elseif ($row['recurrence'] == 'Weekly') {
            $new_due_date = date('Y-m-d', strtotime($row['due_date'] . ' +1 week'));
        } elseif ($row['recurrence'] == 'Monthly') {
            $new_due_date = date('Y-m-d', strtotime($row['due_date'] . ' +1 month'));
        } else {
            continue;
        }

        // Step 3: Check if the task already exists
        $check_task = "SELECT id FROM tasks WHERE title = '{$row['title']}' 
                        AND description = '{$row['description']}'
                        AND priority = '{$row['priority']}'
                        AND due_date = '$new_due_date'
                        AND recurrence = '{$row['recurrence']}'
                        LIMIT 1";
        $task_exists_result = mysqli_query($conn, $check_task);

        if (mysqli_num_rows($task_exists_result) > 0) {
            continue; // Task already exists, skip inserting
        }

        // Step 4: Insert new task
        $insert_task = "INSERT INTO tasks (title, description, priority, status, due_date, recurrence, template_id)
                        VALUES ('{$row['title']}', '{$row['description']}', '{$row['priority']}', 'Pending', '$new_due_date', '{$row['recurrence']}', '{$row['template_id']}')";

        if (mysqli_query($conn, $insert_task)) {
            $new_task_id = mysqli_insert_id($conn); // Get newly inserted task ID
            $task_mapping[$row['id']] = $new_task_id;
        } else {
            throw new Exception("Error inserting new task: " . mysqli_error($conn));
        }
    }

    // Step 5: Assign staff to new tasks
    foreach ($task_mapping as $old_task_id => $new_task_id) {
        $fetch_assignees = "SELECT staff_id FROM task_assignees WHERE task_id = '$old_task_id'";
        $assignees_result = mysqli_query($conn, $fetch_assignees);

        if ($assignees_result) {
            while ($assignee = mysqli_fetch_assoc($assignees_result)) {
                $staff_id = $assignee['staff_id'];

                $assign_task = "INSERT INTO task_assignees (task_id, staff_id, assignee_task)
                                VALUES ('$new_task_id', '$staff_id', 'Pending')";

                if (!mysqli_query($conn, $assign_task)) {
                    throw new Exception("Error assigning staff: " . mysqli_error($conn));
                }
            }
        }
    }

    // Update staff notifications for new tasks
    foreach ($task_mapping as $old_task_id => $new_task_id) {
    $fetch_assignees = "SELECT ta.staff_id,t.title FROM task_assignees ta INNER JOIN tasks t WHERE task_id = '$old_task_id' GROUP BY ta.staff_id";
        $assignees_result = mysqli_query($conn, $fetch_assignees);

        if ($assignees_result) {
            while ($assignee = mysqli_fetch_assoc($assignees_result)) {
                $staff_id = $assignee['staff_id'];

                $notif_message = "You have a new task: {$assignee['title']}";
                $insert_notif = "INSERT INTO task_notifications (task_id, staff_id, message, is_read, created_at)
                                 VALUES ('$new_task_id', '$staff_id', '$notif_message', 0, NOW())";

                if (!mysqli_query($conn, $insert_notif)) {
                    throw new Exception("Error inserting notification: " . mysqli_error($conn));
                }
            }
        }
    }

    // Commit the transaction if all queries are successful
    mysqli_commit($conn);

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo 'Error: ' . $e->getMessage();
}







$task_complete="UPDATE tasks INNER JOIN task_assignees ta ON tasks.id=ta.task_id SET status='Completed' WHERE ta.assignee_task='Complete' AND tasks.status='Pending'";
$task_complete_set=mysqli_query($conn,$task_complete);

$set_overdue="UPDATE tasks t INNER JOIN task_assignees ta ON t.id=ta.task_id SET t.status='Overdue' WHERE t.status='Pending' AND t.due_date<CURDATE()";
$set_overdue_set=mysqli_query($conn,$set_overdue);

$set_staff_overdue="UPDATE task_assignees ta INNER JOIN tasks t ON ta.task_id=t.id SET ta.assignee_task='overdue' WHERE t.status='Overdue' AND ta.assignee_task='pending'";
$set_staff_overdue_set=mysqli_query($conn,$set_staff_overdue);


//sent receipt email

// Fetch payment details
$payment_sent = "SELECT * FROM summary_payment WHERE attached_receipt IS NULL AND payment_status ='paid'";
$pres = mysqli_query($conn, $payment_sent);

if (mysqli_num_rows($pres) > 0) {
    while ($row = mysqli_fetch_assoc($pres)) {
        require_once('../../resources/fpdf186/fpdf.php');

        $booking_id = $row['booking_id'];
        $customer_email = $row['email'];
        $customer_name = $row['NAME'];
        $amount_paid = $row['amount_paid'];
        $check_in = date("F d, Y", strtotime($row['check_in']));
        $check_out = date("F d, Y", strtotime($row['check_out']));
        $room_code = $row['room_code'];
        $room_type = $row['room_type'];

        // Generate PDF Invoice
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Header
        $pdf->Cell(190, 10, 'Resort Management System', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(190, 10, '123 Beachfront Road, Paradise Island', 0, 1, 'C');
        $pdf->Cell(190, 10, 'Email: info@resort.com | Phone: (123) 456-7890', 0, 1, 'C');
        $pdf->Ln(10);

        // Title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(190, 10, 'INVOICE', 0, 1, 'C');
        $pdf->Ln(5);

        // Customer Details
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, 'Customer Details', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(190, 10, 'Name: ' . $customer_name, 0, 1, 'L');
        $pdf->Cell(190, 10, 'Email: ' . $customer_email, 0, 1, 'L');
        $pdf->Ln(5);

        // Booking Details
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, 'Booking Details', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(190, 10, 'Booking ID: ' . $booking_id, 0, 1, 'L');
        $pdf->Cell(190, 10, 'Room: ' . $room_code . ' (' . $room_type . ')', 0, 1, 'L');
        $pdf->Cell(190, 10, 'Check-in: ' . $check_in, 0, 1, 'L');
        $pdf->Cell(190, 10, 'Check-out: ' . $check_out, 0, 1, 'L');
        $pdf->Ln(10);

        // Payment Details
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, 'Payment Details', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(190, 10, 'Amount Paid: PHP ' . number_format($amount_paid, 2), 0, 1, 'L');
        $pdf->Ln(10);

        // Footer
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(190, 10, 'Thank you for choosing our resort!', 0, 1, 'C');
        $pdf->Cell(190, 10, 'For inquiries, contact us at (123) 456-7890', 0, 1, 'C');

        // Save PDF to a file
        $pdf_file = "invoice_{$booking_id}.pdf";
        $pdf->Output($pdf_file, 'F');

        // Prepare Email
        $to = $customer_email;
        $subject = "Payment Receipt - Booking #$booking_id";
        $from_email = "info@resort.com";
        $boundary = md5(time());
        $headers = "From: Resort Management <$from_email>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

        // Email body
        $message = "--$boundary\r\n";
        $message .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
        $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $message .= "Dear $customer_name,\n\nThank you for your payment. Please find your invoice attached.\n\nBest Regards,\nResort Management System\r\n";

        // Read and attach PDF
        $file_content = chunk_split(base64_encode(file_get_contents($pdf_file)));
        $message .= "--$boundary\r\n";
        $message .= "Content-Type: application/pdf; name=\"$pdf_file\"\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n";
        $message .= "Content-Disposition: attachment; filename=\"$pdf_file\"\r\n\r\n";
        $message .= $file_content . "\r\n";
        $message .= "--$boundary--";

        // Send email
        if (mail($to, $subject, $message, $headers)) {
            // Mark email as sent
            $update_query = "UPDATE payment SET email_receipt = NOW() WHERE book_id = $booking_id";
            mysqli_query($conn, $update_query);
        }

        // Delete the temporary PDF file
        unlink($pdf_file);
    }
}
?>  