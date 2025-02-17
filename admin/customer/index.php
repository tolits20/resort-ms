<?php 
include ('../includes/template.html');
include('../../resources/database/config.php');

?>
<style>

    table th, td {
        vertical-align: middle;
        padding: 10px;
        text-align: center;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 25px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: red;
        transition: .4s;
        border-radius: 25px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: green;
    }

    input:checked + .slider:before {
        transform: translateX(25px);
    }

    .btn {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        font-size: 16px;
    }

    .btn i {
        font-size: 16px;
    }

    .popup-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .popup-content {
        background: white;
        padding: 20px;
        width: 400px;
        text-align: start;
        border-radius: 8px;
        position: relative;
    }

    .popup-content input{
        border:solid 1px;
    }
    .popup-content input[name='yes']:hover{
        border:solid 1px;
        background-color: red;
        color: #fff;
    }
    .popup-content button[name='no']:hover{
        border:solid 1px;
        background-color: green;
        color: #fff;
    }


    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
        color: red;
    }

</style>

<div class="content">
    <?php include('filter.php');
      ?>
    <br>
    <table class='table table-striped'>
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        
        <?php 
        while ($row = mysqli_fetch_assoc($result)) {
            $is_active = ($row['status'] === 'activate');
            echo "<tr>
                <td>{$row['username']}</td>
                <td>{$row['role']}</td>";
                $opstat=($row['status']==='activate' ? 'deactivate' : 'activate');
                print "<td>";
                print ($row['status']==='activate' ? '<p>Active</p>' : '<p>Deactivate</p>');
                print "<form method='post' action='update.php'>
            <input type='hidden' name='account_id' value='{$row['account_id']}'>
            <input type='hidden' name='stat1' value='$opstat'>
            <label class='switch'>
                <input type='checkbox' name='status' value='$opstat' onchange='this.form.submit()' " . ($is_active ? "checked" : "") . ">
                <span class='slider'></span>
            </label>
        </form>
    </td>
    <td>
        <a href='view.php?id={$row['account_id']}' class='btn btn-primary btn-sm'><i class='fas fa-eye'></i></a>
        <a href='edit.php?id={$row['account_id']}' class='btn btn-primary btn-sm'><i class='fas fa-edit'></i></a>
        <button class='btn btn-danger' type='button' onclick='openPopup({$row['account_id']})'><i class ='fas fa-trash'></i></button>
        
        <div class='popup-overlay' id='popup-{$row['account_id']}' style='color: black; display: none;'>
            <div class='popup-content'>
                <form action='delete.php?id={$row['account_id']}' method='post'>
                    <span class='close-btn' onclick='closePopup({$row['account_id']})'>&times;</span>
                    <h6>Are you sure you want to <strong style='color: red;'>Delete</strong> this Account?</h6>  
                    <input type='hidden' name='account_id' value='{$row['account_id']}'>
                    <br>
                    <input type='submit' value='YES' class='form-control' name='yes'>
                    <hr> 
                    <button type='submit' name='no' class='form-control' onclick='closePopup({$row['account_id']})'>NO</button>
                    <br>
                </form>
            </div>
        </div>
    </td>
</tr>";

print "<script>
    function openPopup(accountId) {
        document.getElementById('popup-' + accountId).style.display = 'flex';
    }

    function closePopup(accountId) {
        document.getElementById('popup-' + accountId).style.display = 'none';
    }
</script>";

            print "";
        }
        ?>
   </table>
</div>
<?php   ?>
