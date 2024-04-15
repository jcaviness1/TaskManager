<?php 
        //Include config.php
    include('config.php');
    //echo "Delete List Page";
    
    //Check whether the listid is assigned or not
    
    if(isset($_GET['listid']))
    {
        //Delete the List from database
        
        //Get the listid value from URL or Get Method
        $listid = $_GET['listid'];
        
        //Connect the DAtabase
        $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
        
        //SElect Database
        $db_select = mysqli_select_db($conn, DB_NAME) or die("Couldn't connect");
        
        //Write the Query to DELETE List from DAtabase
        $sql = "DELETE FROM tbl_lists WHERE listid=$listid";
        
        //Execute The Query
        $res = mysqli_query($conn, $sql);
        
        //Check whether the query executed successfully or not
        if($res==true)
        {
            //Query Executed Successfully which means list is deleted successfully
            $_SESSION['delete'] = "List Deleted Successfully";
            
            //Redirect to Manage List Page
            header('location:'.SITEURL.'manage-list.php');
        }
        else
        {
            //Failed to Delete List
            $_SESSION['delete_fail'] = "Failed to Delete List.";
            header('location:'.SITEURL.'manage-list.php');
        }
    }
    else
    {
        //Redirect to Manage List Page
        header('location:'.SITEURL.'manage-list.php');
    }
    

    
    
    
?>