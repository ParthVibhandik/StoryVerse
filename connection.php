<?php

    $dbHost = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "StoryVerse";

    if(!$con = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName)){
        die("failed to connect");
    }

?>