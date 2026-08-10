<?php
include("config.php");

if(isset($_POST['register']))
{
    $name=$_POST['name'];
    $user_id=$_POST['user_id'];
    $email=$_POST['email'];

    $password="Welcome123";

    $sql="INSERT INTO employees
          (name,user_id,email,password,role,must_change_password)
          VALUES
          ('$name','$user_id','$email','$password','Employee',1)";

    if(mysqli_query($conn,$sql))
    {
        $message="Account created. Temporary password is Welcome123";
    }
    else
    {
        $message="User ID already exists.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create Account</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="login-box">

<h2>Create Employee Account</h2>

<form method="POST">

<label>Name</label>
<input type="text" name="name" required>

<label>User ID</label>
<input type="text" name="user_id" required>

<label>Email</label>
<input type="email" name="email" required>

<p>Temporary Password: <b>Welcome123</b></p>

<input type="submit" name="register" value="Create Account">

</form>

<?php
if(isset($message))
{
    echo "<p>$message</p>";
}
?>

<a href="login.php">Back to Login</a>

</div>

</body>
</html>