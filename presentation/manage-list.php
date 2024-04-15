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
        
        
        <a class="btn-secondary" href="<?php echo SITEURL; ?>">Home</a>
        
        <h3>Manage Lists Page</h3>
        
        <p>
            <?php 
            
                //check if session set
                if(isset($_SESSION['add']))
                {
                    //display message
                    echo $_SESSION['add'];
                    //remove after one display
                    unset($_SESSION['add']);
                }
                
                //check for del and upd
                
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
                
                //check for del fail
                if(isset($_SESSION['delete_fail']))
                {
                    echo $_SESSION['delete_fail'];
                    unset($_SESSION['delete_fail']);
                }
            
            ?>
        </p>
        
        <!-- table for task -->
        <div class="all-lists">
            
            <a class="btn-primary" href="<?php echo SITEURL; ?>add-list.php">Add List</a>
            
            <table class="tbl-half">
                <tr>
                    <th>S.N.</th>
                    <th>List Name</th>
                    <th>Actions</th>
                </tr>
                
                
                <?php 
                
                    //conndb
                    $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
                    
                    //sel db
                    $db_select = mysqli_select_db($conn, DB_NAME) or die("Couldn't connect");
                    
                    //display all data from db
                    $sql = "SELECT * FROM tbl_lists";
                    
                    //exec 
                    $res = mysqli_query($conn, $sql);
                    
                    //CHeck whether the query executed executed successfully or not
                    if($res==true)
                    {
                        
                        //count rows
                        $count_rows = mysqli_num_rows($res);
                        
                        //create sn var
                        $sn = 1;
                        
                        //check for data
                        if($count_rows>0)
                        {                            
                            
                            while($row=mysqli_fetch_assoc($res))
                            {
                                //get data from db
                                $listid = $row['listid'];
                                $listname = $row['listname'];
                                ?>
                                
                                <tr>
                                    <td><?php echo $sn++; ?>. </td>
                                    <td><?php echo $listname; ?></td>
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
                            //no data in db
                            ?>
                            
                            <tr>
                                <td colspan="3">No List Added Yet.</td>
                            </tr>
                            
                            <?php
                        }
                    }
                
                ?>
                
                
            </table>
        </div>
        <!-- list table end -->
        </div>
    </body>
</html>