<?php 
include("private/header.php");
include("settings_acc.php");
?>

<div class="main_column column">
<h4>Account Settings</h4>
<?php 
echo "<img src='" . $user['profile_img'] ."' id='settings_user_img'>";
?>
<br>
Change Account Information

<?php 
    $user_data_query = mysqli_query($conn, "SELECT first_name, last_name, email FROM users WHERE username='$userLoggedIn'");
    $row = mysqli_fetch_array($user_data_query);

    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $email = $row['email'];
?>

<form action="settings.php" method="POST">
    First Name: <input type="text" name="first_name" value="<?php echo $first_name; ?>"><br>
    Last Name: <input type="text" name="last_name" value="<?php echo $last_name ?>"><br>
    Email: <input type="text" name="email" value="<?php echo $email; ?>"><br>

    <?php echo $message; ?>

    <input type="submit" name="update_account" id="update_account"value="Update Account"><br>

    <h4>Change Password</h4>
    Current Password: <input type="password" name="old_password"><br>
    New Password: <input type="password" name="new_password1"><br>
    Confirm New Password: <input type="password" name="new_password2"><br>
    <input type="submit" name="change_password" id="change_password"value="Change Password"><br>

    <?php echo $password_message; ?>
</form>

<h4>Close Account</h4>
<form action="settings.php" method="POST">
    <input type="submit" name="close_account" id="close_account"value="Close Account">
</form>


</div>