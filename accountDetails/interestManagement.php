<?php
// This file is used to add and remove interests from a user profile
session_start();
include "../connection.php";
include "../functions.php";


$userId = $_SESSION['user_id'];
$interestId = $_POST['interestId'];
$countInterest = countUserInterest($con, $userId);

// First check if the interest is already in the user interests
if(checkUserInterest($con, $userId, $interestId)) {
    // If found, delete it
    $removeInterestQuery = "DELETE FROM user_interests WHERE user_id = ? AND interest_id = ?";
    $removeInterestStmt = mysqli_prepare($con, $removeInterestQuery);
    mysqli_stmt_bind_param($removeInterestStmt, "ii", $userId, $interestId);
    mysqli_stmt_execute($removeInterestStmt);
    echo $countInterest - 1 . "/10";
} else {
    // If not found, add it
    // Before adding, check if there are more than 10 interests of the user
    // If so ask them to remove some before
    if($countInterest > 9) {  
        echo $countInterest . "/10";
    } else {
        // Add if smaller than 10
        $addInterestQuery = "INSERT INTO user_interests (user_id, interest_id) VALUES (?, ?)";
        $addInterestStmt = mysqli_prepare($con, $addInterestQuery);
        mysqli_stmt_bind_param($addInterestStmt, "ii", $userId, $interestId);
        mysqli_stmt_execute($addInterestStmt);
        echo $countInterest + 1 . "/10";
    }

    
}

?>