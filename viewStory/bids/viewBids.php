<?php
session_start();
include "../../connection.php";
include "../../functions.php";

    $storyId = $_GET['storyId'];
    $userId = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bids</title>
    <link rel="stylesheet" href="voteBid.css">
    <link rel="stylesheet" href="../../style.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" 
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" 
    crossorigin="anonymous"></script>
    <style>
        .discussion-box {
            border: 1px solid #000;
            height: 300px;
            width: 500px;
            overflow: scroll;
        }
    </style>
</head>
<body>

    <section class='header'>
        <!-- Left side: logo and create -->
        <div class="left-ui">
            <div id="logo">STORYVERSE</div>
            <div class="create">
                <div id="create-story-btn">Create Story</div>
            </div>
        </div>
        
        <!-- Middle: search -->
        <div class="search-box">
            <div class="search-bar">
                <input type='text' id='search-box' placeholder='Search...'>
                <div id="close-search-btn"></div>
            </div>
            <div id="search-content" style='display: none'>
                <div class="search-tabs-container">
                    <div id="search-user-tab" class='search-tab active' data-target='search-user-output'>Account</div>
                    <div id="search-story-tab" class='search-tab' data-target='search-story-output'>Story</div>
                </div>
                <div class="search-content-display">
                    <div id="search-user-output" class='search-tab-content' style='display: none'></div>
                    <div id="search-story-output" class='search-tab-content' style='display: none'></div>
                </div>
            </div>
        </div>

        <!-- Right side: notifications and userprofile -->
        <div class="right-ui">
            <div class="notification">Notif</div>
            <div class="profile-link" style="background-image: url('<?php
                $loggedInUserDetails = checkLogin($con);
                if($loggedInUserDetails['profile_picture_url'] == 0) {
                    echo "../../accountDetails/pics/default.jpg";
                } else {
                    echo "../../accountDetails/" . $loggedInUserDetails['profile_picture_url']; 
                } ?>'); background-size: cover; background-repeat: no-repeat;">
            </div>
        </div>
    </section>

    <div class="wrapper">

    <!-- They grey overlay for when search is selected -->
    <div class="grey-overlay" style='display: none'></div>

    <button id='contribute'>CONTRIBUTE NOW</button>
    <?php
        echo "<div class='storyId' id='" . $storyId . "'>Story id = " . $storyId . "</div>";
    
        $getBidsQuery = "SELECT * FROM Bids WHERE story_id = $storyId";
        $getBidsResult = mysqli_query($con, $getBidsQuery);
        while($bid = mysqli_fetch_assoc($getBidsResult)) {
            $getUsernameQuery = "SELECT username FROM Users WHERE user_id = ?";
            $usernameStmt = mysqli_prepare($con, $getUsernameQuery);
            mysqli_stmt_bind_param($usernameStmt, 'i', $bid['bid_by']);
            mysqli_stmt_execute($usernameStmt);
            $username = mysqli_fetch_assoc(mysqli_stmt_get_result($usernameStmt))['username'];
        
            // Creating the bid container
            echo "<h5 class='account-username'>" . $username . "</h5>";
                        
            // Fetching the vote count
            echo "<div class='votes-display' id='vote-display-bidid-" . $bid['bid_id'] . "'>" . checkAmtVotes($con, $bid['bid_id']) . "</div>";
            echo "<br>";
            echo $bid['bid_text'];

            // Fetching if the user has already voted and doing the needful       
            if(checkUserBid($con, $bid['bid_id'])) {
                // Not creating display button for users own bid
            }
            else if(checkUserVote($con, $bid['bid_id']) == 1) {
                echo "<button class='upvote selected' id='upvote-" . $bid['bid_id'] . "'>UPVOTE</button>";
            } else {
                echo "<button class='upvote' id='upvote-" . $bid['bid_id'] . "'>UPVOTE</button>";
            }            
        }
    ?>

    <div id="status"></div>
    <br><br><br>

    <div class='discussion-container'>
        <h2>Discussion</h2>
        <div class="discussion-box">
            <?php 
                // Fetching the Discussions
                $discussionResult = fetchDiscussion($con, $storyId, 'Bid_discussion');

                while ($discussion = mysqli_fetch_assoc($discussionResult)){
                    $username = fetchUserDetails($con, $discussion['message_by'])['username']; 

                    echo "<b class='account-username'>" . $username . "</b>";
                    echo "<br>";
                    echo $discussion['message_text'];

                    // Checking if the discussion is of the user who is logged in
                    // To display the delete button
                    if($discussion['message_by'] == $_SESSION['user_id']) {
                        echo "<br>";
                        echo "<button id='" . $discussion['message_id'] . "' class='delete-discussion-btn'>DELETE</button>";
                    }
                                        
                    echo "<br><br>";
                }
                
            ?>
        </div>
        <div class="discussion-ui">
            <input id='discussion-input' type='text'>
            <button id='post-discussion'>POST</button>
        </div>      
        
        <div id='status'></div>
        
    </div>
    </div>

</body>
<script src="../../baseURL.js"></script>
<script src="../../viewProfile.js"></script>
<script src="../../navigation.js"></script>
<script src="viewBids.js"></script>
</html>