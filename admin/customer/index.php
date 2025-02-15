<?php 
include ('../includes/template.html');
include('../../resources/database/config.php');

$id = $_SESSION['ID'];
$sql1 = "SELECT * FROM account WHERE account_id <> ?";
$stmt = mysqli_prepare($conn, $sql1);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
?>
<style>
    table th, td {
        vertical-align: middle;
        padding: 10px;
        text-align: center;
    }

    /* Toggle Switch Styles */
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
</style>

<div class="content">
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
                    <a href='delete.php?id={$row['account_id']}' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>
<?php } ?>
