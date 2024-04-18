<?php 

    include('config.php'); 
    
    
    //Get the Current Values of Selected List
    if(isset($_GET['listid']))
    {
        //Get the List ID value
        $listid = $_GET['listid'];
        
        //Connect to Database
        $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
        
        //SElect DAtabase
        $db_select = mysqli_select_db($conn, DB_NAME) or die("Couldn't connect");
        
        //Query to Get the Values from Database
        $sql = "SELECT * FROM listtbls WHERE listid=$listid";
        
        //Execute Query
        $res = mysqli_query($conn, $sql);
        
        //CHekc whether the query executed successfully or not
        if($res==true)
        {
            //Get the Value from Database
            $row = mysqli_fetch_assoc($res); //Value is in array
            
            //printing $row array
            //print_r($row);
            
            //Create Individual Variable to save the data
            $listname = $row['listname'];
            $listdesc = $row['listdesc'];
        }
        else
        {
            //Go Back to Manage List Page
            header('location:'.SITEURL.'manage-list.php');
        }
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
        
        
            
            <a class="btn-secondary" href="<?php echo SITEURL; ?>">Home</a>
            <a class="btn-secondary" href="<?php echo SITEURL; ?>manage-list.php">Manage Lists</a>
            
       
        
        
        <h3>Update List Page</h3>
        
        <p>
            <?php 
                //Check whether the session is set or not
                if(isset($_SESSION['update_fail']))
                {
                    echo $_SESSION['update_fail'];
                    unset($_SESSION['update_fail']);
                }
            ?>
        </p>
        
        <form method="POST" action="">
        
            <table class="tbl-half">
                <tr>
                    <td>List Name: </td>
                    <td><input type="text" name="listname" value="<?php echo $listname; ?>" required="required" /></td>
                </tr>
                
                <tr>
                    <td>List Description: </td>
                    <td>
                        <textarea name="listdesc">
                            <?php echo $listdesc; ?>
                        </textarea>
                    </td>
                </tr>
                
                <tr>
                    <td><input class="btn-lg btn-primary" type="submit" name="submit" value="UPDATE" /></td>
                </tr>
            </table>
            
        </form>
        
        </div>
        
    
    </body>

</html>


<?php 

    //Check whether the Update is Clicked or Not
    if(isset($_POST['submit']))
    {
        //echo "Button Clicked";
        
        //Get the Updated Values from our Form
        $listname = $_POST['listname'];
        $listdesc = $_POST['listdesc'];
        
        //Connect Database
        $conn2 = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
        
        //SElect the Database
        $db_select2 = mysqli_select_db($conn2, DB_NAME);
        
        //query for update list
        $sql2 = "UPDATE listtbls SET 
            listname = '$listname',
            listdesc = '$listdesc' 
            WHERE listid=$listid
        ";
        
        //Execute the Query
        $res2 = mysqli_query($conn2, $sql2);
        
        //Check whether the query executed successfully or not
        if($res2==true)
        {
            //Update Successful
            //SEt the Message
            $_SESSION['update'] = "List Updated Successfully";
            
            //Redirect to Manage List PAge
            header('location:'.SITEURL.'manage-list.php');
        }
        else
        {
            //FAiled to Update
            //SEt Session Message
            $_SESSION['update_fail'] = "Failed to Update List";
            //Redirect to the Update List PAge
            header('location:'.SITEURL.'update-list.php?listid='.$listid);
        }
        
    }
?>








































