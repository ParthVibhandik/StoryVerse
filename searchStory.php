<?php
// This file is called from navigation.js
// This file is used to search a story on the kepup event on the search box

include "connection.php";

$search = $_POST['currentSearch'];
$searchLike = $search . '%';

if(is_numeric($search)) {
    // If the search is by story id
    $byIdQuery = "SELECT story_id, story_title, story_start FROM Stories WHERE story_id = ?";
    $byIdStmt = mysqli_prepare($con, $byIdQuery);
    mysqli_stmt_bind_param($byIdStmt, "s", $search);
    mysqli_stmt_execute($byIdStmt);
    $byIdResult = mysqli_stmt_get_result($byIdStmt);
    while ($byId = mysqli_fetch_assoc($byIdResult)) {
        echo "<div class='created-story-box link-story' id='" . $byId['story_id'] . "'>";
        echo "<p class='story-title'>" . $byId['story_title'] . "</p>";
        echo "<p class='story-start'>" . $byId['story_start'] . "</p>";
        echo "</div>";
    }
} else {
    // If the search is by tags
    // Fetching the story id from the story_tags table
    $byTagQueryFetchId = "SELECT DISTINCT story_id FROM story_tags WHERE tag LIKE ?";
    $byTagStmtFetchId = mysqli_prepare($con, $byTagQueryFetchId);
    mysqli_stmt_bind_param($byTagStmtFetchId, "s", $searchLike);
    mysqli_stmt_execute($byTagStmtFetchId);
    $byTagResultFetchId = mysqli_stmt_get_result($byTagStmtFetchId);
    while ($byTagFetchId = mysqli_fetch_assoc($byTagResultFetchId)) {

        // Fetching the story details through story_id
        $byTagQuery = "SELECT story_id, story_title, story_start FROM Stories WHERE story_id = ?";
        $byTagStmt = mysqli_prepare($con, $byTagQuery);
        mysqli_stmt_bind_param($byTagStmt, "i", $byTagFetchId['story_id']);
        mysqli_stmt_execute($byTagStmt);
        $byTagResult = mysqli_stmt_get_result($byTagStmt);
        while ($byTag = mysqli_fetch_assoc($byTagResult)) {
            echo "<div class='created-story-box link-story' id='" . $byTag['story_id'] . "'>";
            echo "<p class='story-title'>" . $byTag['story_title'] . "</p>";
            echo "<p class='story-start'>" . $byTag['story_start'] . "</p>";
            echo "</div>";
        }
    }
}

?>