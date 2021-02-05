<?php
    //declaring variables
    $fname = ""; //First Name Variable
    $lname = ""; //Last Name Variable
    $email = ""; //Email Variable
    $email2 = ""; //Confirm Email Variable
    $password = ""; //Password Variable
    $password2 = ""; //Confirm Password Variable
    $date = ""; //Date of registration Variable
    $gender = ""; //Gender Variable
    $error_array = array(); //Error Message
    $success_array = array(); //Successful Registration Message

    if (isset($_POST['Register_btn'])) {
        //Registration form values
        $fname = strip_tags($_POST['reg_fname']); //Removing html tags
        $fname = str_replace(' ','', $fname); //remove spaces
        $fname = ucfirst(strtolower($fname)); //Turning the first letter to upper case
        $_SESSION['reg_fname'] = $fname; //Stores first name into the session

        $lname = strip_tags($_POST['reg_lname']); //Removing html tags
        $lname = str_replace(' ','', $lname); //remove spaces
        $lname = ucfirst(strtolower($lname)); //Turning the first letter to upper case
        $_SESSION['reg_lname'] = $lname; //Stores last name into the session

        $email = strip_tags($_POST['reg_email']); //Removing html tags
        $email = str_replace(' ','', $email); //remove spaces
        $_SESSION['reg_email'] = $email; //Stores email into the session

        $email2 = strip_tags($_POST['reg_email2']); //Removing html tags
        $email2 = str_replace(' ','', $email2); //remove spaces
        $_SESSION['reg_email2'] = $email2; //Stores email into the session

        $password = strip_tags($_POST['reg_password']); //Removing html tags
        $password2 = strip_tags($_POST['reg_password2']); //Removing html tags
        
        $date = date("Y-m-d"); //declares the current date

        $gender = $_POST['reg_gender'];
        $_SESSION['reg_gender'] = $gender; //stores gender into the session

        //check if email and confirm email are the same
        if($email == $email2) {
                //Check if email format is valid
                if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

                    //Check if email is already registered with an account
                    $email_check = mysqli_query($conn, "SELECT email FROM users WHERE email='$email'");

                    //Count the number of rows returned
                    $num_rows = mysqli_num_rows($email_check);
                    if ($num_rows > 0) {
                        array_push($error_array, "Email is already in use.<br>") ;
                    }
                } else {
                    array_push($error_array, "Invalid format!<br>");
                }
        } else {
            array_push ($error_array, "Emails don't match<br>"); //error message for unmatching emails
        }

        if (strlen($fname) > 25 || strlen($fname) < 2) { //error message for few or exceeding characters
            array_push ($error_array, "First name must be between 2-25 characters.<br>");
        }

        if (strlen($lname) > 25 || strlen($lname) < 2) {
            array_push ($error_array, "Last name must be between 2 - 25 characters.<br>"); //error message for few or exceeding characters
        } 

        if ($password != $password2) { //check if input passwords match
            array_push ($error_array, "Passwords do not match.<br>");
        }
        else {
            if(preg_match('/[^A-Za-z0-9]/',$password)) { //checks if the password contains valid characters
                array_push ($error_array, "Password contains invalid characters.<br>");
            }
        }
        if (strlen($password) > 30 || strlen($password) < 5) { //check if password is within the limit of characters
            array_push ($error_array, "Password must be between 5-30 characters.<br>");
        }

        if(empty($error_array)) {
            $password = md5($password); //Encrypt password before savingg to the database

            //Generate username
            $username = strtolower($fname . "_" . $lname);
            $check_username_query = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");

            $i = 0;
            //if username exists add number to username
            while(mysqli_num_rows($check_username_query) != 0) {
                $i++;
                $username = $username . "_" . $i;
                $check_username_query = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");
            }
        
        //Default Profile Image based on gender
        if ($gender == "Male") {
            $profile_img = "public/assets/images/profile_img/default/ddp_male.png";
        } else if ($gender == "Female") {
            $profile_img = "public/assets/images/profile_img/default/ddp_female.png";
        }

        //Inserting Values into Database
        $query = mysqli_query($conn, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$email', '$password', '$date', '$profile_img', '0', '0', 'no', ',', '$gender')");

        array_push($success_array, "<span>You have successfully registered! You can now log in!</span>");
        }
    }
?>