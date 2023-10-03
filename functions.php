<?php
session_start();

function checkLogin($con) {
    if(isset($_SESSION['user_id'])){

        $query = "SELECT * FROM Users where user_id = ? LIMIT 1";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($result && mysqli_num_rows($result) == 1){
            $result = mysqli_fetch_assoc($result);
            return $result;
        }

    } else {
        header("Location: ../login/signin.html");
        die;
    }
}

function fetchUserDetails($con, $id) {
    $query = "SELECT * FROM Users WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $details = (mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)));
    return $details;
}

function checkOwnStory($con, $storyId) {     
    $query = "SELECT COUNT(*) AS if_own_post FROM Stories WHERE story_id = ? AND created_by = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ii", $storyId, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $created = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['if_own_post'];
    if($created > 0) {
        return true;
    } else {
        return false;
    }
}

function alreadyBidded($con, $storyId) {
    $query = "SELECT COUNT(*) AS already_bidded FROM Bids WHERE story_id = ? AND bid_by = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ii", $storyId, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $aleradyBidded = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['already_bidded'];
    if($aleradyBidded > 0) {
        return true;
    } else {
        return false;
    }
}

function checkUserBid($con, $bidId){
    $query = "SELECT COUNT(*) AS if_voted FROM Bids WHERE bid_id = ? AND bid_by = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $bidId, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $vote = (mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)))['if_voted'];
    if($vote > 0) {
        return true;
    } else {
        return false;
    }    
}

function checkUserVote($con, $bidId) {
    $query = "SELECT * FROM bid_votes WHERE voted_on_bid = ? AND voted_by = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $bidId, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $vote = (mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)))['bid_upvote'];
    return $vote;
}

function checkAmtVotes($con, $bidId) {
    $fetchVotesQuery = "SELECT COUNT(*) AS voted_on_bid FROM Bid_votes WHERE voted_on_bid = ?";
    $fetchVotesStmt = mysqli_prepare($con, $fetchVotesQuery);
    mysqli_stmt_bind_param($fetchVotesStmt, "i", $bidId);
    mysqli_stmt_execute($fetchVotesStmt);
    $votes = mysqli_fetch_assoc(mysqli_stmt_get_result($fetchVotesStmt))['voted_on_bid'];
    return $votes;
}

function postDiscussion($con, $storyId, $message, $tableName) {
    $query = "INSERT INTO " . $tableName . " (message_text, message_on, message_by) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "sii", $message, $storyId, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
}

function fetchDiscussion($con, $storyId, $tableName) {
    $fetchDiscussionQuery = "SELECT * FROM " . $tableName . " WHERE message_on = ?";
    $fetchDiscussionStmt = mysqli_prepare($con, $fetchDiscussionQuery);
    mysqli_stmt_bind_param($fetchDiscussionStmt, "i", $storyId);
    mysqli_stmt_execute($fetchDiscussionStmt);
    $result = mysqli_stmt_get_result($fetchDiscussionStmt);
    return $result;
}

function deletePrevDp($con, $userId) {    
    $query = "SELECT profile_picture_url FROM Users WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['profile_picture_url'];            
    unlink($result);
}

function checkFollow($con, $storyId, $userId) {
    $checkFollowQuery = "SELECT * FROM story_follow WHERE story_id = ? and user_id = ?";
    $checkFollowStmt = mysqli_prepare($con, $checkFollowQuery);
    mysqli_stmt_bind_param($checkFollowStmt, "ii", $storyId, $userId);
    mysqli_stmt_execute($checkFollowStmt);
    $checkFollow = mysqli_fetch_assoc(mysqli_stmt_get_result($checkFollowStmt));
    return $checkFollow;
}

function checkUserFollow($con, $userId, $accToBeFollowed) {
    $checkFollowQuery = "SELECT follow_id FROM user_follow WHERE user_id = ? and follows_user_id = ?";
    $checkFollowStmt = mysqli_prepare($con, $checkFollowQuery);
    mysqli_stmt_bind_param($checkFollowStmt, "ii", $userId, $accToBeFollowed);
    mysqli_stmt_execute($checkFollowStmt);
    $checkFollow = mysqli_fetch_assoc(mysqli_stmt_get_result($checkFollowStmt));
    if($checkFollow) {
        return true;
    } else {
        return false;
    }
}

function checkStoryFollow($con, $storyId, $userId) {
    $query = "SELECT follow_id FROM story_follow WHERE story_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ii", $storyId, $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_fetch_assoc($result)) {
        return true;
    } else {
        return false;
    }
}

function checkUserInterest($con, $userId, $interestId) {
    $checkInterestQuery = "SELECT * FROM user_interests WHERE user_id = ? AND interest_id = ?";
    $checkInterestStmt = mysqli_prepare($con, $checkInterestQuery);
    mysqli_stmt_bind_param($checkInterestStmt, "ii", $userId, $interestId);
    mysqli_stmt_execute($checkInterestStmt);
    $checkResult = mysqli_stmt_get_result($checkInterestStmt);
    if(mysqli_num_rows($checkResult) > 0) {
        return true;
    } else {
        return false;
    }
}

