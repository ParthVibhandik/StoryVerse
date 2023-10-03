<?php
// This file is called from the script.js of accountDetails.php
// When the follow button is pressed on any users account page
// This file inputs a userId, this is added to a table in the database against the users own id

session_start();
include "../connection.php";
include "../functions.php";

$accToBeFollowed = $_POST['accToBeFollowed'];
$userId = $_SESSION['user_id'];

// First check if the user already follows the account
if(checkUserFollow($con, $userId, $accToBeFollowed)) {
    // The user follows the account
    // Hene remove the following
    $query = "DELETE FROM user_follow WHERE user_id = ? and follows_user_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ii", $userId, $accToBeFollowed);
    mysqli_stmt_execute($stmt);

    // This prints follow in the follow-user-button
    echo "Follow";
} else {
    // The user does not follow the account
    // Hence add the following
    $query = "INSERT INTO user_follow (user_id, follows_user_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ii", $userId, $accToBeFollowed);
    mysqli_stmt_execute($stmt);

    // This prints following in the follow-user-button
    echo "Following";
}



?>