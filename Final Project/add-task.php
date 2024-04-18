<?php 
//backup page mostly scrap
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
                                
                                
                                $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
                                
                                
                                $db_select = mysqli_select_db($conn, DB_NAME) or die("Couldn't connect");
                                
                                
                                $sql = "SELECT * FROM listtbls";
                                
                                
                                $res = mysqli_query($conn, $sql);
                                
                                
                                if($res==true)
                                {
                                    
                                    $count_rows = mysqli_num_rows($res);
                                    
                                    
                                    if($count_rows>0)
                                    {
                                        
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
                
                <!-- admin functionality only. -->
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

    //conn bb
    $conn2 = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");

    //sel db
    $db_select2 = mysqli_select_db($conn2, DB_NAME) or die("Couldn't connect");
	
		//same as add task user
	$sql2 = "INSERT INTO tasktbl (task_name, task_description, listid, priority, deadline) 
			 VALUES ('$task_name', '$task_description', $listid, '$priority', '$deadline')";
	
	 
	$res2 = mysqli_query($conn2, $sql2);

	
	if($res2) {
		
		$task_id = mysqli_insert_id($conn2);

		
		$sql3 = "INSERT INTO usr_task_rel (user, task) VALUES ($userid, $task_id)";
		echo "SQL Query: " . $sql3 . "<br>"; 
		$res3 = mysqli_query($conn2, $sql3);

		if($res3) {
			
			$_SESSION['add'] = "Task Added Successfully.";
			header('location:'.SITEURL);
		} else {
			
			$_SESSION['cantadd'] = "Failed to Add Task";
			header('location:'.SITEURL.'add-task.php');
		}
	} else {
		
		$_SESSION['cantadd'] = "Failed to Add Task";
		header('location:'.SITEURL.'add-task.php');
}
}
?>