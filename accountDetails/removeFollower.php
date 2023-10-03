<?php
// This file removes the selected follower

session_start();

include "../connection.php";
include "../functions.php";

$followerToRemove = $_POST['followerToRemove'];
$userId = $_SESSION['user_id'];

$query = "DELETE FROM user_follow WHERE follows_user_id = ? AND user_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ii", $userId, $followerToRemove);
mysqli_stmt_execute($stmt);

fetchFollowers($con, $userId);

?>