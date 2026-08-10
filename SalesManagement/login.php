<?php
session_start();
include("config.php");

if(isset($_POST['login']))
{
    $user_id=$_POST['user_id'];
    $password=$_POST['password'];

    $sql="SELECT * FROM employees
          WHERE user_id='$user_id'
          AND password='$password'";

    $result=mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==1)
    {
        $row=mysqli_fetch_assoc($result);

        $_SESSION['id']=$row['id'];
        $_SESSION['name']=$row['name'];
        $_SESSION['user_id']=$row['user_id'];
        $_SESSION['role']=$row['role'];

        if($row['must_change_password']==1)
        {
            header("Location: change_password.php");
        }
        else
        {
            header("Location: dashboard.php");
        }

        exit();
    }
    else
    {
        $error="Invalid User ID or Password";
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

<label>User ID</label>
<input type="text" name="user_id" required>

<label>Password</label>
<input type="password" name="password" required>

<input type="submit" name="login" value="Login">

</form>

<?php
if(isset($error))
{
    echo "<p class='error'>$error</p>";
}
?>

<p>
New employee?
<a href="register.php">Create Account</a>
</p>

</div>

</body>
</html>