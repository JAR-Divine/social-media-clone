<?php 
    require 'initialize.php';
    include("classes/User.php");
    include("classes/Post.php");

    //check if a user is logged in
    if(isset($_SESSION['username'])) {
        $userLoggedIn = $_SESSION['username'];
        $user_info_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$userLoggedIn'");
        $user = mysqli_fetch_array($user_info_query);
    } else { //if no user is logged in redirect to login/register page
        header("Location: index.php");
    }

    //Get posts_id
    if(isset($_GET['post_id'])) {
        $post_id = $_GET['post_id'];
    }

    $get_likes = mysqli_query($conn, "SELECT likes, posted_by FROM posts WHERE posts_id='$post_id'");
    $row = mysqli_fetch_array($get_likes);
    $total_likes = $row['likes'];
    $user_liked = $row['posted_by'];
    $user_details_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$user_liked'");
    $row = mysqli_fetch_array($user_details_query);
    $total_user_likes = $row['likes_no'];

    //Like Button
    if(isset($_POST['like_button'])){
        $total_likes++;
        $query = mysqli_query($conn, "UPDATE posts SET likes='$total_likes' WHERE posts_id='$post_id'");
        $total_user_likes++;
        $user_likes = mysqli_query($conn, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
        $insert_user = mysqli_query($conn, "INSERT INTO likes VALUES('', '$userLoggedIn', '$post_id')");

        //Insert Notification
    }

    //Unlike Button
    if(isset($_POST['unlike_button'])){
        $total_likes--;
        $query = mysqli_query($conn, "UPDATE posts SET likes='$total_likes' WHERE posts_id='$post_id'");
        $total_user_likes--;
        $user_likes = mysqli_query($conn, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
        $insert_user = mysqli_query($conn, "DELETE FROM likes Where username='$userLoggedIn' AND posts_id='$post_id'");

        //Insert Notification
    }

    //Check for previous likes
    $check_query = mysqli_query($conn, "SELECT * FROM likes WHERE username='$userLoggedIn' AND posts_id='$post_id'");
    $num_rows = mysqli_num_rows($check_query);

    if($num_rows > 0) {
        echo '<form action="like.php?post_id=' .$post_id . '" method="POST">
        <input type="submit" class="comment_like" name="unlike_button" value="Unlike"
        <div class="like_value">
            <p class="like_count">'. $total_likes . ' Likes</p>
            </div>
        </form>
        ';
    } else {
        echo '<form action="like.php?post_id=' .$post_id . '" method="POST">
        <input type="submit" class="comment_like" name="like_button" value="Like"
        <div class="like_value">
            <p class="like_count">'. $total_likes . ' Likes</p>
            </div>
        </form>
        ';
    }
    ?>

<html>
    <head>
    <link rel="stylesheet" type="text/css" href="../css/style.min.css">
    </head>
    <body>
        
    </body>
</html>