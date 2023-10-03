<?php

// This file is called from viewProfile.js
// This file is used to get the userId of a user from the username
// This file returns a link to the caller

include "connection.php";

$username = $_POST['username'];

$query = "SELECT user_id AS userId FROM Users WHERE username = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$userId = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['userId'];

$destinationURL = "/accountDetails/accountDetails.php?userId=" . urlencode($userId);
echo $destinationURL;

?>