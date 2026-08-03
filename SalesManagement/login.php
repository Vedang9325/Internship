<?php
session_start();
include("config.php");

if(isset($_POST['login']))
{
    $email=$_POST['email'];
    $password=$_POST['password'];

    $sql="SELECT * FROM employees
          WHERE email='$email'
          AND password='$password'";

    $result=mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==1)
    {
        $row=mysqli_fetch_assoc($result);

        $_SESSION['id']=$row['id'];
        $_SESSION['name']=$row['name'];
        $_SESSION['role']=$row['role'];

        header("Location: dashboard.php");
    }
    else
    {
        $error="Invalid Email or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<link rel="stylesheet" href="style.css">

</head>
<body>

<div class="login-box">

<h2>Sales Management Portal</h2>

<form method="POST">

<label>Email</label>

<input type="email"
name="email"
required>

<label>Password</label>

<input type="password"
name="password"
required>
<input
type="submit"
name="login"
value="Login">
</form>

<?php
if(isset($error))
{
    echo "<p>$error</p>";
}
?>
</div>
</body>
</html>