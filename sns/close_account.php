<?php 
    include("private/header.php");

    if(isset($_POST['cancel'])) {
        header("Location: settings.php");
    }

    if(isset($_POST['close_account'])) {
        $close_query = mysqli_query($conn, "UPDATE users SET user_active='yes' WHERE username='$userLoggedIn'");
        session_destroy();
        header("Location: index.php");
    }
?>

<div class="main_column column">
    <h4>Close Account</h4>

    <p>Are you sure you want to close your account?<br>
    Closing your account will make it unavailable for all to see your profile and previous activities.<br>
    You can return by simply logging in to your account any time that you have changed your mind.
    </p>

    <form action="close_account.php" method="POST">
        <input type="submit" name="close_account" id="close_account" value="Yes, close my account.">
        <input type="submit" name="cancel" id="update_account" Value="Cancel">
    </form>
</div>