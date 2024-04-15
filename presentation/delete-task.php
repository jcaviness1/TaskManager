<?php 

    include('config.php');
    
    //check what taskid is in url
    if(isset($_GET['task_id']))
    {
        //del task code
        //get id
        $task_id = $_GET['task_id'];
        
        //conn db
        $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
        
        //sel db
        $db_select = mysqli_select_db($conn, DB_NAME) or die("Couldn't connect");
        
        //query to delete task
        $sql = "DELETE FROM tbl_tasks WHERE task_id=$task_id";
        
        //exec query
        $res = mysqli_query($conn, $sql);
        
        //check if query successful or not
        if($res==true)
        {
            //disp message
            $_SESSION['delete'] = "Task Deleted Successfully.";
            
            //redirect home
            header('location:'.SITEURL);
        }
        else
        {
            //failed
            $_SESSION['delete_fail'] = "Failed to Delete Task";
            
            //redirect home
            header('location:'.SITEURL);
        }
        
    }
    else
    {
        //redirect home
        header('location:'.SITEURL);
    }

?>