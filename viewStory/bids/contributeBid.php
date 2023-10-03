<?php
include "../../connection.php";

    $storyId = $_POST['storyId'];
    
    // Selecting the Contribution with most votes
    $fetchMostVotedQuery = "SELECT * FROM Bid_votes WHERE voted_on_story = ? GROUP BY voted_on_bid ORDER BY COUNT(*) DESC LIMIT 1";
    $fetchMostvotedStmt = mysqli_prepare($con, $fetchMostVotedQuery);
    mysqli_stmt_bind_param($fetchMostvotedStmt, "i", $storyId);
    mysqli_stmt_execute($fetchMostvotedStmt);
    $mostVoted = mysqli_fetch_assoc(mysqli_stmt_get_result($fetchMostvotedStmt));

    if($mostVoted == '') {
        $query = "SELECT * FROM Bids WHERE story_id = ? ORDER BY bid_date ASC";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $storyId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        $mostVoted['voted_on_bid'] = $result['bid_id'];
    }

    // Fetching the text of the bid
    $fetchBidTextQuery = "SELECT bid_text FROM Bids WHERE bid_id = ?";
    $fetchBidTextStmt = mysqli_prepare($con, $fetchBidTextQuery);
    mysqli_stmt_bind_param($fetchBidTextStmt, "i", $mostVoted['voted_on_bid']);
    mysqli_stmt_execute($fetchBidTextStmt);
    $bidText = mysqli_fetch_assoc(mysqli_stmt_get_result($fetchBidTextStmt))['bid_text'];

    // Inserting the selected bid into the Contributions table
    // First, fetching the user who owns the bid, using voted_on_bid, in bids table
    $fetchBidOwnerQuery = "SELECT bid_by FROM Bids WHERE bid_id = ?";
    $fetchBidOwnerStmt = mysqli_prepare($con, $fetchBidOwnerQuery);
    mysqli_stmt_bind_param($fetchBidOwnerStmt, "i", $mostVoted['voted_on_bid']);
    mysqli_stmt_execute($fetchBidOwnerStmt);
    $bidOwner = mysqli_fetch_assoc(mysqli_stmt_get_result($fetchBidOwnerStmt))['bid_by'];
    // Now inserting into Contributions table
    $contributeQuery = "INSERT INTO Contributions (story_id, contributed_by, contribution_text) VALUES (?, ?, ?)";
    $contributeStmt = mysqli_prepare($con, $contributeQuery);
    mysqli_stmt_bind_param($contributeStmt, "iis", $storyId, $bidOwner, $bidText);
    mysqli_stmt_execute($contributeStmt);

    // Deleting the data of the discarded bids
    $deleteVotesQuery = "DELETE FROM Bid_votes WHERE voted_on_story = ?";
    $deleteVotesStmt = mysqli_prepare($con, $deleteVotesQuery);
    mysqli_stmt_bind_param($deleteVotesStmt, "i", $storyId);
    mysqli_stmt_execute($deleteVotesStmt);

    $deleteBidQuery = "DELETE FROM Bids WHERE story_id = ?";
    $deleteBidStmt = mysqli_prepare($con, $deleteBidQuery);
    mysqli_stmt_bind_param($deleteBidStmt, "i", $storyId);
    mysqli_stmt_execute($deleteBidStmt);
    
    $deleteDiscussionQuery = "DELETE FROM Bid_discussion WHERE message_on = ?";
    $deleteDiscussionStmt = mysqli_prepare($con, $deleteDiscussionQuery);
    mysqli_stmt_bind_param($deleteDiscussionStmt, "i", $storyId);
    mysqli_stmt_execute($deleteDiscussionStmt);

?>