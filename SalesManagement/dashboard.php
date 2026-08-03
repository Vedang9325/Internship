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
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<h1>Sales Management Portal</h1>
<h3>Welcome <?php echo $_SESSION['name']; ?></h3>
<hr>
<h2>Today's Alerts</h2>

<?php
$today = date("Y-m-d");

if($_SESSION['role']=="Manager")
{
    $sql = "SELECT tasks.task_name, employees.name
            FROM tasks
            INNER JOIN employees
            ON tasks.employee_id = employees.id
            WHERE deadline='$today'";
}
else
{
    $id = $_SESSION['id'];

    $sql = "SELECT task_name
            FROM tasks
            WHERE employee_id='$id'
            AND deadline='$today'";
}

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0)
{
    while($row = mysqli_fetch_assoc($result))
    {
        if($_SESSION['role']=="Manager")
        {
            echo "<p style='color:orange;'>⚠ ".$row['name']." has '".$row['task_name']."' due today.</p>";
        }
        else
        {
            echo "<p style='color:orange;'>⚠ ".$row['task_name']." is due today.</p>";
        }
    }
}
else
{
    echo "<p>No alerts today.</p>";
}

?>

<p><strong>Role:</strong> <?php echo $_SESSION['role']; ?></p>
<hr>

<?php
if($_SESSION['role']=="Manager")
{
?>
    <h2>Manager Dashboard</h2>

    <ul>
        <li><a href="employees.php">Manage Employees</a></li>
        <li><a href="tasks.php">Manage Tasks</a></li>
        <li><a href="reports.php">View Reports</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

<?php
}
else
{
?>
    <h2>Employee Dashboard</h2>

    <ul>
        <li><a href="tasks.php">My Tasks</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

<?php
}
?>

</body>
</html>