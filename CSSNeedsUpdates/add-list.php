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
        <a class="btn-secondary" href="<?php echo SITEURL; ?>manage-list.php">Manage Lists</a>
        
        
        <h3>Add List Page</h3>
        
        <p>
        
        <?php 
        
            //Check whether the session is created or not
            if(isset($_SESSION['cantadd']))
            {
                //display session message
                echo $_SESSION['cantadd'];
                //Remove the message after displaying once
                unset($_SESSION['cantadd']);
            }
        
        ?>
        
        </p>
        
        <!-- Form to Add List Starts Here -->
        
        <form method="POST" action="">
            
            <table class="tbl-half">
                <tr>
                    <td>List Name: </td>
                    <td><input type="text" name="listname" placeholder="Type list name here" required="required" /></td>
                </tr>
                <tr>
                    <td>List Description: </td>
                    <td><textarea name="listdesc" placeholder="Type List Description Here"></textarea></td>
                </tr>
                
                <tr>
                    <td><input class="btn-primary btn-lg" type="submit" name="submit" value="SAVE" /></td>
                </tr>
                
            </table>
            
        </form>
        
        <!-- Form to Add List Ends Here -->
        </div>
    </body>
</html>


<?php 

    //Check whether the form is submitted or not
    if(isset($_POST['submit']))
    {
        //echo "Form Submitted";
        
        //Get the values from form and save it in variables
        $listname = $_POST['listname'];
        $listdesc = $_POST['listdesc'];
        
        //Connect Database
        $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
        
        //Check whether the database connected or not
        /*
        if($conn==true)
        {
            echo "Database Connected";
        }
        */
        
        //SElect Database
        $db_select = mysqli_select_db($conn, DB_NAME);
        
        //Check whether database is connected or not
        /*
        if($db_select==true)
        {
            echo "Database SElected";
        }
        */
        //SQL Query to Insert data into database
        $sql = "INSERT INTO tbl_lists SET 
            listname = '$listname',
            listdesc = '$listdesc'
        ";
        
        //Execute Query and Insert into Database
        $res = mysqli_query($conn, $sql);
        
        //Check whether the query executed successfully or not
        if($res==true)
        {
            //Data inserted successfully
            //echo "Data Inserted";
            
            //Create a SESSION VAriable to Display message
            $_SESSION['add'] = "List Added Successfully";
            
            //Redirect to Manage List Page
            header('location:'.SITEURL.'manage-list.php');
            
            
        }
        else
        {
            //Failed to insert data
            //echo "Failed to Insert Data";
            
            //Create SEssion to save message
            $_SESSION['cantadd'] = "Failed to Add List";
            
            //REdirect to Same Page
            header('location:'.SITEURL.'add-list.php');
        }
        
    }

?>
































