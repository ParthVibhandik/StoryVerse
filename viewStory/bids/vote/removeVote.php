<?php
session_start();
include "../../../connection.php";

    $userId = $_SESSION['user_id'];
    $bidId = $_POST['bidId'];

    $query = "DELETE FROM Bid_votes WHERE voted_by = ? AND voted_on_bid = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $userId, $bidId);
    if(mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "failed";
    }
    
?>