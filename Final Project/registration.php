<?php
    session_start();
    include('config.php');

    // Check if user is already logged in, if yes, redirect to To Do List page
    if(isset($_SESSION['username'])) {
        header("Location: task-manager.php");
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

        // Get username and passwords from the form
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm']; // Added for password verification

        // Verify passwords match
        if ($password !== $password_confirm) {
            $error = "Passwords do not match.";
        } else {
            // SQL injection prevention
            $username = mysqli_real_escape_string($conn, $username);
            $password = mysqli_real_escape_string($conn, $password);

            // Query to insert user into the database
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

            if(mysqli_query($conn, $sql)) {
                // Registration successful, redirect to login page
                $_SESSION['registration_success'] = "Registration successful. You can now login.";
                header("Location: index.php");
                exit;
            } else {
                // Registration failed, show error message
                $error = "Registration failed. Please try again later.";
            }
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
    <title>Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="registration-container">
        <h2>Registration</h2>
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
            <div class="form-group">
                <label for="password_confirm">Confirm Password:</label>
                <input type="password" name="password_confirm" id="password_confirm" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="index.php">Login here</a>.</p>
    </div>
</body>
</html>
