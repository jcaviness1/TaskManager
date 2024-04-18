<?php 
// Start the session
session_start();

include('config.php');

//Get the listid from URL
$listid_url = $_GET['listid'];
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
		// Connect to the database
		$conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS, DB_NAME) or die("Couldn't connect");

		// Get username from session
		$username = $_SESSION['username'];

		// Query to get isAdmin status from the database
		$sql = "SELECT admin FROM users WHERE username='$username'";
		$result = mysqli_query($conn, $sql);

		// Check if query executed successfully
		if($result) {
			// Fetch the admin status
			$row = mysqli_fetch_assoc($result);
			$isAdmin = $row['admin'];
		}
		if ($isAdmin == 1) {
			define("USER_SUFFIX", "");
		} else {
			define("USER_SUFFIX", "-user");
		}
        // Query to get lists from the database
        $sql2 = "SELECT * FROM listtbls";
        $res2 = mysqli_query($conn, $sql2);

        // Check whether the query executed or not
        if($res2) {
            // Display the lists in the menu
            while($row2 = mysqli_fetch_assoc($res2)) {
                $listid = $row2['listid'];
                $listname = $row2['listname'];
                ?>
                
                <a href="<?php echo SITEURL; ?>list-task<?php echo USER_SUFFIX; ?>.php?listid=<?php echo $listid; ?>"><?php echo $listname; ?></a>
                
                <?php
            }
        }
        ?>

        <a href="<?php echo SITEURL; ?>manage-list.php">Manage Lists</a>
    </div>
    <!-- Menu Ends Here -->


    <div class="all-task">

        <a class="btn-primary" href="<?php echo SITEURL; ?>add-task.php">Add Task</a>


        <table class="tbl-full">

            <tr>
                <th>S.N.</th>
                <th>Task Name</th>
                <th>Priority</th>
                <th>Deadline</th>
                <th>Actions</th>
            </tr>

            <?php 

            // SQL QUERY to display tasks by list selected
            $sql = "SELECT * FROM tasktbl WHERE listid=$listid_url";
            $res = mysqli_query($conn, $sql);

            if($res) {
                // Display the tasks based on the list
                // Count the Rows
                $count_rows = mysqli_num_rows($res);

                if($count_rows > 0) {
                    // We have tasks on this list
                    while($row = mysqli_fetch_assoc($res)) {
                        $task_id = $row['task_id'];
                        $task_name = $row['task_name'];
                        $priority = $row['priority'];
                        $deadline = $row['deadline'];
                        ?>

                        <tr>
                            <td>1. </td>
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
                else {
                    // NO Tasks on this list
                    ?>
                    <tr>
                        <td colspan="5">No Tasks added on this list.</td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </div>

</div>
</body>

</html>
