<?php 
    require 'private/initialize.php';
    require 'private/functions/signup.php';
    require 'private/functions/login.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome! to Buzz! || Login or Register</title>
    <link rel="stylesheet" type="text/css" href="css/style.min.css">
</head>
<body>
    <div class="container">
    <div class="login_div">
    <form class="login_form" action="index.php" method="post">
        <h1>Welcome to Buzz!</h1>
        <h2>Login to your account</h2><br>
        <input type="email" name="login_email" placeholder="Email" value="<?php 
        if(isset($_SESSION['login_email'])) {
            echo $_SESSION['login_email'];
        }
        ?>"><br>
        <input type="password" name="login_password" placeholder="Password"><br>
        <input class="log_btn" type="submit" name="Login_btn" value="Log In">

        <?php 
            if (in_array("Email or password is incorrect!<br>", $error_array)) echo "Email or password is incorrect!<br>";
        ?>
    </form>
    </div>
    
    <div class="register_div">
    <form action="index.php" method="post">
        <h1>Or Create an account today!</h1>
        <input type="text" name="reg_fname" placeholder="First Name" value="<?php 
        if (isset($_SESSION['reg_fname'])) 
        { echo $_SESSION['reg_fname'];
        };
        ?>" required>
        <br>

        <?php 
            if (in_array("First name must be between 2-25 characters.<br>", $error_array)) {
                echo "First name must be between 2-25 characters.<br>";
            }
        ?>

        <input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
        if (isset($_SESSION['reg_lname'])) {
            echo $_SESSION['reg_lname'];
        }
        ?>" required>
        <br>

        <?php 
            if (in_array("Last name must be between 2 - 25 characters.<br>", $error_array)) {
                echo "Last name must be between 2 - 25 characters.<br>";
            }
        ?>

        <input type="email" name="reg_email" placeholder="Email" value="<?php
        if (isset($_SESSION['reg_email'])) {
            echo $_SESSION['reg_email'];
        }
        ?>" required>
        <br>
        
        <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
        if (isset($_SESSION['reg_email'])) {
            echo $_SESSION['reg_email2'];
        }
        ?>" required>
        <br>

        <?php 
            if (in_array("Email is already in use.<br>", $error_array)) {
                echo "Email already in use<br>";
            }
            else if (in_array("Invalid format!<br>", $error_array)) {
                echo "Invalid format!<br>";
            }
            else if (in_array("Emails don't match<br>", $error_array)) {
                echo "Emails don't match<br>";
            }
        ?>

        <p>Gender:</p>
        <select name="reg_gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <br>

        <input type="password" name="reg_password" placeholder="Password" required>
        <br>
        <input type="password" name="reg_password2" placeholder="Confirm Password" required>
        <br>

        <?php 
            if (in_array("Passwords do not match.<br>", $error_array)) {
                echo "Passwords do not match.<br>";
            }
            else if (in_array("Password contains invalid characters.<br>", $error_array)) {
                echo "Password contains invalid characters.<br>";
            }
            else if (in_array("Password must be between 5-30 characters.<br>", $error_array)) {
                echo "Password must be between 5-30 characters.<br>";
            }
        ?>

        <input class="reg_btn" type="submit" name="Register_btn" value="Register" >
        <br>

        <?php 
            if(in_array("<span>You have successfully registered! You can now log in!</span>", $success_array)) {
                echo "<span>You have successfully registered! You can now log in!</span>";
            }
        ?>
    </form>
    </div>

    </div>
</body>
</html>