<?php

session_start();

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
}

include("config.php");

?>

<!DOCTYPE html>
<html>
<head>
<title>Reports</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<h1>Reports</h1>
<a href="dashboard.php">Dashboard</a> |
<a href="employees.php">Employees</a> |
<a href="tasks.php">Tasks</a> |
<a href="logout.php">Logout</a>
<hr>

<h2>Employee Report</h2>
<table border="1">
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
</tr>

<?php

$result=mysqli_query($conn,"SELECT * FROM employees");

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['role']; ?></td>
</tr>

<?php

}

?>

</table>

<br><br>

<h2>Task Report</h2>

<table border="1">

<tr>
<th>Employee</th>
<th>Task</th>
<th>Priority</th>
<th>Deadline</th>
</tr>

<?php

$sql="SELECT tasks.*,employees.name
FROM tasks
INNER JOIN employees
ON tasks.employee_id=employees.id";

$result=mysqli_query($conn,$sql);
while($row=mysqli_fetch_assoc($result))
{

?>

<tr>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['task_name']; ?></td>
<td><?php echo $row['priority']; ?></td>
<td><?php echo $row['deadline']; ?></td>
</tr>

<?php

}

?>

</table>
</body>
</html>