<?php
    class User {
        private $user;
        private $conn;

        //function to connect to the database and get database data
        public function __construct($conn, $user) {
            $this->conn = $conn;
            $user_details_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
            $this->user = mysqli_fetch_array($user_details_query);
        } //end of __construct function

        //function to get the Username
        public function getUsername() {
            return $this->user['username'];
        }//end of getUsername function

        //function to get all the posts
        public function getNumPosts() {
            $username = $this->user['username'];
            $query = mysqli_query($this->conn, "SELECT posts_no FROM users WHERE username='$username'");
            $row = mysqli_fetch_array($query);
            return $row['posts_no'];
        }  //end of getNumPosts()

        //function to get Profile Image from the server
        public function getProfilePic() {
            $username = $this->user['username'];
            $query = mysqli_query($this->conn, "SELECT profile_img FROM users WHERE username='$username'");
            $row = mysqli_fetch_array($query);
            return $row['profile_img'];
        } //end of getProfilePic function

        //function to get friend array data from the server
        public function getFriendArray() {
            $username = $this->user['username'];
            $query = mysqli_query($this->conn, "SELECT friends FROM users WHERE username='$username'");
            $row = mysqli_fetch_array($query);
            return $row['friends'];
        } //end of getFriendArray function

        //function to get First & Last Name from the server
        public function getFirstAndLastName() {
            $username = $this->user['username'];
            $query = mysqli_query($this->conn, "SELECT first_name, last_name FROM users WHERE username='$username'");
            $row = mysqli_fetch_array($query);
            return $row['first_name'] . " " . $row['last_name'];
        } //end of getFirstAndLastName function

        //function to check if the user is active
        public function isClosed() {
            $username = $this->user['username'];
            $query = mysqli_query($this->conn, "SELECT user_active FROM users WHERE username='$username'");
            $row = mysqli_fetch_array($query);

            if($row['user_active'] == 'yes'){
                return true;
            } else {
                return false;
            }
        }//end of isClosed function

        //function to check if the users are friends/following
        public function isFriend($username_to_check) {
            $usernameComma = "," . $username_to_check . ",";

            //check if the friend is in the friends array or if the username is the same as the active user
            if((strstr($this->user['friends'], $usernameComma) || $username_to_check == $this->user['username'])) {
                return true;
            } else {
                return false;
            }

        }//end of isFriend function

        public function didReceiveRequest($user_from) {
            $user_to = $this->user['username'];
            $check_request_query = mysqli_query($this->conn, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from='$user_from'");
            if(mysqli_num_rows($check_request_query) > 0) {
                return true;
            }
            else {
                return false;
            }
        }

        public function didSendRequest($user_to) {
            $user_from = $this->user['username'];
            $check_request_query = mysqli_query($this->conn, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from='$user_from'");
            if(mysqli_num_rows($check_request_query) > 0) {
                return true;
            }
            else {
                return false;
            }
        }

        public function unFriend($user_to_remove) {
            $logged_in_user = $this->user['username'];

            $query = mysqli_query($this->conn, "SELECT friends FROM users WHERE username='$user_to_remove'");
            $row = mysqli_fetch_array($query);
            $friend_array_username = $row['friends'];

            $new_friend_array = str_replace($user_to_remove . ",", "", $this->user['friends']);
            $remove_friend = mysqli_query($this->conn, "UPDATE users SET friends='$new_friend_array' WHERE username='$logged_in_user'");

            $new_friend_array = str_replace($this->user['username'] . ",", "", $friend_array_username);
            $remove_friend = mysqli_query($this->conn, "UPDATE users SET friends='$new_friend_array' WHERE username='$user_to_remove'");
        }

        public function sendRequest($user_to) {
            $user_from = $this->user['username'];
            $query = mysqli_query($this->conn, "INSERT INTO friend_requests VALUES ('', '$user_to', '$user_from')");
        }
    }
?>