<?php 
//Include config file for pretty much every file.
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
        
           
            if(isset($_SESSION['cantadd']))
            {
                //If adding list fails display session message for not being able to add
                echo $_SESSION['cantadd'];
                //Delete the message after displaying it once
                unset($_SESSION['cantadd']);
            }
        
        ?>
        
        </p>
        
        <!-- The form to add list -->
        
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
        
        <!-- End HTML for add-list -->
        </div>
    </body>
</html>


<?php 

    //Checks for form submission
    if(isset($_POST['submit']))
    {
        
        
        //Get values for listname and description and save them in variables
        $listname = $_POST['listname'];
        $listdesc = $_POST['listdesc'];
        
        //config connect
        $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS) or die("Couldn't connect");
        
        
        //Select the db which has the forms
        $db_select = mysqli_select_db($conn, DB_NAME);
        
        //query to insert data into the db
        $sql = "INSERT INTO listtbls SET 
            listname = '$listname',
            listdesc = '$listdesc'
        ";
        
        //run query for insert into db
        $res = mysqli_query($conn, $sql);
        
        //Check for successful data add
        if($res==true)
        {
            //if successful data add display message
            $_SESSION['add'] = "List Added Successfully";
            
            //go back to manage list page after
            header('location:'.SITEURL.'manage-list.php');
            
            
        }
        else
        {
                        
            //made session variable in case adding list fails
            $_SESSION['cantadd'] = "Failed to Add List";
            
            //go back to the add list page if adding list fails.
            header('location:'.SITEURL.'add-list.php');
        }
        
    }

?>
































