  <?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to dashboard.
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard");
    exit;
}

// Include config file
require_once "includes/config.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if username is empty
    if(empty(trim($_POST["email"]))){
        $username_err = "Please enter your email.";
    } else{
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT user_id, email, user_pass FROM `users` WHERE `email` = '$email'";
        $query = mysqli_query($link, $sql);
        $result=mysqli_fetch_array($query);

        if($result > 0){
        if ($password == $result['user_pass']) {

            // Store data in session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $result['user_id'];
            $_SESSION['full_name'] = $result['fname'].' '.$result['lname'];
            // Redirect user to welcome page
            header("location: dashboard.php");
        } else{
            // Display an error message if password is not valid
            $password_err = "The password you entered was not valid.";
        }
    }
    else{
        $password_err = "Account does not exists";
    }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login Form</title>
        <?php
        include_once 'includes/header.php'
        ?>

        <!-- Custom css -->
        <link rel="stylesheet" href="css/stylelog.css">
    </head>
    <style>

    </style>
    <body style="background-image: url('login.jpg');-webkit-background-size:cover;">

        <!-- Navbar starts -->
        <?php
        include_once 'includes/loginregisternavbar.php'; ?>
        <!-- Navbar ends -->

        <div class="wrapper">
            <div class="inner-card">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h2>Login</h2>

                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <input type="email" name="email" class="form-control" placeholder="Username" required value="<?php echo htmlspecialchars($email);?>">
                        <p class="help-block text-danger mb-1 text-center"><?php echo $email_err; ?></p>
                    </div>

                    <div class="input-group mb-3 <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <input type="password" required name="password" id="inputPassword" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                        <span class="input-group-text"><i  id="hideme" class="fa fa-eye-slash" onclick="showLoginPassword('hideme', 'inputPassword')"></i></span>
                        </div>
                    </div>
                    <p class="help-block text-danger mb-1 text-center"><?php echo $password_err; ?></p>                    <button type="submit" class="btn btn-dark">Login</button>
                                    <a href="register.php" class="text-dark">Dont have an account ? Register Here</a>

                </form>
            </div>
        </div>
    </body>
</html>
