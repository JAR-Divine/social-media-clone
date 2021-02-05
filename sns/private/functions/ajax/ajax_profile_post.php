<?php 
    require '../../initialize.php';
    include("../../classes/User.php");
    include("../../classes/Post.php");

    if(isset($_POST['post_body_profile'])) {
        $post = new Post($conn, $_POST['user_from']);
        $post->submitPost($_POST['post_body_profile'], $_POST['user_to']);
    }
?>