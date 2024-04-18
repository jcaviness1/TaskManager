<?php 
//config
    include('config.php');
?>

<html>
<head>
    <title>To Do List</title>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>style.css" />
</head>

<body>

    <div class="wrapper">
    
        <h1>To Do List</h1>
        
        <a class="btn-secondary" href="<?php echo SITEURL; ?>">Home</a>
        
        <h3>Add Task Page</h3>
        
        <p>
            <?php 
            
                if(isset($_SESSION['cantadd']))
                {
                    echo $_SESSION['cantadd'];
                    unset($_SESSION['cantadd']);
                }
            
            ?>
        </p>
        
        <form method="POST" action="">
            
            <table class="tbl-half">
                <tr>
                    <td>Task Name: </td>
                    <td><input type="text" name="task_name" placeholder="Type your Task Name" required="required" /></td>
                </tr>
                
                <tr>
                    <td>Task Description: </td>
                    <td><textarea name="task_description" placeholder="Type Task Description"></textarea></td>
                </tr>
                
                <tr>
                    <td>Select List: </td>
                    <td>
                        <select name="listid">
                            
                            <?php 
                                
                                //conn db
                                $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
                                
                                //sel db
                                $db_select = mysqli_select_db($conn, DB_NAME) or die("Couldn't connect");
                                
                                //sql query get list from table
                                $sql = "SELECT * FROM listtbls";
                                
                                //exec query
                                $res = mysqli_query($conn, $sql);
                                
                                //check if query exec
                                if($res==true)
                                {
                                    //count row variable
                                    $count_rows = mysqli_num_rows($res);
                                    
                                    //if data in db display if not display none as option
                                    if($count_rows>0)
                                    {
                                        //display all the list as a dropdown
                                        while($row=mysqli_fetch_assoc($res))
                                        {
                                            $listid = $row['listid'];
                                            $listname = $row['listname'];
                                            ?>
                                            <option value="<?php echo $listid ?>"><?php echo $listname; ?></option>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        //disp none
                                        ?>
                                        <option value="0">None</option>
                                        <?php
                                    }
                                    
                                }
                            ?>
                        
                            
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td>Priority: </td>
                    <td>
                        <select name="priority">
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td>Deadline: </td>
                    <td><input type="date" name="deadline" /></td>
                </tr>
                
                              
                <tr>
                    <td><input class="btn-primary btn-lg" type="submit" name="submit" value="SAVE" /></td>
                </tr>
                
            </table>
            
        </form>
        
    </div>
</body>
    
</html>

<?php
if(isset($_POST['submit']))
{
    //get vals from form
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $listid = $_POST['listid'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];
    $userid = $_POST['userid'];
	echo "User ID is: " . $userid; 

    //conn db
    $conn2 = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");

    //sel db
    $db_select2 = mysqli_select_db($conn2, DB_NAME) or die("Couldn't connect");
	
		//sql query to insert into db
	$sql2 = "INSERT INTO tasktbl (task_name, task_description, listid, priority, deadline) 
			 VALUES ('$task_name', '$task_description', $listid, '$priority', '$deadline')";
	
	 // exec query
	$res2 = mysqli_query($conn2, $sql2);

	// check if inserted successfully
	if($res2) {
		//get taskid for inserted task
		$task_id = mysqli_insert_id($conn2);

		// relate user with task
		$sql3 = "INSERT INTO usr_task_rel (user, task) VALUES ($userid, $task_id)";
		echo "SQL Query: " . $sql3 . "<br>"; 
		$res3 = mysqli_query($conn2, $sql3);

		if($res3) {
			// if task and user related successfully
			$_SESSION['add'] = "Task Added Successfully.";
			header('location:'.SITEURL);
		} else {
			//failed to add user task
			$_SESSION['cantadd'] = "Failed to Add Task";
			header('location:'.SITEURL.'add-task.php');
		}
	} else {
		// Failed to add task
		$_SESSION['cantadd'] = "Failed to Add Task";
		header('location:'.SITEURL.'add-task.php');
}
}
?>