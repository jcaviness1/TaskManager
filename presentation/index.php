<?php
    include('config.php');
	  //check if session is active if not start
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); // Start session
    }
    //SCRAP IGNORE
    // Check if user is already logged in, if yes, redirect to To Do List page
    //if(isset($_SESSION['username'])) {
    //    header("Location: index2.php");
    //    exit;
    //}
	
	// if user already logged in connect to db
if(isset($_SESSION['username'])) {
    
    $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS, DB_NAME);

    // check connection
    if(!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // get username
    $username = $_SESSION['username'];

    // if admin check for admin 
    $sql = "SELECT admin FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    // check exec successful
    if($result) {
        //get admin status
        $row = mysqli_fetch_assoc($result);
        $isAdmin = $row['admin'];

        // ifadmin
        if ($isAdmin == 1) {
            header("Location: index2.php");
            exit;
        } else {
            header("Location: index2-user.php");
            exit;
        }
    } else {
        // error
        echo "Error: " . mysqli_error($conn);
    }

    // close conn
    mysqli_close($conn);
}


    // check if form is submitted
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        // conn db
        $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS, DB_NAME);

        // check conn
        if(!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Get username and password from the form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // prevent sql injection
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        // see if user exists
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);

        // Check if a row is returned
        if(mysqli_num_rows($result) == 1) {
            // User exists, fetch additional information
            $row = mysqli_fetch_assoc($result);
            $isAdmin = $row['admin'];
            
            // Set session variable for username
            $_SESSION['username'] = $username;
            
            // Set session variable for userid
            $_SESSION['userid'] = $row['userid'];

            // Redirect based on admin status
            if ($isAdmin == 1) {
                header("Location: admin.php");
            } else {
                header("Location: user.php");
            }
            exit;
        } else {
            // Invalid credentials, show error message
            $error = "Invalid username or password";
        }

        // Close connection
        mysqli_close($conn);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login-style.css">
</head>
<body>
    <div class="main-container">
        <h1>To-Do List</h1> 
        <div class="login-container">
            <h2>Login</h2>
            <?php if(isset($error)) { ?>
                <p class="error"><?php echo $error; ?></p>
            <?php } ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="registration.php">Register here</a>.</p>
        </div>
    </div>
</body>
</html>

