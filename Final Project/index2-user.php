<?php 
include('config.php');


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
            <button type="submit" name="logout">Logout</button>
        </form>
        
        <!-- start menu -->
        <div class="menu">
            <a href="<?php echo SITEURL; ?>">Home</a>
            <?php 
            // Connect to the database
            $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS, DB_NAME) or die("Couldn't connect");
            
            // Get username from session
            $username = $_SESSION['username'];
            
            // Query to get user ID
            $sql_user_id = "SELECT userid FROM users WHERE username='$username'";
            $result_user_id = mysqli_query($conn, $sql_user_id);
            $row_user_id = mysqli_fetch_assoc($result_user_id);
            $user_id = $row_user_id['userid'];
            
            // Query to get lists associated with the user
            $sql_lists = "SELECT DISTINCT l.listid, l.listname 
                          FROM listtbls l
                          INNER JOIN tasktbl t ON l.listid = t.listid
                          INNER JOIN usr_task_rel utr ON t.task_id = utr.task
                          WHERE utr.user = $user_id";
            $result_lists = mysqli_query($conn, $sql_lists);
            
            if($result_lists && mysqli_num_rows($result_lists) > 0) {
                // Display the lists in the menu
                while($row_lists = mysqli_fetch_assoc($result_lists)) {
                    $listid = $row_lists['listid'];
                    $listname = $row_lists['listname'];
                    ?>
                    <a href="<?php echo SITEURL; ?>list-task-user.php?listid=<?php echo $listid; ?>"><?php echo $listname; ?></a>
                    <?php
                }
            } else {
                echo "No lists available for this user.";
            }
            ?>
            <a href="<?php echo SITEURL; ?>manage-list.php">Manage Lists</a>
        </div>
        <!-- end menu -->
        
        <!-- start task -->
        <div class="all-tasks">
            <a class="btn-primary" href="<?php echo SITEURL; ?>add-task-user.php">Add Task</a>
            <table class="tbl-full">
                <tr>
                    <th>S.N.</th>
                    <th>Task Name</th>
                    <th>Priority</th>
                    <th>Deadline</th>
                    <th>Actions</th>
                </tr>
                <?php 
                // Query to get tasks associated with the user
                $sql_tasks = "SELECT t.* 
                              FROM tasktbl t 
                              INNER JOIN usr_task_rel utr ON t.task_id = utr.task 
                              WHERE utr.user = $user_id";
                $result_tasks = mysqli_query($conn, $sql_tasks);  
                
                if($result_tasks) {
                    // Display the tasks based on the user
                    $sn = 1;
                    while($row_task = mysqli_fetch_assoc($result_tasks)) {
                        $task_id = $row_task['task_id'];
                        $task_name = $row_task['task_name'];
                        $priority = $row_task['priority'];
                        $deadline = $row_task['deadline'];
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
                } else {
                    // No tasks available for this user
                    ?>
                    <tr>
                        <td colspan="5">No tasks available.</td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <!-- end task -->
    </div>
</body>
</html>
