<?php 
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
    
    
    <!-- menu -->
    <div class="menu">
    
        <a href="<?php echo SITEURL; ?>">Home</a>
        
        <?php 
            
            //display list
            $conn2 = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
            
            //sel db
            $db_select2 = mysqli_select_db($conn2, DB_NAME) or die("Couldn't connect");
            
            //query to get list
            $sql2 = "SELECT * FROM listtbls";
            
            //Execute Query
            $res2 = mysqli_query($conn2, $sql2);
            
            //check query true
            if($res2==true)
            {
                //disp lists from menu
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
    <!-- end menu -->
    
    <!-- THE TASKS -->
    
    <p>
        <?php 
        
            if(isset($_SESSION['add']))
            {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }
            
            if(isset($_SESSION['delete']))
            {
                echo $_SESSION['delete'];
                unset($_SESSION['delete']);
            }
            
            if(isset($_SESSION['update']))
            {
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }
            
            
            if(isset($_SESSION['delete_fail']))
            {
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
            
                //conn db
                $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
                
                //sel db
                $db_select = mysqli_select_db($conn, DB_NAME) or die("Couldn't connect");
                
                //sql for get task from db
                $sql = "SELECT * FROM tasktbl";
                
                //exec to get task
                $res = mysqli_query($conn, $sql);
                
                //check whether successful
                if($res==true)
                {
                    //get rows from db for number of task
                    
                    $count_rows = mysqli_num_rows($res);
                    
                    //create sn for task
                    $sn=1;
                    
                    //check if there are any rows in first place
                    if($count_rows>0)
                    {
                        //get data
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
                        //if no data disp message
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
    
    <!-- end task -->
    </div>
    </body>

</html>