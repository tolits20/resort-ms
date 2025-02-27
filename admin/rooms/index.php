<?php 

include ('../includes/template.html');
include('../../resources/database/config.php');

?>
<style>
  
</style>
<div class="content" style="color:black;">
    <?php
    include ('alert.php');
    include ('filter.php'); ?>
    <br>
    <table class="table table-striped" style="text-align: center;">
        <tr>
        <th>Room Code</th>
        <th>Status</th>
        <th>Action</th>
        </tr>
        <?php 
       while($row=mysqli_fetch_assoc($result)){
        print "<tr>
        <td>{$row['room_code']}</td>
        <td>{$row['room_status']}</td>
        <td><a href='edit.php?id={$row['room_id']}' class='btn btn-primary'><i class='fas fa-edit'></i></a>
        <a href='delete.php?id={$row['room_id']}&index_click=true' class='btn btn-danger'><i class='fas fa-trash'></i></a></td>
        
        </tr>";
       }
        ?>
    </table>
</div>
