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
<div class="advanced-search-container">
    <div class="search-navigation">
        <button class="navigation-btn back-btn" onclick="history.back()">
            <i class="fas fa-arrow-left"></i>
        </button>

        <form action="<?php $_SERVER['PHP_SELF']?>" method="get" class="search-form">
            <div class="search-wrapper">
                <div class="search-input-group">
                    <input 
                        type="text" 
                        class="search-input" 
                        name="searchbar" 
                        placeholder="Search accounts..." 
                        value="<?php echo (isset($_GET['searchbar']) ? $_GET['searchbar'] : '') ?>"
                    >
                    <button type="submit" name="search" class="search-btn" value="search">
                        <i class="fas fa-magnifying-glass"></i>
                    </button>
                </div>

                <select 
                    name="sort" 
                    class="sort-select" 
                    onchange="this.form.submit()"
                >
                    <option 
                        value="ASC" 
                        <?php echo (isset($_GET['sort']) && $_GET['sort']==='ASC' ? 'selected' : '' ) ?>
                    >
                        A to Z
                    </option>
                    <option 
                        value="DESC" 
                        <?php echo (isset($_GET['sort']) && $_GET['sort']==='DESC' ? 'selected' : '' ) ?>
                    >
                        Z to A
                    </option>
                </select>
            </div>
        </form>

        <a href="create.php?create=true" class="action-btn add-btn">
            <i class="fas fa-plus"></i>
        </a>
    </div>
</div>

<style>
:root {
    --primary-color: #3a7bd5;
    --secondary-color: #f4f7f6;
    --text-color: #333;
    --border-color: #e0e4e7;
}

.advanced-search-container {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-color: var(--secondary-color);
    padding: 15px;
    border-radius: 12px;
}

.search-navigation {
    display: flex;
    align-items: center;
    gap: 15px;
}

.navigation-btn, .action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: 10px;
    transition: all 0.3s ease;
    text-decoration: none;
}

.back-btn {
    background-color: #f1f3f4;
    color: var(--text-color);
    border: none;
    cursor: pointer;
}

.back-btn:hover {
    background-color: #e1e3e4;
}

.back-btn i {
    font-size: 18px;
}

.search-form {
    flex-grow: 1;
}

.search-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;
}

.search-input-group {
    flex-grow: 1;
    display: flex;
    align-items: center;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.search-input {
    flex-grow: 1;
    padding: 12px 15px;
    border: none;
    font-size: 16px;
    outline: none;
}

.search-btn {
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 12px 15px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-btn:hover {
    background-color: #2c5fc4;
}

.sort-select {
    padding: 12px 15px;
    border-radius: 10px;
    border: 1px solid var(--border-color);
    background-color: white;
    font-size: 16px;
    transition: all 0.3s ease;
}

.sort-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.1);
}

.add-btn {
    background-color: var(--primary-color);
    color: white;
}

.add-btn:hover {
    background-color: #2c5fc4;
}

@media (max-width: 768px) {
    .search-navigation {
        flex-direction: column;
        gap: 10px;
    }

    .search-wrapper {
        flex-direction: column;
        width: 100%;
    }

    .search-input-group, .sort-select {
        width: 100%;
    }
}
</style>