<?php

session_start();

if(!isset($_SESSION['id']))
{
    header("Location: login.php");
    exit();
}

include("config.php");


/* ASSIGN TASK */

if($_SESSION['role']=="Manager" && isset($_POST['assign']))
{
    $employee=$_POST['employee'];
    $company=$_POST['company'];
    $task=$_POST['task'];
    $priority=$_POST['priority'];
    $deadline=$_POST['deadline'];
    $parent=$_POST['parent'];

    if($company=="")
    {
        $company="NULL";
    }

    if($parent=="")
    {
        $parent="NULL";
    }

    $sql="INSERT INTO tasks
          (employee_id,company_id,parent_task_id,task_name,priority,deadline)
          VALUES
          ('$employee',$company,$parent,'$task','$priority','$deadline')";

    mysqli_query($conn,$sql);

    header("Location: tasks.php");
    exit();
}


/* MARK TASK COMPLETE */

if(isset($_GET['complete']))
{
    $id=$_GET['complete'];

    if($_SESSION['role']=="Manager")
    {
        mysqli_query($conn,
            "UPDATE tasks SET status='Completed'
             WHERE id=$id");
    }
    else
    {
        mysqli_query($conn,
            "UPDATE tasks SET status='Completed'
             WHERE id=$id
             AND employee_id=".$_SESSION['id']);
    }

    header("Location: tasks.php");
    exit();
}


/* SHOW TASK TREE */

function showTasks($parent,$conn)
{
    if($parent==0)
    {
        $sql="SELECT tasks.*,
              employees.name AS employee_name,
              employees.user_id AS employee_user_id,
              companies.name AS company_name
              FROM tasks
              JOIN employees ON tasks.employee_id=employees.id
              LEFT JOIN companies ON tasks.company_id=companies.id
              WHERE parent_task_id IS NULL
              ORDER BY deadline";
    }
    else
    {
        $sql="SELECT tasks.*,
              employees.name AS employee_name,
              employees.user_id AS employee_user_id,
              companies.name AS company_name
              FROM tasks
              JOIN employees ON tasks.employee_id=employees.id
              LEFT JOIN companies ON tasks.company_id=companies.id
              WHERE parent_task_id=$parent
              ORDER BY deadline";
    }

    $result=mysqli_query($conn,$sql);

    while($row=mysqli_fetch_assoc($result))
    {
        echo "<div class='task'>";

        if($parent!=0)
        {
            echo "<span class='branch'>└── </span>";
        }

        echo "<b>".$row['task_name']."</b>";

        if($row['company_name']!="")
        {
            echo " - ".$row['company_name'];
        }

        echo "<br>";

        echo "Assigned to: ".$row['employee_name'].
             " (".$row['employee_user_id'].")<br>";

        echo "Priority: ".$row['priority']."<br>";

        echo "Deadline: ".$row['deadline']."<br>";


        /* OVERDUE / TODAY */

        if($row['deadline'] < date("Y-m-d") &&
           $row['status']!="Completed")
        {
            echo "<span class='overdue'>OVERDUE</span>";
        }
        elseif($row['deadline']==date("Y-m-d") &&
               $row['status']!="Completed")
        {
            echo "<span class='today'>DUE TODAY</span>";
        }


        echo "<br>";


        /* COMPLETE */

        if($row['status']!="Completed")
        {
            echo "<a href='tasks.php?complete=".$row['id']."'>
                  Mark Complete</a>";
        }
        else
        {
            echo "<span class='completed'>Completed</span>";
        }


        /* SUBTASK */

        if($_SESSION['role']=="Manager")
        {
            echo " | <a href='tasks.php?parent=".$row['id']."'>
                  Add Subtask</a>";
        }

        echo "</div>";


        /* SHOW CHILD TASKS */

        showTasks($row['id'],$conn);
    }
}

?>


<!DOCTYPE html>

<html>

<head>

<title>Tasks</title>

<link rel="stylesheet" href="style.css">

</head>


<body>

<div class="container">


<!-- HEADER -->

<div class="header">

<div>

<h1>Task Management</h1>

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


<!-- ASSIGN TASK -->

<?php

if($_SESSION['role']=="Manager")
{
?>

<div class="box">

<h2>

<?php

if(isset($_GET['parent']))
{
    echo "Add Subtask";
}
else
{
    echo "Assign New Task";
}

?>

</h2>


<form method="POST">


<label>Employee</label>

<select name="employee" required>

<?php

$result=mysqli_query($conn,
    "SELECT id,name,user_id
     FROM employees
     ORDER BY name");

while($row=mysqli_fetch_assoc($result))
{
    echo "<option value='".$row['id']."'>".
         $row['name']." (".$row['user_id'].")</option>";
}

?>

</select>


<label>Company</label>

<select name="company">

<option value="">No Company</option>

<?php

$result=mysqli_query($conn,
    "SELECT id,name
     FROM companies
     ORDER BY name");

while($row=mysqli_fetch_assoc($result))
{
    echo "<option value='".$row['id']."'>".
         $row['name']."</option>";
}

?>

</select>


<label>Task</label>

<input type="text"
       name="task"
       placeholder="Enter task name"
       required>


<label>Priority</label>

<select name="priority">

<option>High</option>

<option>Medium</option>

<option>Low</option>

</select>


<label>Deadline</label>

<input type="date"
       name="deadline"
       required>


<input type="hidden"
       name="parent"
       value="<?php
       echo isset($_GET['parent']) ? $_GET['parent'] : '';
       ?>">


<input type="submit"
       name="assign"
       value="<?php
       echo isset($_GET['parent'])
            ? 'Add Subtask'
            : 'Assign Task';
       ?>">


</form>

</div>

<?php
}

?>


<!-- TASK LIST -->

<div class="box">

<h2>Tasks & Subtasks</h2>

<?php

showTasks(0,$conn);

?>

</div>


</div>

</body>

</html>