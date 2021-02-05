<?php 
include("private/header.php");

if(isset($_POST['post'])) {

    $uploadOk = 1;
    $imageName = $_FILES['fileUpload']['name'];
    $errorMessage = "";

    if($imageName != "") {
        $targetDir = "public/assets/images/posts/";
        $imageName = $targetDir . uniqid() .basename($imageName);
        $imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);

        if($_FILES['fileUpload']['size'] > 10000000) {
            $errorMessage = "The file is too large to upload";
            $upload = 0;
        }

        if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" &&strtolower($imageFileType) != "jpg") {
            $errorMessage = "Only jpg, jpg & png files are allowed";
            $upload = 0;
        }

        if($uploadOk) {
            if(move_uploaded_file($_FILES['fileUpload']['tmp_name'], $imageName)) {
                //image uploaded is okay
            } else {
                //image did not upload
                $uploadOk = 0;
            }
        }
    }

    if($uploadOk == 1) {
        $post = new Post($conn, $userLoggedIn);
    $post->submitPost($_POST['post_text'], 'none', $imageName);
    }
    else {
        echo "<div style='text-align: center;' class='alert alert-danger'>
            $errorMessage
        
        </div>";
    }
}

?>
    <div class="user_info column">
        <a href="<?php echo $userLoggedIn ?>"><img class="user_dp" src="<?php echo $user['profile_img'];?>"></a>

        <div class="user_info_left_right">        
        <a href="<?php echo $userLoggedIn ?>">
        <?php 
            echo $user['first_name'] . " " . $user['last_name'];
            ?>
        </a>
        <br>
            <?php echo "Buzzes: " . $user['posts_no'] . "<br>" ; 
            echo "Likes: " . $user['likes_no'];
        ?>
        </div>
    </div>

    <div class="posts_column column">
        <form class="posts_form" action="dashboard.php" method="post" enctype="multipart/form-data">
        <br><br>
            <input type="file" name="fileUpload" id="fileUpload">
            <br><br>
            <textarea name="post_text" id="post_text" placeholder="Buzz something"></textarea>
            <input class="submit_btn" type="submit" name="post" id="post_btn" value="Post">
            <hr>
        </form>
        <br>

        <div class="posts_container"></div>
        <img height="50px" width="50px" id="loading" src="public/assets/images/icons/load_200p.gif">
        

    </div>

    <script>
        var userLoggedIn='<?php echo $userLoggedIn; ?>';

        $(document).ready(function() {
            $('#loading').show();

            //Ajax request for loading first batch of posts
            $.ajax({
                url: "private/functions/ajax/ajax_load_posts.php",
                type: "post",
                data: "page=1&userLoggedIn=" + userLoggedIn,
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
                    url: "private/functions/ajax/ajax_load_posts.php",
                    type: "post",
                    data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
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