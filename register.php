<?php
// Include the database connection file
require_once 'db_connection.php';

// Initialize variables to store user input
$username = $password = $confirm_password = $mobile = "";
$username_err = $password_err = $confirm_password_err = $mobile_err = "";

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Prepare a select statement to check if the username already exists
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate mobile
    if (empty(trim($_POST['mobile']))) {
        $mobile_err = "Please enter a mobile number.";
    } else {
        $mobile = trim($_POST['mobile']);
    }

    // Check input errors before inserting into database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($mobile_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, mobile) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_username, $param_password, $param_mobile);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_mobile = $mobile;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to welcome page after successful registration
                header("location: index.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <style>
        * {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "segoe ui", roboto, oxygen, ubuntu, cantarell, "fira sans", "droid sans", "helvetica neue", Arial, sans-serif;
            font-size: 16px;
        }

        body {
            background-color: #435165;
        }

        .login {
            width: 400px;
            background-color: #ffffff;
            box-shadow: 0 0 9px 0 rgba(0, 0, 0, 0.3);
            margin: 100px auto;
        }

        .login h1 {
            text-align: center;
            color: #5b6574;
            font-size: 24px;
            padding: 20px 0 20px 0;
            border-bottom: 1px solid #dee0e4;
        }

        .login form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding-top: 20px;
        }

        .login form label {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50px;
            height: 50px;
            background-color: #3274d6;
            color: #ffffff;
        }

        .login form input[type="password"],
        .login form input[type="text"] {
            width: 310px;
            height: 50px;
            border: 1px solid #dee0e4;
            margin-bottom: 20px;
            padding: 0 15px;
        }

        .login form input[type="submit"] {
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            background-color: #3274d6;
            border: 0;
            cursor: pointer;
            font-weight: bold;
            color: #ffffff;
            transition: background-color 0.2s;
        }

        .login form input[type="submit"]:hover {
            background-color: #2868c7;
            transition: background-color 0.2s;
        }

        /* Eye icon for password visibility toggle */
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="login">
        <h1>Register</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="username" placeholder="Username" id="username" value="<?php echo $username; ?>" required>
            <span class="help-block"><?php echo $username_err; ?></span>
            <label for="password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="password" placeholder="Password" id="password" value="<?php echo $password; ?>" required>
            <span class="help-block"><?php echo $password_err; ?></span>
            <label for="confirm_password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" value="<?php echo $confirm_password; ?>" required>
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
            <label for="mobile">
                <i class="fas fa-mobile-alt"></i>
            </label>
            <input type="text" name="mobile" placeholder="Mobile Number" id="mobile" value="<?php echo $mobile; ?>" required>
            <span class="help-block"><?php echo $mobile_err; ?></span>
            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="index.html">Login here</a></p>
    </div>
</body>
</html>



