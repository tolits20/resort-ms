<?php 

include ('../includes/template.html');
include('../../resources/database/config.php');

$sql="SELECT * FROM room";
$result=mysqli_query($conn,$sql);


?>
<div class="content" style="color:black;">
    <table class="table table-striped">
        <tr>
        <th>Room Code</th>
        <th>Status</th>
        <th>Action</th>
        </tr>
        <?php 
       while($row=mysqli_fetch_assoc($result)){
        print "<tr>
        <td>{$row['room_code']}</td>
        <td>{$row['status']}</td>
        <td><a href='edit.php?id={$row['room_id']}' class='btn btn-primary'><i class='fas fa-edit'></i></a>
        <a href='edit.php?id={$row['room_id']}' class='btn btn-danger'><i class='fas fa-trash'></i></a></td>
        
        </tr>";
       }
        ?>
    </table>
</div>
