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
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
					
background: linear-gradient(145deg, #007ea2, #0096c1);
box-shadow:  15px 15px 6px #0085ab,
             -15px -15px 6px #0093bd;
           /* background-color: #00c7fc;*/ /*background for wrapper*/
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .form-group p {
            margin-top: 10px;
            font-size: 14px;
            text-align: center;
        }

        .form-group p a {
            color: #007bff ; /**/
            text-decoration: none;
        }

        .form-group p a:hover {
            text-decoration: underline;
        }

        .error {
            color: #dc3545;
            margin-bottom: 10px;
        }
			
    </style>
</head>
<body>
	<div class="gif-background">
</div>
	<section class="bubble">
      <!-- content here -->
    </section>
    <div class="login-wrapper">
        <div class="login-container">
            <h2>Login</h2>
            <?php if(isset($error)) { ?>
                <p class="error"><?php echo $error; ?></p>
            <?php } ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <button type="submit">Login</button>
                </div>
            </form>
            <p>Don't have an account? <a href="registration.php">Register here</a>.</p>
        </div>
    </div>
</body>
</html>

