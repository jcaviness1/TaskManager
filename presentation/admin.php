<?php 
//ADMIN PAGE STAY AWAY
    include('config.php');
	  // Check if session is not active
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); // Start session
    }

    // Logout logic
    if(isset($_POST['logout'])) {
        // Unset all session variables
        $_SESSION = array();
        // Destroy the session
        session_destroy();
        // Redirect to the index page
        header("Location: index.php");
        exit;
    }
?>

<html>

    <head>
        <title>To Do List</title>
        <link rel="stylesheet" href="<?php echo SITEURL; ?>style.css" />
    </head>
    
    <body>
    
    <div class="wrapper">
    
    <h1>To Do List</h1>
    
    <!-- Logout Button -->
<form method="post" style="text-align: right;">
    <button type="submit" name="logout" class="btn-primary">Logout</button>
</form>

    
    <!-- Menu Starts Here -->
    <div class="menu">
    
        <a href="<?php echo SITEURL; ?>">Home</a>
        
        <?php 
            // Displaying Lists From Database in our Menu
            $conn2 = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
            
            // SELECT DATABASE
            $db_select2 = mysqli_select_db($conn2, DB_NAME) or die("Couldn't connect");
            
            // Query to Get the Lists from database
            $sql2 = "SELECT * FROM tbl_lists";
            
            // Execute Query
            $res2 = mysqli_query($conn2, $sql2);
            
            // Check whether the query executed or not
            if($res2==true)
            {
                // Display the lists in menu
                while($row2=mysqli_fetch_assoc($res2))
                {
                    $listid = $row2['listid'];
                    $listname = $row2['listname'];
                    ?>
                    
                    <a href="<?php echo SITEURL; ?>list-task.php?listid=<?php echo $listid; ?>"><?php echo $listname; ?></a>
                    
                    <?php
                    
                }
            }
        ?>
        
        <a href="<?php echo SITEURL; ?>manage-list.php">Manage Lists</a>
    </div>
    <!-- Menu Ends Here -->
    
    <!-- Tasks Starts Here -->
    
    <p>
        <?php 
            if(isset($_SESSION['add'])) {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }
            
            if(isset($_SESSION['delete'])) {
                echo $_SESSION['delete'];
                unset($_SESSION['delete']);
            }
            
            if(isset($_SESSION['update'])) {
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }
            
            if(isset($_SESSION['delete_fail'])) {
                echo $_SESSION['delete_fail'];
                unset($_SESSION['delete_fail']);
            }
        ?>
    </p>
    
    <div class="all-tasks">
        
        <a class="btn-primary" href="<?php SITEURL; ?>add-task.php">Add Task</a>
        
        <table class="tbl-full">
        
            <tr>
                <th>S.N.</th>
                <th>Task Name</th>
                <th>Priority</th>
                <th>Deadline</th>
                <th>Actions</th>
            </tr>
            
            <?php 
                // Connect Database
                $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
                
                // Select Database
                $db_select = mysqli_select_db($conn, DB_NAME) or die("Couldn't connect");
                
                // Create SQL Query to Get Data from Database
                $sql = "SELECT * FROM tbl_tasks";
                
                // Execute Query
                $res = mysqli_query($conn, $sql);
                
                // Check whether the query executed or not
                if($res==true)
                {
                    // Display the Tasks from Database
                    // Count the Tasks on Database first
                    $count_rows = mysqli_num_rows($res);
                    
                    // Create Serial Number Variable
                    $sn=1;
                    
                    // Check whether there is task on database or not
                    if($count_rows>0)
                    {
                        // Data is in Database
                        while($row=mysqli_fetch_assoc($res))
                        {
                            $task_id = $row['task_id'];
                            $task_name = $row['task_name'];
                            $priority = $row['priority'];
                            $deadline = $row['deadline'];
                            ?>
                            
                            <tr>
                                <td><?php echo $sn++; ?>. </td>
                                <td><?php echo $task_name; ?></td>
                                <td><?php echo $priority; ?></td>
                                <td><?php echo $deadline; ?></td>
                                <td>
                                <a class="action-button" href="<?php echo SITEURL; ?>update-task.php?task_id=<?php echo $task_id; ?>">
    <img src="update_icon.png" alt="Update">
</a>
<a class="action-button" href="<?php echo SITEURL; ?>delete-task.php?task_id=<?php echo $task_id; ?>">
    <img src="delete_icon.png" alt="Delete">
</a>
                                
                                </td>
                            </tr>
                            
                            <?php
                        }
                    }
                    else
                    {
                        // No data in Database
                        ?>
                        
                        <tr>
                            <td colspan="5">No Task Added Yet.</td>
                        </tr>
                        
                        <?php
                    }
                }
            ?>
            
        </table>
    
    </div>
    
    <!-- Tasks Ends Here -->
    </div>
    </body>

</html>
