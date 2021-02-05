<?php 
include("private/header.php");


if(isset($_GET['profile_username'])) {
    $username = $_GET['profile_username'];
    $username_details_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user_array = mysqli_fetch_array($username_details_query);

    $num_friends = (substr_count($user_array['friends'], ",")) - 1;

    if(isset($_POST['unfriend'])) {
        $user = new User ($conn, $userLoggedIn);
        $user->unFriend($username);
    }

    if(isset($_POST['add_friend'])) {
        $user = new User ($conn, $userLoggedIn);
        $user->sendRequest($username);
    }

    if(isset($_POST['respond_request'])) {
        header("Location: requests.php");
    }
}
?>

    <div class="profile_box">
        <img class="profile_img" src="<?php echo $user_array['profile_img'];?>">

        <div class="profile_info">
        <p><?php echo "Buzzes: " . $user_array['posts_no']; ?></p>
        <p><?php echo "Likes: " . $user_array['likes_no']; ?></p>
        <p><?php echo "Friends:  " . $num_friends ?></p>
        </div>
        
        <form action="<?php echo $username; ?>" method="POST">
            <?php $profile_user_obj = new User ($conn, $username); 
            if($profile_user_obj->isClosed()) {
                header("Location: user_closed.php");
            }
            
            $logged_in_user_obj = new User ($conn, $userLoggedIn);
            if($userLoggedIn != $username){
                if($logged_in_user_obj->isFriend($username)) {
                    echo'<input type="submit" name="unfriend" class="unfriend" value="Remove Friend"><br>';
                }
                else if ($logged_in_user_obj->didReceiveRequest($username)) {
                    echo'<input type="submit" name="respond_request" class="confirm_req" value="Respond Request"><br>';
                }
                else if ($logged_in_user_obj->didSendRequest($username)) {
                    echo'<input type="submit" name="" class="req_pending" value="Request Pending"><br>';
                }
                else {
                    echo'<input type="submit" name="add_friend" class="add_friend" value="Add Friend"><br>';
                }
            } //end of if statement

            ?>


        </form>


    </div>

    <div class="profile_posts_column column">
    <input type="submit" class="post_profile_btn" data-toggle="modal" data-target="#post_form" value="Buzz Something"><br><br>
    <div class="posts_container"></div>
        <img height="50px" width="50px" id="loading" src="public/assets/images/icons/load_200p.gif">

        
    </div>

        <!-- Modal -->
        <div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postModalLabel">Buzz Something</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>Buzz something to your friends!</p>
                <form class="profile_post" action="" method="POST">
                    <textarea name="post_body_profile"></textarea>
                    <input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
                    <input type="hidden" name="user_to" value="<?php echo $username; ?>">
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" name="post_btn" id="submit_profile_post">Post</button>
            </div>
            </div>
        </div>
        </div>
            
        <script>
        var userLoggedIn='<?php echo $userLoggedIn; ?>';
        var profileUsername = '<?php echo $username; ?>';

        $(document).ready(function() {
            $('#loading').show();

            //Ajax request for loading first batch of posts
            $.ajax({
                url: "private/functions/ajax/ajax_profile_load_posts.php",
                type: "post",
                data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
                cache:false,

                success: function(data) {
                    $('#loading').hide();
                    $('.posts_container').html(data);
                }
            });

            $(window).scroll(function() {
                var height = $('.posts_container').height(); //div posts_container
                var scroll_top = $(this).scrollTop;
                var page = $('.posts_container').find('.nextPage').val();
                var noMorePosts = $('.posts_container').find('.noMorePosts').val();

                if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
                    $('#loading').show();

                    var ajaxReq = $.ajax({
                    url: "private/functions/ajax/ajax_profile_load_posts.php",
                    type: "post",
                    data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
                    cache:false,

                    success: function(response) {
                    $('.posts_container').find('.nextPage').remove(); //removes the current .nextPage
                    $('.posts_container').find('.noMorePosts').remove(); //removes current .nextPage

                    $('#loading').hide();
                    $('.posts_container').append(response);
                    }
                });
                
                } //End of if statement

                return false;
            }); //End of window . scroll

        });
    </script>

    </div>
</body>
</html>