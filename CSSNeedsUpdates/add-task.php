<?php 
    include('config.php');
?>

<html>
<head>
    <title>To Do List</title>
    <link rel="stylesheet" href="<?php echo SITEURL; ?>php/add-task-style.php"/>
        <link rel="stylesheet" href="add-task-style.php">
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
                                
                                //Connect Database
                                $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
                                
                                //SElect Database
                                $db_select = mysqli_select_db($conn, DB_NAME) or die("Couldn't connect");
                                
                                //SQL query to get the list from table
                                $sql = "SELECT * FROM tbl_lists";
                                
                                //Execute Query
                                $res = mysqli_query($conn, $sql);
                                
                                //Check whether the query executed or not
                                if($res==true)
                                {
                                    //Create variable to Count Rows
                                    $count_rows = mysqli_num_rows($res);
                                    
                                    //If there is data in database then display all in dropdows else display None as option
                                    if($count_rows>0)
                                    {
                                        //display all lists on dropdown from database
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
                                        //Display None as option
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
                
                <!-- New section for selecting users -->
                <tr>
                    <td>Select User: </td>
                    <td>
                        <select name="userid">
                            <?php 
                                $sql = "SELECT * FROM users";
                                $result = mysqli_query($conn, $sql);
                                if(mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        $userid = $row['userid'];
                                        $username = $row['username'];
                                        echo "<option value='$userid'>$username</option>";
                                    }
                                }
                            ?>
                        </select>
                    </td>
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
    //Get all the Values from Form
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $listid = $_POST['listid'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];
    $userid = $_POST['userid'];
	echo "User ID is: " . $userid; 

    //Connect Database
    $conn2 = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");

    //SElect Database
    $db_select2 = mysqli_select_db($conn2, DB_NAME) or die("Couldn't connect");
	
		//CReate SQL Query to INSERT DATA into DAtabase
	$sql2 = "INSERT INTO tbl_tasks (task_name, task_description, listid, priority, deadline) 
			 VALUES ('$task_name', '$task_description', $listid, '$priority', '$deadline')";
	
	 // Execute Query
	$res2 = mysqli_query($conn2, $sql2);

	// Check if task inserted successfully
	if($res2) {
		// Get the task ID of the inserted task
		$task_id = mysqli_insert_id($conn2);

		// Insert user-task relation into usr_task_rel table
		$sql3 = "INSERT INTO usr_task_rel (user, task) VALUES ($userid, $task_id)";
		echo "SQL Query: " . $sql3 . "<br>"; // Print the SQL query
		$res3 = mysqli_query($conn2, $sql3);

		if($res3) {
			// Task and user-task relation added successfully
			$_SESSION['add'] = "Task Added Successfully.";
			header('location:'.SITEURL);
		} else {
			// Failed to add user-task relation
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