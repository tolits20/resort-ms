<?php 
include ('../includes/template.html');
include('../../resources/database/config.php');

$id=$_SESSION['ID'];
$sql1="SELECT * FROM account WHERE account_id<>{$id}";
$result=mysqli_query($conn,$sql1);

if(mysqli_affected_rows($conn)>0){


?>
<style>
    table th,td{
    vertical-align: middle;
    width: auto;
    padding: 10px;
    text-align: justify;
    }
</style>
<div class="content">
   <div class="div1"></div>
   <table  class='table table-striped' >
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        
            <?php 
            while($row=mysqli_fetch_assoc($result)){
                print "<tr>
                <td>{$row['username']}</td>
                <td>{$row['role']}</td>
                <td><a href='view.php?id={$row['account_id']}' class='btn btn-primary btn-sm'><i class='fas fa-eye'></i></a>
                <a href='edit.php?id={$row['account_id']}' class='btn btn-primary btn-sm'><i class='fas fa-edit'></i></a>
                <a href='delete.php?id={$row['account_id']}' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a></td>
                </tr>"
                ;
            }
    }
            ?>
        
    </table>
</div>