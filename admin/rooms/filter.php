<?php 

$result;

if(isset($_GET['search']) && isset($_GET['searchbar'])){
     $find=isset($_GET['searchbar']) ? $_GET['searchbar'] : '';
    $search="'%$find%'";
    $sql1 = "SELECT 
    room.room_status,
    room.room_id,
    room.price,
    room.room_code,
    discount.discount_name,
    discount.discount_percentage,
    ROUND(room.price - (room.price * (discount.discount_percentage / 100)), 2) AS discounted_price
FROM room
LEFT JOIN discount 
    ON room.room_type = discount.applicable_room 
    AND discount.discount_status = 'activate'
    AND NOW() BETWEEN discount.discount_start AND discount.discount_end
WHERE room.room_code LIKE $search;";
    $result = mysqli_query($conn, $sql1);

    }else{
    $sql1 = "SELECT 
    room.room_status,
    room.room_id,
    room.price,
    room.room_code,
    discount.discount_name,
    discount.discount_percentage,
    ROUND(room.price - (room.price * (discount.discount_percentage / 100)), 2) AS discounted_price
FROM room
LEFT JOIN discount 
    ON room.room_type = discount.applicable_room 
    AND discount.discount_status = 'activate'
    AND NOW() >= discount.discount_start 
    AND NOW() <= discount.discount_end;

";                                                               
    $result = mysqli_query($conn, $sql1);
    
    }
?>
<style>
    
    .top-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px;
    background: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    gap: 10px;
}

.back-btn {
    padding: 10px 18px;
    border: none;
    background: #007bff;
    color: white;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}

.back-btn:hover {
    background: #0056b3;
    transform: scale(1.05);
}

.search-bar {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: border 0.3s;
}

.search-bar:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}

.btnn-primary {
    padding: 10px 15px;
    background: #007bff;
    color: white;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}

.btnn-primary i {
    font-size: 18px;
}

.btnn-primary:hover {
    background: #0056b3;
    transform: scale(1.05);
}

</style>
<div class="top-bar">
    <button class="back-btn" onclick="history.back()">← Back</button>
    <form action="<?php $_SERVER['PHP_SELF']?>" method="get">
   <input type="text" class="search-bar" name="searchbar"  placeholder="Search...">
   <button class="btnn btnn-primary" name="search" ><i class="fas fa-magnifying-glass"></i></button>
   </form>
    <a href="create.php" class="btnn btnn-primary" style="text-decoration: none; color:white;"><i class="fas fa-add "></i></a>
</div>
<br>