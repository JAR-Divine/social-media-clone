<?php 
    require 'initialize.php';
    include("private/classes/User.php");
    include("private/classes/Post.php");

    //check if a user is logged in
    if(isset($_SESSION['username'])) {
        $userLoggedIn = $_SESSION['username'];
        $user_info_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$userLoggedIn'");
        $user = mysqli_fetch_array($user_info_query);
    } else { //if no user is logged in redirect to login/register page
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- javascript -->
    <script src="public/assets/js/jquery-3.5.1.min.js"></script>
    <script src="public/assets/js/bootstrap.js"></script>
    <script src="public/assets/js/bootbox.min.js"></script>
    <script src="public/assets/js/socmed.js"></script>
    <script src="https://kit.fontawesome.com/4738ea9f87.js" crossorigin="anonymous"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.min.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    
</head>
<body>
<nav class="navbar">

  <div class="header_bar">
    <div class="Logo">
        <a href="dashboard.php">Buzz</a>
    </div>

    <div class="navigation">
    <nav class="header_nav">
    <a href="<?php echo $userLoggedIn ?>">
        <?php echo $user['first_name']; ?>
    </a>
        <a href="dashboard.php">
            <i class="fas fa-home"></i>
        </a>
        <a href="requests.php">
            <i class="fas fa-user-friends"></i>
        </a>
        <a href="settings.php">
            <i class="fas fa-cog"></i>
        </a>
        <a href="private/functions/logout.php">
        <i class="fas fa-sign-out-alt"></i>
        </a>
    </nav>
    </div>
  </div>

  <div class="wrapper">
      
