<?php 
$id = $_SESSION['ID'];
$result;
if(isset($_GET['search']) && !empty($_GET['searchbar'])){
 $find=isset($_GET['searchbar']) ? $_GET['searchbar'] : '';
$sql1 = "SELECT * FROM account WHERE account_id <> ? AND username LIKE ? ";
$stmt = mysqli_prepare($conn, $sql1);
$search="%$find%";
mysqli_stmt_bind_param($stmt, 'is', $id,$search);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
}else{
$sql1 = "SELECT * FROM account WHERE account_id <> ?";
$stmt = mysqli_prepare($conn, $sql1);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
}

if(isset($_GET['sort'])){
 $sort=$_GET['sort'];
 $find=$_GET['searchbar'];
  $sql1 = "SELECT * FROM account WHERE account_id <> ? AND username LIKE ? ORDER BY username $sort";
$stmt = mysqli_prepare($conn, $sql1);
$search="%$find%";
mysqli_stmt_bind_param($stmt, 'is', $id,$search);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
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
    <button class="back-btn" onclick="history.back()">← </button>
   <form action="<?php $_SERVER['PHP_SELF']?>" method="get" style="width: 50%; margin:0 20px; display:flex; flex-direction:row; gap:100px;">
  <div class="searchdiv" style="display: flex;">
     <input type="text" class="search-bar" name="searchbar"  placeholder="Search..." value="<?php echo (isset($_GET['searchbar']) ? $_GET['searchbar'] : '') ?>">
   <button class="btn btn-primary" name="search" value="search"><i class="fas fa-magnifying-glass"></i></button>
  </div>
   <select name="sort" id="" class="form-select" onchange="this.form.submit()">
    <option value="ASC" <?php echo (isset($_GET['sort']) && $_GET['sort']==='ASC' ? 'selected' : '' ) ?> >Ascending</option>
    <option value="DESC" <?php echo (isset($_GET['sort']) && $_GET['sort']==='DESC' ? 'selected' : '' ) ?>>Descending</option>
   </select>
   </form>
    <a href="create.php" class="btn btn-primary" style="text-decoration: none; color:white;"><i class="fas fa-add "></i></a>
</div>