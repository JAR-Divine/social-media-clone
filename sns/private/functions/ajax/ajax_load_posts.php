<?php 
include ("../../initialize.php");
include("../../classes/User.php");
include("../../classes/Post.php");

$limit = 10; //number of loaded posts per call

$posts = new Post($conn, $_REQUEST['userLoggedIn']);
$posts->loadPostsFriends($_REQUEST, $limit);
?>