function countUserInterest($con, $userId) {
    $interestCountQuery = "SELECT COUNT(*) as interest_count FROM user_interests WHERE user_id = ?";
    $interestCountStmt = mysqli_prepare($con, $interestCountQuery);
    mysqli_stmt_bind_param($interestCountStmt, "i", $userId);
    mysqli_stmt_execute($interestCountStmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($interestCountStmt))['interest_count'];
}

function checkStoryTag($con, $storyId, $tag) {
    // Returns true if the tag is found in the story
    $checkTagQuery = "SELECT * FROM story_tags WHERE story_id = ? AND tag = ?";
    $checkTagStmt = mysqli_prepare($con, $checkTagQuery);
    mysqli_stmt_bind_param($checkTagStmt, "is", $storyId, $tag);
    mysqli_stmt_execute($checkTagStmt);
    $checkResult = mysqli_stmt_get_result($checkTagStmt);
    if(mysqli_num_rows($checkResult) > 0) {
        return true;
    } else {
        return false;
    }
}

function countStoryTags($con, $storyId) {
    $tagCountQuery = "SELECT COUNT(*) as tags_count FROM story_tags WHERE story_id = ?";
    $tagCountStmt = mysqli_prepare($con, $tagCountQuery);
    mysqli_stmt_bind_param($tagCountStmt, "i", $storyId);
    mysqli_stmt_execute($tagCountStmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($tagCountStmt))['tags_count'];
}

function fetchStoryTags($con, $storyId) {
    $fetchAllTagsQuery = "SELECT * FROM story_tags WHERE story_id = ?";
    $fetchAllTagsStmt = mysqli_prepare($con, $fetchAllTagsQuery);
    mysqli_stmt_bind_param($fetchAllTagsStmt, "i", $storyId);
    mysqli_stmt_execute($fetchAllTagsStmt);
    $fetchAllTagsResult = mysqli_stmt_get_result($fetchAllTagsStmt);
    return $fetchAllTagsResult;
}

function countFollowers($con, $userId) {
    $followersCountQuery = "SELECT COUNT(*) AS followers FROM user_follow WHERE follows_user_id = ?";
    $followersCountStmt = mysqli_prepare($con, $followersCountQuery);
    mysqli_stmt_bind_param($followersCountStmt, "i", $userId);
    mysqli_stmt_execute($followersCountStmt);
    $followersCount = mysqli_fetch_assoc(mysqli_stmt_get_result($followersCountStmt))['followers'];
    echo $followersCount;
}

function countFollowing($con, $userId) {
    $followingCountQuery = "SELECT COUNT(*) AS following FROM user_follow WHERE user_id = ?";
    $followingCountStmt = mysqli_prepare($con, $followingCountQuery);
    mysqli_stmt_bind_param($followingCountStmt, "i", $userId);
    mysqli_stmt_execute($followingCountStmt);
    $followingCount = mysqli_fetch_assoc(mysqli_stmt_get_result($followingCountStmt))['following'];
    echo $followingCount;
}

function countStoryContributors($con, $storyId) {
    $query = "SELECT DISTINCT contributed_by AS contributors FROM Contributions WHERE story_id = ?";
    // $query = "SELECT COUNT(*) AS contributions FROM Contributions WHERE story_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $storyId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    // $row = mysqli_fetch_assoc($result);
    return mysqli_num_rows($result);
}

function countStoryFollowers($con, $storyId) {
    $query = "SELECT COUNT(*) AS followers FROM story_follow WHERE story_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $storyId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['followers'];
}

function displayInterests($con, $userId) {
    if(countUserInterest($con, $userId) != 0) {
        echo "<div class='interest'>";
        $fetchActiveInterestQuery = "SELECT * FROM user_interests WHERE user_id = ?";
        $fetchActiveInterestStmt = mysqli_prepare($con, $fetchActiveInterestQuery);
        mysqli_stmt_bind_param($fetchActiveInterestStmt, "i", $userId);
        mysqli_stmt_execute($fetchActiveInterestStmt);
        $resultActiveInterest = mysqli_stmt_get_result($fetchActiveInterestStmt);
        
        echo "<div class='label'>What I write: </div>";
        echo "<div class='display-interests'>";
        while($activeInterests = mysqli_fetch_assoc($resultActiveInterest)) {
            // Fetch the interest name from the interest id
            $fetchInterestDetailsQuery = "SELECT interest_name, interest_background_color, interest_pic_url FROM Interests WHERE interest_id = ?";
            $fetchInterestDetailsStmt = mysqli_prepare($con, $fetchInterestDetailsQuery);
            mysqli_stmt_bind_param($fetchInterestDetailsStmt, "i", $activeInterests['interest_id']);
            mysqli_stmt_execute($fetchInterestDetailsStmt);
            $interestDetails = mysqli_fetch_assoc(mysqli_stmt_get_result($fetchInterestDetailsStmt));
        
            echo "<div class='interest-container' style='background-color: " . $interestDetails['interest_background_color'] . "'>";
            echo "<div class='interest-pic' style='background-image: url(" . $interestDetails['interest_pic_url'] . "')></div>";
            echo "<div class='interest-name'>" . $interestDetails['interest_name'] . "</div>";
            echo "</div>";
        }
        if($userId == $_SESSION['user_id']) {
            echo "<div id='add-interest'></div>";
        }
        echo "</div>";
        echo "</div>";
    } else {
        // There are no interests, hence dont display
        // But if the account is of the users own, display the option to add
        if($userId == $_SESSION['user_id']) {
            echo "<div class='interest'>";
            echo "<div class='label'>What I write: </div>";
            echo "<div id='add-interest'></div>";
            echo "</div>";
        }
    }
    echo "</div>";
}

