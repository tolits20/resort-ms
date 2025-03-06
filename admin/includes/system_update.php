<?php 

//booking real time tracking and update
$check="SELECT * FROM booking";
$check_set=mysqli_query($conn,$check);
echo $today = date("Y-m-d H:i:s");
while($check_record=mysqli_fetch_assoc($check_set)){
    if($check_record['check_out']==$today && $check_record['book_status']=="confirmed"){
        echo $set="UPDATE booking SET book_status='completed' WHERE book_id={$check_record['book_id']}";
        $set1=mysqli_query($conn,$set); 
        if(mysqli_affected_rows($conn)>0){
           echo $set2="UPDATE room SET room_status='available' WHERE room_id={$check_record['room_id']}";
            if (!mysqli_query($conn, $set2)) {
                echo "Error updating room status: " . mysqli_error($conn);
            }
        }
    }
}


//automatic email sending
try{
    $now = date("Y-m-d H:i:s");

// 1. Send reminder email 24 hours before check-in
    $sql = "SELECT 
        'user' AS identntifier,
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
        'user' AS identntifier,
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
    AND b.check_out >= NOW()

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
    AND b.check_out>=NOW();
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





//realtime discount tracking
$track_query="SELECT 
(SELECT COUNT(*) FROM discount WHERE discount_start<=NOW() && discount_end>=NOW()) AS active_discount,
(SELECT COUNT(*) FROM discount WHERE discount_start<NOW() && discount_end<NOW()) AS expired_discount,
(SELECT COUNT(*) FROM discount WHERE discount_start>NOW() && discount_end>NOW()) AS upcoming_discount;";



//auto cancel booking after check-in
$check="SELECT * FROM booking";
$check_set=mysqli_query($conn,$check);
echo $today = date("Y-m-d H:i:s");
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





?>