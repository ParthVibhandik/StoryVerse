<?php
// This file is used to remove tags from a story

session_start();
include "../connection.php";
include "../functions.php";

$tagId = $_POST['tagId'];
$storyId = $_POST['storyId'];
$countTag = countStoryTags($con, $storyId);

// Delete the tag
$removeTagQuery = "DELETE FROM story_tags WHERE story_tag_id = ?";
$removeTagStmt = mysqli_prepare($con, $removeTagQuery);
mysqli_stmt_bind_param($removeTagStmt, "i", $tagId);
mysqli_stmt_execute($removeTagStmt);
displayTags($con, $storyId);

function displayTags($con, $storyId) {
    $storyTags = fetchStoryTags($con, $storyId);
    while($storyTag = mysqli_fetch_assoc($storyTags)) {
        echo "<div class='edit-tag-display-box' id='" . $storyTag['story_tag_id'] . "'># ";
        echo $storyTag['tag'];
        echo "</div>";
    }
}

?>