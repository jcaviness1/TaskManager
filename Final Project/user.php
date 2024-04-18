<?php 
session_start(); // Start the session

include('config.php');

// Connect to the database
$conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS, DB_NAME) or die("Couldn't connect");

// Get username from session
$username = $_SESSION['username'];

// Query to get isAdmin status from the database
$sql_admin = "SELECT admin FROM users WHERE username='$username'";
$result_admin = mysqli_query($conn, $sql_admin);

// Check if query executed successfully
if($result_admin) {
    // Fetch the admin status
    $row_admin = mysqli_fetch_assoc($result_admin);
    $isAdmin = $row_admin['admin'];
}

if ($isAdmin == 1) {
    define("USER_SUFFIX", "");
} else {
    define("USER_SUFFIX", "-user");
}
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

       <!-- Menu Starts Here -->
    <div class="menu">

        <a href="<?php echo SITEURL; ?>">Home</a>

        <?php 

        // Step 1: Get User ID
        $sql_user_id = "SELECT userid FROM users WHERE username = '$username'";
        $result_user_id = mysqli_query($conn, $sql_user_id);
        $row_user_id = mysqli_fetch_assoc($result_user_id);
        $user_id = $row_user_id['userid'];

        // Step 2: Get List IDs and Names
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
                
                <a href="<?php echo SITEURL; ?>list-task<?php echo USER_SUFFIX; ?>.php?listid=<?php echo $listid; ?>"><?php echo $listname; ?></a>
                
                <?php
            }
        } else {
            echo "No lists available for this user.";
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

            <a class="btn-primary" href="<?php SITEURL; ?>add-task-user.php">Add Task</a>

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
                    
                    // Get the logged-in user's userid
                    $userid = $_SESSION['userid'];

                    // Create SQL Query to Get Data from Database
                    $sql = "SELECT t.task_id, t.task_name, t.priority, t.deadline 
                            FROM tasktbl t 
                            INNER JOIN usr_task_rel ut ON t.task_id = ut.task
                            WHERE ut.user = $userid";
                    
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
