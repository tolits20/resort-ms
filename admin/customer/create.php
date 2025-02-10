<?php 
include ('../includes/bootstrap.html');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <form action="store.php" method="POST" enctype="multipart/form-data">
            <div class="fname">
                <label for="" class="form-label">First Name:</label><br>
                <input type="text" name="fname" class="form-control" placeholder="Firstname" required>
            </div>
            <br>
            <div class="lastname">
                <label for="" class="form-label">Last Name:</label><br>
                <input type="text" class="form-control" name="lname" placeholder="surname" required>
            </div>
            <br>
            <div class="age">
                <label for="" class="form-label">Age:</label>
                <input type="number" name="age" class="form-control" required min="18">
            </div>
            <br>
            <div class="gender">
                <label for="" class="form-label">Gender:</label>
                <select name="gender" class="form-control" id="">
                    <option value="male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <br>
            <div class="contact">
                <label for="" class="form-label">Contacts:</label>
                <input type="text" name="contact" class="form-control">
            </div>
            <br>
            <div class="email">
                <label for="" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control">
            </div>
            <br>
            <div class="btn">
                <input type="submit" name="create" value="create">
            </div>
        </form>
    </div>
</body>
</html>