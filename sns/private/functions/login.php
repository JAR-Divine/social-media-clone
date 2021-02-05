<?php 
if(isset($_POST['Login_btn']))
{
    $email = filter_var($_POST['login_email'], FILTER_SANITIZE_EMAIL); //sanitize email

    $_SESSION['login_email'] = $email; //store into session variable
    $password = md5($_POST['login_password']); //Get password
    
    $check_database_query = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND password='$password'");
    $check_login_query = mysqli_num_rows($check_database_query);

    if($check_login_query == 1) {
        $row = mysqli_fetch_array($check_database_query);
        $username = $row['username'];

        $user_active_query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND user_active='yes'");
        if(mysqli_num_rows($user_active_query) == 1) {
            $reopen_account = mysqli_query($conn, "UPDATE users SET user_active='no' WHERE email='$email'");
        }

        $_SESSION['username'] = $username;
        header("Location:dashboard.php");
        exit();
    }
    else {
        array_push ($error_array, "Email or password is incorrect!<br>");
    }
}
?>