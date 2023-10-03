<?php
// This file is called from navigation.js
// This file is used to search a user on the kepup event on the search box

include "connection.php";
include "functions.php";

$baseUrl = $_POST['baseUrl'];
$search = $_POST['currentSearch'];
$searchLike = $search . '%';

if(is_numeric($search)) {
    // If the search is by user id
    $byIdQuery = "SELECT user_id, username FROM Users WHERE user_id = ?";
    $byIdStmt = mysqli_prepare($con, $byIdQuery);
    mysqli_stmt_bind_param($byIdStmt, "s", $search);
    mysqli_stmt_execute($byIdStmt);
    $byIdResult = mysqli_stmt_get_result($byIdStmt);
    while ($byId = mysqli_fetch_assoc($byIdResult)) {
        echo "<div class='follower-display-container'>";
        echo "<div class='displayed-user-details'>";
        // For the user's pic
        $displayedUserPic = fetchUserDetails($con, $byId['user_id'])['profile_picture_url'];
        if($displayedUserPic == 0) {
            echo "<div class='displayed-user-pic' style='background-size: cover; background-repeat: no-repeat; background-image: url(" . $baseUrl . "/accountDetails/pics/default.jpg')></div>";
        } else {
            echo "<div class='displayed-user-pic' style='background-size: cover; background-repeat: no-repeat; background-image: url(" . $baseUrl . "/accountDetails/" . $displayedUserPic . ")'></div>";    
        }
        echo "<div class='account-username'>" . $byId['username'] . "</div>";
        echo "</div>";
        echo "</div>";
    }
} else {
    // If the search is by name or username
    $byNameQuery = "SELECT user_id, username FROM Users WHERE name LIKE ? OR username LIKE ?";
    $byNameStmt = mysqli_prepare($con, $byNameQuery);
    mysqli_stmt_bind_param($byNameStmt, "ss", $searchLike, $searchLike);
    mysqli_stmt_execute($byNameStmt);
    $byNameResult = mysqli_stmt_get_result($byNameStmt);
    while ($byName = mysqli_fetch_assoc($byNameResult)) {
        echo "<div class='follower-display-container'>";
        echo "<div class='displayed-user-details'>";
        // For the user's pic
        $displayedUserPic = fetchUserDetails($con, $byName['user_id'])['profile_picture_url'];
        if($displayedUserPic == 0) {
            echo "<div class='displayed-user-pic' style='background-size: cover; background-repeat: no-repeat; background-image: url(" . $baseUrl . "/accountDetails/pics/default.jpg)'></div>";
        } else {
            echo "<div class='displayed-user-pic' style='background-size: cover; background-repeat: no-repeat; background-image: url(" . $baseUrl . "/accountDetails/" . $displayedUserPic . ")'></div>";    
        }
        echo "<div class='account-username'>" . $byName['username'] . "</div>";
        echo "</div>";
        echo "</div>";
    }
}

?>