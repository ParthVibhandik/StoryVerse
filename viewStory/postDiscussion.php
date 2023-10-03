<?php
include "../connection.php";
include "../functions.php";

$storyId = $_POST['storyId'];
$message = $_POST['message'];

postDiscussion($con, $storyId, $message, 'Contribution_discussion');

?>