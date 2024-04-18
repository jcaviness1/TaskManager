<?php 
        //Include config.php
    include('config.php');
    //echo "Delete List Page";
    
    //check if list has listid
    
    if(isset($_GET['listid']))
    {
        //delete list from db
        
        //get listid from url method
        $listid = $_GET['listid'];
        
        //conn db
        $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
        
        //sel db
        $db_select = mysqli_select_db($conn, DB_NAME) or die("Couldn't connect");
        
        //query to del list from db
        $sql = "DELETE FROM listtbls WHERE listid=$listid";
        
        //exec query
        $res = mysqli_query($conn, $sql);
        
        //check if successfully deleted
        if($res==true)
        {
            //disp message
            $_SESSION['delete'] = "List Deleted Successfully";
            
            //go back to manage list page
            header('location:'.SITEURL.'manage-list.php');
        }
        else
        {
            //failed to del list
            $_SESSION['delete_fail'] = "Failed to Delete List.";
            header('location:'.SITEURL.'manage-list.php');
        }
    }
    else
    {
        //redirect to Manage List Page
        header('location:'.SITEURL.'manage-list.php');
    }
    

    
    
    
?>