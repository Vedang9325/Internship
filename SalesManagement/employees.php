<?php
session_start();

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
}

if($_SESSION['role']!="Manager")
{
    header("Location: dashboard.php");
}

include("config.php");

// ADD EMPLOYEE
if(isset($_POST['add']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "INSERT INTO employees(name,email,password,role)
            VALUES('$name','$email','$password','$role')";

    mysqli_query($conn,$sql);

    header("Location: employees.php");
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Employees</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<h1>Employee Management</h1>
<a href="dashboard.php">Dashboard</a> |
<a href="tasks.php">Tasks</a> |
<a href="reports.php">Reports</a> |
<a href="logout.php">Logout</a>

<hr>

<h2>Add Employee</h2>

<form method="POST">
<label>Name</label><br>
<input type="text" name="name" required><br>
<label>Email</label><br>
<input type="email" name="email" required><br>
<label>Password</label><br>
<input type="text" name="password" required><br>
<label>Role</label><br>
<select name="role">
<option>Manager</option>
<option>Employee</option>
</select>

<br><br>

<input type="submit" name="add" value="Add Employee">

</form>
<hr>

<h2>Employee List</h2>
<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Delete</th>
</tr>

<?php
$result = mysqli_query($conn,"SELECT * FROM employees");
while($row = mysqli_fetch_assoc($result))
{
?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['role']; ?></td>
<td>
<a href="employees.php?delete=<?php echo $row['id']; ?>">
Delete
</a>
</td>
</tr>
<?php
}
?>
</table>
</body>
</html>
<?php
// DELETE EMPLOYEE

if(isset($_GET['delete']))
{
    $id=$_GET['delete'];
    mysqli_query($conn,"DELETE FROM employees WHERE id=$id");
    header("Location: employees.php");
}
?>