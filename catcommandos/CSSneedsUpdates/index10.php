<?php
    // Start session
    session_start();
    include('config.php');

    // Check if user is already logged in, if yes, redirect to To Do List page
    if(isset($_SESSION['username'])) {
        header("Location: index2.php");
        exit;
    }

    // Check if the form is submitted
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Connect to the database
        $conn = mysqli_connect(LOCALHOST, DBUSER, DBPASS, DB_NAME);

        // Check connection
        if(!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Get username and password from the form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // SQL injection prevention
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        // Query to check if the user exists
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);

        // Check if a row is returned
        if(mysqli_num_rows($result) == 1) {
            // User exists, set session variables and redirect to To Do List page
            $_SESSION['username'] = $username;
            header("Location: index2.php");
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
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
</body>
</html>
