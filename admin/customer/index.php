<?php 
include ('../includes/template.html');

$sql1="SELECT * FROM customer";
mysqli_query($conn,$sql1);

?>
<div class="content">
    <table>
        <tr>
            <th>username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <tr>
            <?php ?>
        </tr>
    </table>
</div>