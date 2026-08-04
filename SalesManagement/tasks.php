<?php
session_start();

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
    exit();
}

include("config.php");

$isManager = ($_SESSION['role'] == "Manager");

// Add Task
if($isManager && isset($_POST['assign']))
{
    $employee = $_POST['employee'];
    $task = $_POST['task'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];

    $sql = "INSERT INTO tasks(employee_id,task_name,priority,deadline)
            VALUES('$employee','$task','$priority','$deadline')";

    mysqli_query($conn,$sql);

    header("Location: tasks.php");
    exit();
}

// Delete Task
if($isManager && isset($_GET['delete']))
{
    $id = $_GET['delete'];

    mysqli_query($conn,"DELETE FROM tasks WHERE id=$id");

    header("Location: tasks.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tasks</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h1>Task Management</h1>

<a href="dashboard.php">Dashboard</a> |
<a href="employees.php">Employees</a> |
<a href="reports.php">Reports</a> |
<a href="logout.php">Logout</a>

<hr>

<?php if($isManager) { ?>

<h2>Assign Task</h2>

<form method="POST">

<label>Employee</label><br>

<select name="employee">

<?php

$result = mysqli_query($conn,"SELECT * FROM employees");

while($emp = mysqli_fetch_assoc($result))
{
?>

<option value="<?php echo $emp['id']; ?>">
    <?php echo $emp['name']; ?>
</option>

<?php
}
?>

</select>

<br><br>

<label>Task</label><br>
<input type="text" name="task" required>

<br><br>

<label>Priority</label><br>

<select name="priority">
    <option>High</option>
    <option>Medium</option>
    <option>Low</option>
</select>

<br><br>

<label>Deadline</label><br>
<input type="date" name="deadline" required>

<br><br>

<input type="submit" name="assign" value="Assign Task">

</form>

<hr>

<?php } ?>

<h2>Tasks</h2>

<table border="1" cellpadding="10">

<tr>
    <th>Employee</th>
    <th>Task</th>
    <th>Priority</th>
    <th>Deadline</th>
    <th>Status</th>

    <?php if($isManager) { ?>
        <th>Delete</th>
    <?php } ?>

</tr>

<?php

if($isManager)
{
    $sql = "SELECT tasks.*, employees.name
            FROM tasks
            INNER JOIN employees
            ON tasks.employee_id = employees.id";
}
else
{
    $id = $_SESSION['id'];

    $sql = "SELECT tasks.*, employees.name
            FROM tasks
            INNER JOIN employees
            ON tasks.employee_id = employees.id
            WHERE employee_id='$id'";
}

$result = mysqli_query($conn,$sql);

while($row = mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['task_name']; ?></td>

<td><?php echo $row['priority']; ?></td>

<td><?php echo $row['deadline']; ?></td>

<td>

<?php

$today = date("Y-m-d");

if($row['deadline'] < $today)
{
    echo "Overdue";
}
elseif($row['deadline'] == $today)
{
    echo "Due Today";
}
else
{
    echo "Pending";
}

?>

</td>

<?php if($isManager) { ?>

<td>
<a href="tasks.php?delete=<?php echo $row['id']; ?>">Delete</a>
</td>

<?php } ?>

</tr>

<?php
}
?>

</table>

</body>
</html>