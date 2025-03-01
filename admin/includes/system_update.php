<?php 

//booking real time tracking
$check="SELECT * FROM booking";
$check_set=mysqli_query($conn,$check);
echo $today = date("Y-m-d");
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


//realtime discount tracking
$track_query="SELECT 
(SELECT COUNT(*) FROM discount WHERE discount_start<=CURDATE() && discount_end>=CURDATE()) AS active_discount,
(SELECT COUNT(*) FROM discount WHERE discount_start<CURDATE() && discount_end<CURDATE()) AS expired_discount,
(SELECT COUNT(*) FROM discount WHERE discount_start>CURDATE() && discount_end>CURDATE()) AS upcoming_discount;";





?>