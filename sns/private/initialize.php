<?php 
    ob_start(); //Activates Output Buffering
    session_start();

    $timezone = date_default_timezone_set("Europe/London");

    $server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db = "socmed";

    $conn = mysqli_connect($server, $db_user, $db_pass, $db);

    if(mysqli_connect_errno()){
        echo "failed to connect: " . mysqli_connect_errno();
    }
?>