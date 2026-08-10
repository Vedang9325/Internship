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

?>


<!DOCTYPE html>

<html>

<head>

<title>Reports</title>

<link rel="stylesheet" href="style.css">

</head>


<body>

<div class="container">


<!-- HEADER -->

<div class="header">

<div>

<h1>Reports</h1>

<p>

Logged in as
<b><?php echo $_SESSION['name']; ?></b>

<span class="user-id">
<?php echo $_SESSION['user_id']; ?>
</span>

</p>

</div>

<a href="logout.php">Logout</a>

</div>


<!-- NAVIGATION -->

<nav>

<a href="dashboard.php">Dashboard</a>

<a href="tasks.php">Tasks</a>

<a href="employees.php">Employees</a>

<a href="companies.php">Companies</a>

<a href="reports.php">Reports</a>

</nav>


<!-- EMPLOYEE REPORT -->

<div class="box">

<h2>Employee Report</h2>


<table>

<tr>

<th>User ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>

</tr>


<?php

$result=mysqli_query($conn,
    "SELECT user_id,name,email,role
     FROM employees
     ORDER BY name");


while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td>
<?php echo $row['user_id']; ?>
</td>

<td>
<?php echo $row['name']; ?>
</td>

<td>
<?php echo $row['email']; ?>
</td>

<td>
<?php echo $row['role']; ?>
</td>

</tr>

<?php

}

?>

</table>

</div>


<!-- TASK REPORT -->

<div class="box">

<h2>Task Report</h2>


<table>

<tr>

<th>Employee</th>
<th>User ID</th>
<th>Company</th>
<th>Task</th>
<th>Priority</th>
<th>Deadline</th>
<th>Status</th>

</tr>


<?php

$sql="SELECT tasks.*,
      employees.name AS employee_name,
      employees.user_id AS employee_user_id,
      companies.name AS company_name
      FROM tasks
      JOIN employees ON tasks.employee_id=employees.id
      LEFT JOIN companies ON tasks.company_id=companies.id
      ORDER BY deadline";


$result=mysqli_query($conn,$sql);


while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td>
<?php echo $row['employee_name']; ?>
</td>

<td>
<?php echo $row['employee_user_id']; ?>
</td>

<td>

<?php

if($row['company_name']=="")
{
    echo "None";
}
else
{
    echo $row['company_name'];
}

?>

</td>

<td>
<?php echo $row['task_name']; ?>
</td>

<td>
<?php echo $row['priority']; ?>
</td>

<td>
<?php echo $row['deadline']; ?>
</td>

<td>
<?php echo $row['status']; ?>
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