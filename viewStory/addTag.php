<?php
// This file is used to add tags from to story

session_start();
include "../connection.php";
include "../functions.php";

$tag = $_POST['tag'];
$storyId = $_POST['storyId'];
$countTag = countStoryTags($con, $storyId);

if($countTag > 4) {
    // Dont add
    displayTags($con, $storyId);
} else {
    // Add if smaller than 5
    // Check if the tag being added, already exists
    if(checkStoryTag($con, $storyId, $tag)) {
        // Dont add
        displayTags($con, $storyId);
    } else {
        $addTagQuery = "INSERT INTO story_tags (story_id, tag) VALUES (?, ?)";
        $addTagStmt = mysqli_prepare($con, $addTagQuery);
        mysqli_stmt_bind_param($addTagStmt, "is", $storyId, $tag);
        mysqli_stmt_execute($addTagStmt);

        displayTags($con, $storyId);
    }
}

function displayTags($con, $storyId) {
    $storyTags = fetchStoryTags($con, $storyId);
    while($storyTag = mysqli_fetch_assoc($storyTags)) {
        echo "<div class='edit-tag-display-box' id='" . $storyTag['story_tag_id'] . "'># ";
        echo $storyTag['tag'];
        echo "</div>";
    }
}
