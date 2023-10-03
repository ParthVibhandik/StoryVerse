<?php
session_start();
include "../../connection.php";

$userId = $_SESSION['user_id'];
$storyId = $_POST['storyId'];
$bid = $_POST['bid'];

$query = "INSERT INTO Bids (bid_by, story_id, bid_text) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'iis', $userId, $storyId, $bid);
if(mysqli_stmt_execute($stmt)) {
    echo "Successfully bidded";
} else {
    echo "There was a problem posting your bid";
}

?>