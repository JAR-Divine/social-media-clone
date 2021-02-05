<?php 
    class Post {
        private $user_obj;
        private $conn;

        public function __construct($conn, $user) {
            $this->conn = $conn;
            $this->user_obj = new User($conn, $user);
        }
        
        
        public function submitPost($body, $user_to, $imageName) {
            $body = strip_tags($body); //removes any html tag
            $body = mysqli_real_escape_string($this->conn, $body); //escapes any special characters
            $check_empty = preg_replace('/\s+/', ' ', $body);

            if($check_empty != "") {
                //Current Date and Time
                $date_added = date("Y-m-d H:i:s");
                //Get username
                $posted_by = $this->user_obj->getUsername();
                //if user is on their own profile, user_to will be 'none'
                if($user_to == $posted_by) {
                    $user_to = "none";
                }

                //insert post
                $query = mysqli_query($this->conn, "INSERT INTO posts VALUES (' ', '$body', '$posted_by', '$user_to', '$date_added', 'no', 'no', '0', '$imageName') ");
                $returned_id = mysqli_insert_id($this->conn); //returns the id of the post submitted

                //Insert notification

                //Updates posts count for the user
                $posts_num = $this->user_obj->getNumPosts();
                $posts_num++;
                $update_query = mysqli_query($this->conn, "UPDATE users SET posts_no='$posts_num' WHERE username='$posted_by'");
            }
        }

        public function loadPostsFriends($data, $limit) {

            $page = $data['page'];
            $userLoggedIn = $this->user_obj->getUsername();

            if($page == 1)
                $start = 0;
            else 
                $start = ($page - 1) * $limit;
            

            $str = ""; //Return String
            $data_query = mysqli_query($this->conn, "SELECT * FROM posts WHERE post_deleted='no' ORDER BY posts_id DESC");

            if (mysqli_num_rows($data_query) > 0) {

                $num_iterations = 0; //Number of results checked
                $count = 1;

                while($row = mysqli_fetch_array($data_query)) {
                    $id = $row['posts_id'];
                    $body = $row['body'];
                    $posted_by = $row['posted_by'];
                    $date_time = $row['date_added'];
                    $imagePath = $row['images'];

                    //prepare user_to string to be included despite not posted to a user
                    if($row['user_to'] == "none"){
                        $user_to = "";
                    } else {
                        $user_to_obj = new User($this->conn, $row['user_to']);
                        $user_to_name = $user_to_obj->getFirstAndLastName();
                        $user_to = "to <a href='" . $row['user_to'] . "'>" . $user_to_name . "</a>";
                    }

                    //Check if user that posted is active
                    $added_by_obj = new User($this->conn, $posted_by);
                    if($added_by_obj->isClosed()) {
                        continue;
                    }

                    $user_logged_obj = new User($this->conn, $userLoggedIn);
                    if($user_logged_obj->isFriend($posted_by)) {

                        if($num_iterations++ < $start)
                            continue;

                        //break once 10 posts have loaded else add 1 to count
                        if($count > $limit) {
                            break;
                        } else {
                            $count++;
                        }
                        

                        //delete button
                        if($userLoggedIn == $posted_by) {
                            $delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
                        }
                        else {
                            $delete_button = "";
                        }

                        $user_details_query = mysqli_query($this->conn, "SELECT first_name, last_name, profile_img FROM users WHERE username='$posted_by'");
                        $user_row = mysqli_fetch_array($user_details_query);
                        $first_name = $user_row['first_name'];
                        $last_name = $user_row['last_name'];
                        $profile_img = $user_row['profile_img'];

                    ?>

                        <script>
                            function toggle<?php echo $id; ?>() {

                                var target = $(event.target); //when the profile is clicked
                                if(!target.is("a")) {
                                    var element = document.getElementById("toggleComment<?php echo $id; ?>");
                                
                                if(element.style.display == "block") {
                                    element.style.display = "none";
                                } else {
                                    element.style.display = "block";
                                }
                            }
                                }

                                
                                
                        </script>

                        <?php

                        $comments_check = mysqli_query($this->conn, "SELECT * FROM comments WHERE posts_id='$id'");
                        $comments_check_num = mysqli_num_rows($comments_check);


                        //Timeframe
                        $date_time_now = date("Y-m-d H:i:s");
                        $start_date = new DateTime($date_time); //Time of post
                        $end_date = new DateTime($date_time_now); //current Time
                        $interval = $start_date->diff($end_date);
                        if($interval->y >= 1) {
                            if($interval == 1)
                                $time_message = $interval->y . " year ago";//1 year ago
                            else 
                                $time_message = $interval->y . " years ago";//2+ years ago
                            
                        }
                        else if ($interval->m >= 1) {
                            if($interval->d >= 0) {
                                $days = " ago";
                            }
                            else if($interval->d == 1) {
                                $days = $interval->d . " day ago";
                            }
                            else {
                                $days = $interval->d . " days ago";
                            }

                            if($interval->m == 1){
                                $time_message = $interval->m . " month". $days;
                            }
                            else {
                                $time_message = $interval->m . " months". $days;
                            }
                        }
                        else if ($interval->d >=1){
                            if($interval->d == 1) {
                                $time_message =  "Yesterday";
                            }
                            else {
                                $time_message = $interval->d . " days ago";
                            }
                        }
                        else if ($interval->h >=1) {
                            if($interval->h == 1) {
                                $time_message = $interval->h . " hour ago";
                            }
                            else {
                                $time_message = $interval->h . " hours ago";
                            }
                        }
                        else if ($interval->i >=1) {
                            if($interval->i == 1) {
                                $time_message = $interval->i . " minute ago";
                            }
                            else {
                                $time_message = $interval->i . " minutes ago";
                            }
                        }
                        else {
                            if($interval->s < 30) {
                                $time_message = $interval->d . "Just now";
                            }
                            else {
                                $time_message = $interval->s . " seconds ago";
                            }
                        }

                        //process image
                        if($imagePath != "") {
                            $imageDiv = "<div class='postedImage'>
                                <img src='$imagePath'>
                            </div>";
                        } else {
                            $imageDiv = "";
                        }

                        $str .= "<div class='post_status' onClick='javascript:toggle$id()'>
                            <div class='post_profile_img'>
                                <img src='$profile_img' width='50'>
                            </div>

                            <div class='posted_by' style='color:black;'>
                                <a href='$posted_by'> $first_name $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp; $time_message
                                $delete_button
                            </div>

                            <div id='post_body'>
                            $body
                            <br>
                            $imageDiv
                            </div>

                            <div class='newsfeedPostOptions'>
                                Comments($comments_check_num)&nbsp;&nbsp;&nbsp;
                                <iframe src='private/like.php?post_id=$id'>
                                </iframe>
                            </div>

                        </div>
                        <div class='post_comment' id='toggleComment$id' style='display:none;'>
                        <iframe src='private/comments_part.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
                        </div>
                        <hr>";
                }
                ?>

                <!-- Delete Post -->
                <script>
                    $(document).ready(function() {
                        
                        $('#post<?php echo $id; ?>').on('click', function(){
                            bootbox.confirm("Do you want to delete this post?", function(result) {
                                
                                $.post("../functions/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

                                if(result) {
                                    location.reload();
                                }
                            });
                        });
                    });
                </script>
                
                <?php
                    
            } //end of while loop

                if ($count > $limit) {
                    $str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
                            <input type='hidden' class='noMorePosts' value='false'>";
                } else {
                    $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: center;'> No more posts to show!</p>";
                }

        }
            echo $str;
        }

        public function loadProfilePosts($data, $limit) {

            $page = $data['page'];
            $profileUser=$data['profileUsername'];
            $userLoggedIn = $this->user_obj->getUsername();

            if($page == 1)
                $start = 0;
            else 
                $start = ($page - 1) * $limit;
            

            $str = ""; //Return String
            $data_query = mysqli_query($this->conn, "SELECT * FROM posts WHERE post_deleted='no' AND ((posted_by='$profileUser' AND user_to='none') OR user_to='$profileUser') ORDER BY posts_id DESC");

            if (mysqli_num_rows($data_query) > 0) {

                $num_iterations = 0; //Number of results checked
                $count = 1;

                while($row = mysqli_fetch_array($data_query)) {
                    $id = $row['posts_id'];
                    $body = $row['body'];
                    $posted_by = $row['posted_by'];
                    $date_time = $row['date_added'];
                    $imagePath = $row['images'];

                        if($num_iterations++ < $start)
                            continue;

                        //break once 10 posts have loaded else add 1 to count
                        if($count > $limit) {
                            break;
                        } else {
                            $count++;
                        }
                        

                        //delete button
                        if($userLoggedIn == $posted_by) {
                            $delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
                        }
                        else {
                            $delete_button = "";
                        }

                        $user_details_query = mysqli_query($this->conn, "SELECT first_name, last_name, profile_img FROM users WHERE username='$posted_by'");
                        $user_row = mysqli_fetch_array($user_details_query);
                        $first_name = $user_row['first_name'];
                        $last_name = $user_row['last_name'];
                        $profile_img = $user_row['profile_img'];

                    ?>

                        <script>
                            function toggle<?php echo $id; ?>() {

                                var target = $(event.target); //when the profile is clicked
                                if(!target.is("a")) {
                                    var element = document.getElementById("toggleComment<?php echo $id; ?>");
                                
                                if(element.style.display == "block") {
                                    element.style.display = "none";
                                } else {
                                    element.style.display = "block";
                                }
                            }
                                }

                                
                                
                        </script>

                        <?php

                        $comments_check = mysqli_query($this->conn, "SELECT * FROM comments WHERE posts_id='$id'");
                        $comments_check_num = mysqli_num_rows($comments_check);


                        //Timeframe
                        $date_time_now = date("Y-m-d H:i:s");
                        $start_date = new DateTime($date_time); //Time of post
                        $end_date = new DateTime($date_time_now); //current Time
                        $interval = $start_date->diff($end_date);
                        if($interval->y >= 1) {
                            if($interval == 1)
                                $time_message = $interval->y . " year ago";//1 year ago
                            else 
                                $time_message = $interval->y . " years ago";//2+ years ago
                            
                        }
                        else if ($interval->m >= 1) {
                            if($interval->d >= 0) {
                                $days = " ago";
                            }
                            else if($interval->d == 1) {
                                $days = $interval->d . " day ago";
                            }
                            else {
                                $days = $interval->d . " days ago";
                            }

                            if($interval->m == 1){
                                $time_message = $interval->m . " month". $days;
                            }
                            else {
                                $time_message = $interval->m . " months". $days;
                            }
                        }
                        else if ($interval->d >=1){
                            if($interval->d == 1) {
                                $time_message =  "Yesterday";
                            }
                            else {
                                $time_message = $interval->d . " days ago";
                            }
                        }
                        else if ($interval->h >=1) {
                            if($interval->h == 1) {
                                $time_message = $interval->h . " hour ago";
                            }
                            else {
                                $time_message = $interval->h . " hours ago";
                            }
                        }
                        else if ($interval->i >=1) {
                            if($interval->i == 1) {
                                $time_message = $interval->i . " minute ago";
                            }
                            else {
                                $time_message = $interval->i . " minutes ago";
                            }
                        }
                        else {
                            if($interval->s < 30) {
                                $time_message = $interval->d . "Just now";
                            }
                            else {
                                $time_message = $interval->s . " seconds ago";
                            }
                        }

                        if($imagePath != "") {
                            $imageDiv = "<div class='postedImage'>
                                <img src='$imagePath'>
                            </div>";
                        }
                        else {
                            $imageDiv = "";
                        }

                        $str .= "<div class='post_status' onClick='javascript:toggle$id()'>
                            <div class='post_profile_img'>
                                <img src='$profile_img' width='50'>
                            </div>

                            <div class='posted_by' style='color:black;'>
                                <a href='$posted_by'> $first_name $last_name </a> &nbsp;&nbsp;&nbsp;&nbsp; $time_message
                                $delete_button
                            </div>

                            <div id='post_body'>
                            $body
                            <br>
                            $imageDiv
                            </div>

                            <div class='newsfeedPostOptions'>
                                Comments($comments_check_num)&nbsp;&nbsp;&nbsp;
                                <iframe src='private/like.php?post_id=$id'>
                                </iframe>
                            </div>

                        </div>
                        <div class='post_comment' id='toggleComment$id' style='display:none;'>
                        <iframe src='private/comments_part.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
                        </div>
                        <hr>";
                ?>

                <!-- Delete Post -->
                <script>
                    $(document).ready(function() {
                        
                        $('#post<?php echo $id; ?>').on('click', function(){
                            bootbox.confirm("Do you want to delete this post?", function(result) {
                                
                                $.post("../functions/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

                                if(result) {
                                    location.reload();
                                }
                            });
                        });
                    });
                </script>
                
                <?php
                    
            } //end of while loop

                if ($count > $limit) {
                    $str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
                            <input type='hidden' class='noMorePosts' value='false'>";
                } else {
                    $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: center;'> No more posts to show!</p>";
                }

        }
            echo $str;
        }
    } 

?>