<?php
// This file is called fromt the accountDetails.php
// It is responsible for refreshing the followers and following count everytime the close button is clicked on the popup

include "../connection.php";
include "../functions.php";

session_start();

$userId = $_SESSION['user_id'];

countFollowing($con, $userId);

?>