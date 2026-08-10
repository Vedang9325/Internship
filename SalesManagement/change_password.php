<?php
session_start();
include("config.php");

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
    exit();
}

if(isset($_POST['change']))
{
    $password=$_POST['password'];
    $confirm=$_POST['confirm'];

    if($password==$confirm)
    {
        $id=$_SESSION['id'];

        $sql="UPDATE employees
              SET password='$password',
              must_change_password=0
              WHERE id='$id'";

        mysqli_query($conn,$sql);

        header("Location: dashboard.php");
        exit();
    }
    else
    {
        $error="Passwords do not match";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Change Password</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="login-box">

<h2>Change Password</h2>

<p>Please change your temporary password.</p>

<form method="POST">

<label>New Password</label>
<input type="password" name="password" required>

<label>Confirm Password</label>
<input type="password" name="confirm" required>

<input type="submit" name="change" value="Change Password">

</form>

<?php
if(isset($error))
{
    echo "<p class='error'>$error</p>";
}
?>

</div>

</body>
</html>