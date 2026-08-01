<?php
$insert=false;
if (isset($_POST['name'])) {
    
    $server="localhost";
    $username="root";
    $password="";

    $con=mysqli_connect($server,$username,$password);

    if (!$con) {
        die("connection to database failed due to ".mysqli_connect_error());
    }
    // echo "sucess connecting to the db";
    $name=$_POST['name'];
    $gender=$_POST['gender'];
    $age=$_POST['age'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];
    $desc=$_POST['desc'];
    $sql="INSERT INTO `trip`.`trip` (`name`, `age`, `gender`, `email`, `phone`, `other`, `dt`) VALUES ('$name', '$age', '$gender', '$email', '$phone', '$desc', current_timestamp())";
    // echo $sql;


    if($con->query($sql)==true){
        // echo "sucessfully inserted";
        $submit=true;
    }
    else{
        echo "ERROR: $sql <br> $con->error";
    }
    $con->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="bg"><img src="bg.jpg" alt="RAIT"></div>
    <div class="container">
    <h3>Welcome to RAIT</h3>
    <p>Enter details to confirm participation</p>
    <?php
        if($insert==true){
            echo "<p class='submit'>Thanks for submitting</p>";
        }
    ?>
    <form action="index.php" method="post">
    <input type="text" name="name" id="name" placeholder="Enter your name">
    <input type="text" name="age" id="age" placeholder="Enter your age">
    <input type="text" name="gender" id="gender" placeholder="Enter your gender">
    <input type="email" name="email" id="email" placeholder="Enter your email">
    <input type="phone" name="phone" id="phone" placeholder="Enter your phone">
    <textarea name="desc" id="desc" cols="30" rows="10" placeholder="Enter any other information here"></textarea>
    <button class="btn">Submit</button>
    <!-- <button class="btn">Reset</button> -->
    </form>
    </div>
    <script src="index.js"></script>
</body>
</html>