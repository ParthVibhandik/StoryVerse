<?php
session_start();
include "../../../connection.php";

    $userId = $_SESSION['user_id'];
    $bidId = $_POST['bidId'];
    $upvote = 1;
    $storyId = $_SESSION['story_id'];
   
    $deleteVoteQuery = "DELETE FROM Bid_votes WHERE voted_by = ? AND voted_on_story = ?";
    $deleteVoteStmt = mysqli_prepare($con, $deleteVoteQuery);
    mysqli_stmt_bind_param($deleteVoteStmt, 'ii', $userId, $storyId);
    mysqli_stmt_execute($deleteVoteStmt);

    $query = "INSERT INTO Bid_votes (voted_by, voted_on_story, voted_on_bid, bid_upvote) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'iiii', $userId, $storyId, $bidId, $upvote);
    if(mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "failed";
    }
    
?>