<?php
// This file is called when the pop up of tags is closed in the viewStory.php
// The purpose of this file is to update the tags container to reflect changes made in the popup

include "../connection.php";
include "../functions.php";

$storyId = $_POST['storyId'];

echo fetchStoryTags($con, $storyId);

?>