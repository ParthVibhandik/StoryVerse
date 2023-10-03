<!-- This file is called from viewStory/script.js when button id - follow-story-btn is clicked -->
<!-- This file is used to insert a record to the story_follow table -->
<!-- This file is used to make a user follow a story, when they click the button -->
<!-- This file receives the story_id as a parameter through post method -->

<?php
session_start();
include "../connection.php";
include "../functions.php";

$storyId = $_POST['storyId'];
$userId = $_SESSION['user_id'];

// Checking if the user already follows the story
if(checkFollow($con, $storyId, $userId)) {
    // If they do, remove following
    $query = "DELETE FROM story_follow WHERE story_id = ? and user_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ii", $storyId, $userId);
    mysqli_stmt_execute($stmt);

    // Display follow on the button
    echo "Follow";
} else {
    // If they dont, add following
    $query = "INSERT INTO story_follow (story_id, user_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ii", $storyId, $userId);
    mysqli_stmt_execute($stmt);

    // Display following on the button
    echo "Following";
}



?>