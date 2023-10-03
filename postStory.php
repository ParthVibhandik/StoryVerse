<?php
session_start();
include "connection.php";

$userId = $_SESSION['user_id'];
$storyTitle = $_POST['storyTitle'];
$storyStart = $_POST['storyStart'];
$storyStatus = 'active';

$query = "INSERT INTO Stories (created_by, story_title, story_start, story_status) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($con, $query);

if (!$stmt) {
    die("Error: " . mysqli_error($con)); // You can add error handling here
}

mysqli_stmt_bind_param($stmt, 'isss', $userId, $storyTitle, $storyStart, $storyStatus);
if (!mysqli_stmt_execute($stmt)) {
    echo "There was an error posting your story";
} else {
    echo "Story successfully posted";
}

?>