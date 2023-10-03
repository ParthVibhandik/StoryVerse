<?php
// This file is used in the script.js of viewStory.js
// This is used to update the tag count in the tag popup of the viewStory.php page
// Called when adding or removing a tag

include "../connection.php";

$storyId = $_POST['storyId'];

$query = "SELECT COUNT(*) AS tag_count FROM story_tags WHERE story_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $storyId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
echo $row['tag_count'] . "/5";


?>