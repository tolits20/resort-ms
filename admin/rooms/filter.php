<?php 

$result;

if(isset($_GET['search']) && isset($_GET['searchbar'])){
    echo $find=isset($_GET['searchbar']) ? $_GET['searchbar'] : '';
    $search="'%$find%'";
    $sql1 = "SELECT * FROM room WHERE room_code LIKE $search ";
    $result = mysqli_query($conn, $sql1);

    }else{
    $sql1 = "SELECT * FROM room";
    $result = mysqli_query($conn, $sql1);
    
    }
?>
<style>
    
    .top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        background: #f4f4f4;
        border-radius: 8px;
        gap: 15px;
    }

    .back-btn {
        padding: 8px 16px;
        border: none;
        background: #007bff;
        color: white;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    .back-btn:hover {
        background: #0056b3;
    }

    .search-bar {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }
</style>
<div class="top-bar">
    <button class="back-btn" onclick="history.back()">← Back</button>
    <form action="<?php $_SERVER['PHP_SELF']?>" method="get">
   <input type="text" class="search-bar" name="searchbar"  placeholder="Search...">
   <button class="btn btn-primary" name="search" value="search"><i class="fas fa-magnifying-glass"></i></button>
   </form>
    <a href="create.php" class="btn btn-primary" style="text-decoration: none; color:white;"><i class="fas fa-add "></i></a>
</div>