function fetchFollowers($con, $userId) {
    // Executing query to fetch followers
    $fetchFollowerQuery = "SELECT * FROM user_follow WHERE follows_user_id = ?";
    $fetchFollowerStmt = mysqli_prepare($con, $fetchFollowerQuery);
    mysqli_stmt_bind_param($fetchFollowerStmt, "i", $userId);
    mysqli_stmt_execute($fetchFollowerStmt);
    $followerList = mysqli_stmt_get_result($fetchFollowerStmt);
    while ($followerUser = mysqli_fetch_assoc($followerList)) {
        echo "<div class='follower-display-container'>";
        echo "<div class='displayed-user-details'>";
        // For the user's pic
        $displayedUserPic = fetchUserDetails($con, $followerUser['user_id'])['profile_picture_url'];
        if($displayedUserPic == 0) {
            echo "<div class='displayed-user-pic' style='background-size: cover; background-repeat: no-repeat; background-image: url(pics/default.jpg)'></div>";
        } else {
            echo "<div class='displayed-user-pic' style='background-size: cover; background-repeat: no-repeat; background-image: url(" . $displayedUserPic . ")'></div>";    
        }
        echo "<div class='account-username'>" . fetchUserDetails($con, $followerUser['user_id'])['username'] . "</div>";
        echo "</div>";
        
        // Check if the user being displayed is the user own
        if ($_SESSION['user_id'] == $followerUser['user_id']) {
            // Dont display button in this case
        } else {
            // Display the remove follower button
            echo "<div class='remove-follower-btn not-removed' id='user" . $followerUser['user_id'] . "'>Remove</div>";

            // Check if the user follows the displayed user
            if (checkUserFollow($con, $_SESSION['user_id'], $followerUser['user_id'])) {
                echo "<div class='follow-user-btn follow-active' id='follow-user" . $followerUser['user_id'] . "'>Following</div>";
            } else {
                echo "<div class='follow-user-btn follow-inactive' id='follow-user" . $followerUser['user_id'] . "'>Follow</div>";
            }
        }
        echo "</div>";
    }
}




function fetchProfilePic($con, $userId) {
    $loggedInUserDetails = fetchUserDetails($con, $userId);
    if($loggedInUserDetails['profile_picture_url'] == 0) {
        return 'background-image: url("../accountDetails/ProfilePics/default.jpg");';
    } else {
        $style = 'background-image: url("' . $loggedInUserDetails['profile_picture_url'] . '");';
        $style .= 'background: ' . $loggedInUserDetails['profile_picture_bg'] . ';';
        return $style;
    }
}





function fetchStoryDetails($con, $storyId) {
    $query = "SELECT * FROM Stories WHERE story_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $storyId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row;
}

function fetchStoryFollowers($con, $storyId) {
    $query = "SELECT user_id FROM story_follow WHERE story_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $storyId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row = mysqli_fetch_assoc($result)) {
        $userDetails = fetchUserDetails($con, $row['user_id']);
        echo $userDetails['username'];
    }
}

function fetchStoryContributors($con, $storyId) {
    $query = "SELECT DISTINCT contributed_by FROM Contributions WHERE story_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $storyId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='user-list-container'>";
        echo "<div class='displayed-user-details'>";
        $userDetails = fetchUserDetails($con, $row['contributed_by']);
        // For the user's pic
        if($userDetails['profile_picture_url'] == 0) {
            echo "<div class='displayed-user-pic' style='background-size: cover; background-repeat: no-repeat; background-image: url(pics/default.jpg)'></div>";
        } else {
            echo "<div class='displayed-user-pic' style='background-size: cover; background-repeat: no-repeat; background-image: url(" . $userDetails['profile_picture_url'] . ")'></div>";    
        }
        echo "<div class='account-username'>" . $userDetails['username'] . "</div>";
        echo "</div>";
    }
}

?>