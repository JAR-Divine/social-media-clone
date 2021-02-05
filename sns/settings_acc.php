<?php 

if(isset($_POST['update_account'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

    $email_check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $row = mysqli_fetch_array($email_check);
    $matched_user = $row['username'];

    if($matched_user = "" || $matched_user == $userLoggedIn) {
        $message = "Details updated!<br>";

        $query = mysqli_query($conn, "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email' WHERE username='$userLoggedIn'");
    } else {
        $message = "Email is already in use!<br>";
    }
} else {
    $message = "";
}

    //--------------------------------------
    if(isset($_POST['change_password'])) {
        $old_password = strip_tags($_POST['old_password']);
        $new_password1 = strip_tags($_POST['new_password1']);
        $new_password2 = strip_tags($_POST['new_password2']);

        $password_query = mysqli_query($conn, "SELECT password FROM users WHERE username='$userLoggedIn'");
        $row = mysqli_fetch_array($password_query);
        $db_password = $row['password'];

        if(md5($old_password) == $db_password) {

            if($new_password1 == $new_password2) {

                if(strlen($new_password1) <=4) {
                    $password_message = "Sorry, your password must contain at least 4 characters";
                } else {
                    $new_password_md5 = md5($new_password1);
                    $password_query = mysqli_query ($conn, "UPDATE users SET password='$new_password_md5' WHERE username='$userLoggedIn'");
                    $password_message = "Password has been changed!";
                }

            } else {
                $password_message = "New Password dont match! Please Try Again!<br>";
            }
        }
    } else {
        $password_message = "Current Password is Incorrect";
    }

    //----------------------------------------------------
    if(isset($_POST['close_account'])) {
        header("Location: close_account.php");
    }
?>