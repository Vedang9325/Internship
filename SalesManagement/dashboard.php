<?php

session_start();

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
    exit();
}

include("config.php");


$today=date("Y-m-d");


/* GET TASK ALERTS */

if($_SESSION['role']=="Manager")
{
    $sql="SELECT tasks.*,
          employees.name AS employee_name,
          employees.user_id AS employee_user_id
          FROM tasks
          JOIN employees ON tasks.employee_id=employees.id
          WHERE deadline <= '$today'
          AND status!='Completed'
          ORDER BY deadline";
}
else
{
    $id=$_SESSION['id'];

    $sql="SELECT *
          FROM tasks
          WHERE employee_id='$id'
          AND deadline <= '$today'
          AND status!='Completed'
          ORDER BY deadline";
}

$result=mysqli_query($conn,$sql);

?>


<!DOCTYPE html>

<html>

<head>

<title>Dashboard</title>

<link rel="stylesheet" href="style.css">

</head>


<body>

<div class="container">


<!-- HEADER -->

<div class="header">

<div>

<h1>Sales Management Portal</h1>

<p>

Welcome,
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


<?php

if($_SESSION['role']=="Manager")
{
?>

<a href="employees.php">Employees</a>

<a href="companies.php">Companies</a>

<a href="reports.php">Reports</a>

<?php
}

?>

</nav>


<!-- TASK ALERTS -->

<div class="box">

<h2>Task Alerts</h2>


<?php

if(mysqli_num_rows($result)==0)
{
    echo "<p>No overdue or due-today tasks.</p>";
}


while($row=mysqli_fetch_assoc($result))
{

    if($row['deadline'] < $today)
    {
        echo "<div class='alert overdue'>";
        echo "⚠ OVERDUE: ";
    }
    else
    {
        echo "<div class='alert today'>";
        echo "⚠ DUE TODAY: ";
    }


    echo "<b>".$row['task_name']."</b>";


    if($_SESSION['role']=="Manager")
    {
        echo " - ".$row['employee_name'].
             " (".$row['employee_user_id'].")";
    }


    echo " (".$row['deadline'].")";

    echo "</div>";
}

?>

</div>


<!-- DASHBOARD -->

<div class="box">

<h2>

<?php

if($_SESSION['role']=="Manager")
{
    echo "Manager Dashboard";
}
else
{
    echo "Employee Dashboard";
}

?>

</h2>


<div class="cards">


<a href="tasks.php">

<b>Tasks</b>

<br>

Manage Tasks

</a>


<?php

if($_SESSION['role']=="Manager")
{
?>

<a href="employees.php">

<b>Employees</b>

<br>

Manage Employees

</a>


<a href="companies.php">

<b>Companies</b>

<br>

Manage Companies

</a>


<a href="reports.php">

<b>Reports</b>

<br>

View Reports

</a>

<?php
}

?>


</div>

</div>


</div>

</body>

</html>