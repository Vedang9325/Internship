<?php
session_start();

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
    exit();
}

if($_SESSION['role']!="Manager")
{
    header("Location: dashboard.php");
    exit();
}

include("config.php");

if(isset($_POST['add']))
{
    $name=$_POST['name'];
    $contact=$_POST['contact'];
    $phone=$_POST['phone'];
    $email=$_POST['email'];
    $product=$_POST['product'];

    $sql="INSERT INTO companies
          (name,contact,phone,email,product_interest)
          VALUES
          ('$name','$contact','$phone','$email','$product')";

    mysqli_query($conn,$sql);

    header("Location: companies.php");
    exit();
}


if(isset($_GET['delete']))
{
    $id=$_GET['delete'];

    mysqli_query($conn,
        "DELETE FROM companies WHERE id=$id");

    header("Location: companies.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>

<title>Companies</title>
<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">
<div class="header">
<div>

<h1>Company Management</h1>

<p>
Logged in as
<b><?php echo $_SESSION['name']; ?></b>
</p>

</div>
<a href="logout.php">Logout</a>
</div>

<nav>

<a href="dashboard.php">Dashboard</a>
<a href="tasks.php">Tasks</a>
<a href="employees.php">Employees</a>
<a href="companies.php">Companies</a>
<a href="reports.php">Reports</a>

</nav>

<div class="box">

<h2>Add Company</h2>

<form method="POST">

<label>Company Name</label>

<input type="text"
       name="name"
       required>

<label>Contact Person</label>

<input type="text"
       name="contact">

<label>Phone</label>

<input type="text"
       name="phone">

<label>Email</label>

<input type="email"
       name="email">

<label>Interested Product</label>

<input type="text"
       name="product">

<input type="submit"
       name="add"
       value="Add Company">

</form>

</div>

<div class="box">

<h2>Company List</h2>

<table>

<tr>
<th>Company</th>
<th>Contact</th>
<th>Phone</th>
<th>Email</th>
<th>Product Interest</th>
<th>Action</th>
</tr>

<?php

$result=mysqli_query($conn,
    "SELECT * FROM companies
     ORDER BY name");

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>
<td>
<?php echo $row['name']; ?>
</td>
<td>
<?php echo $row['contact']; ?>
</td>
<td>
<?php echo $row['phone']; ?>
</td>
<td>
<?php echo $row['email']; ?>
</td>
<td>
<?php echo $row['product_interest']; ?>
</td>
<td>

<a href="companies.php?delete=<?php echo $row['id']; ?>"
   onclick="return confirm('Delete this company?');">

Delete

</a>
</td>
</tr>

<?php

}

?>

</table>

</div>

</div>

</body>

</html>