<?php
session_start();
include "../connection.php";
include "../functions.php";

$storyId = $_GET['storyId'];
$_SESSION['story_id'] = $storyId;

$userId = $_SESSION['user_id'];

// Story Details
$fetchStoryQuery = "SELECT * FROM Stories WHERE story_id = $storyId";
$getStoryResult = mysqli_query($con, $fetchStoryQuery);
$story = mysqli_fetch_assoc($getStoryResult);        

// Fetching the username
$getUsernameQuery = "SELECT username FROM Users WHERE user_id = ?";
$usernameStmt = mysqli_prepare($con, $getUsernameQuery);
mysqli_stmt_bind_param($usernameStmt, 'i', $story['created_by']);
mysqli_stmt_execute($usernameStmt);
$username = mysqli_fetch_assoc(mysqli_stmt_get_result($usernameStmt))['username'];





function displayContributions($con, $storyId) {
    $query = "SELECT * FROM Contributions WHERE story_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $storyId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='story-part-container'>";
            $style = fetchProfilePic($con, $row['contributed_by']);
            echo "<div class='story-part-pic' style='$style'></div>";
            echo "<div class='story-part-text'>";
                echo $row['contribution_text'];
            echo "</div>";
        echo "</div>";
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Story</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../style.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" 
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" 
    crossorigin="anonymous"></script> 
    <script src="../baseURL.js"></script>
    <script src="../toggleTabs.js"></script>
    <script src="../viewProfile.js"></script>
    <script src="../navigation.js"></script>
    <script src="manageNavigation.js"></script>
    <script src="script.js"></script>
    <script src="tagsPopup.js"></script>
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
            <div id="notification"></div>
            <div class="profile-link" style="<?php fetchProfilePic($con, $userId); ?> background-size: cover">
            </div>
        </div>
    </section>
    

    <div class="wrapper">

    <!-- They grey overlay for when search is selected -->
    <div class="grey-overlay" style='display: none'></div>



    <div class="main-container">
        <div class="left-container">
            <div class="story-pic-container">
                <div id="story-pic"></div>

                <div id="change-pic-btn" ></div>
            </div>

            <div id="story-title">
                <?php echo fetchStoryDetails($con, $storyId)['story_title']; ?>
            </div>

            <div id="story-id" class='faint-text-color'>
                <?php echo "# " . fetchStoryDetails($con, $storyId)['story_id']; ?>
            </div>

            <div id="created-by" class='faint-text-color'> 
                <?php echo "Created by: @" . fetchUserDetails($con, fetchStoryDetails($con, $storyId)['created_by'])['username']; ?>
            </div>

            <div id="story-description">
                <?php echo fetchStoryDetails($con, $storyId)['story_description']; ?>
            </div>

            <div id="story-tags" class='faint-text-color'>
                <?php 
                $storyTags = fetchStoryTags($con, $storyId);
                while($storyTag = mysqli_fetch_assoc($storyTags)) {
                    echo "<div class='story-tag-box'># ";
                    echo $storyTag['tag'];
                    echo "&nbsp;&nbsp;&nbsp;</div>";
                }
                ?>
            </div>

            <div class="story-follow-container">
                <div class="story-followers" id='show-followers'>
                    <div class="followers-count">
                        <?php echo countStoryFollowers($con, $storyId); ?>
                    </div>

                    <div class="followers-label">Followers</div>
                </div>

                <div class="story-contributions" id='show-contributors'>
                    <div class="following-count">
                        <?php echo countStoryContributors($con, $storyId); ?>
                    </div>

                    <div class="following-label">Contributors</div>
                </div>            
            </div>

            <?php
            // Settings appear only if the story is created by the user itself
            if(fetchStoryDetails($con, $storyId)['created_by'] == $_SESSION['user_id']) {
                echo "<div id='story-settings' class='story-toggle' data-state='settings'>";
                echo "<button class='edit-profile' id='settings-btn'>Settings</button>";
                echo "</div>";
            } else {
                // And follow story btn appears otherwise
                // If user follows, following appears in the button
                if(checkStoryFollow($con, $storyId, $userId)) {
                    echo "<button id='story-follow-btn'>Following</button>";
                } else {
                    // else follow appears
                    echo "<button id='story-follow-btn'>Follow</button>";
                }
            }
            ?>
        </div>


        <div class="right-container">
            <div id="view-container" class='container'>
                <div class="view-tab-selector">
                    <div class="story-tab tab selected" data-target='story-container'>Story</div>
                    <div class="bids-tab tab" data-target='bids-container'>Bids</div>
                    <div class="discussion-tab tab" data-target='discussion-container'>Discussion</div>
                </div>
                <div class="view-tab-contents">
                    <div id="story-container" class='tab-content'>
                        <div class='story-part-container'>
                            <div class="story-part-pic" style="<?php fetchProfilePic($con, fetchStoryDetails($con, $storyId)['created_by']); ?>"></div>
                            <?php echo "<div class='story-part-text' id='" . $storyId . "'>" . $story['story_start'] . "</div>"; ?>
                        </div>

<!-- ************************************************* -->

<!-- ************************************************* -->

<!-- ************************************************* -->

<!-- ************************************************* -->


                        <?php displayContributions($con, $storyId); ?>


<!-- ************************************************* -->

<!-- ************************************************* -->

<!-- ************************************************* -->

<!-- ************************************************* -->

                    </div>
                    <div id="bids-container" class='tab-content' style='display: none;'>Bids</div>
                    <div id="discussion-container" class='tab-content' style='display: none;'>Discussion</div>
                </div>
                
            </div>

            <!-- Settings container -->
            <div id='settings-container' style='display: none'>
                <div class="edit-description-container container">
                    <div class="label">Story Description: </div>
                    <textarea type='text' id='edit-description-field'>
                        <?php echo fetchStoryDetails($con, $storyId)['story_description']; ?>
                    </textarea>
                </div>
                <div class="edit-tags-container container">
                    <div class="label">Story Tags: </div>
                    <div class="edit-tags-box">
                        <div id="tag-display">
                            <?php
                            $storyTags = fetchStoryTags($con, $storyId);
                            while($storyTag = mysqli_fetch_assoc($storyTags)) {
                                echo "<div class='edit-tag-display-box' id='" . $storyTag['story_tag_id'] . "'># ";
                                echo $storyTag['tag'];
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <div class="enter-tag-box">
                            <input type='text' id='enter-tag-field' placeholder='Add new tags...'>
                            <button id='enter-tag-btn'>Add</button>
                            <div id="tag-count">
                                <?php echo countStoryTags($con, $storyId) . "/5"; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="edit-ui-container">
                    <button id="end-story-btn">End Story</button>
                </div>
            </div>
        </div>
    </div>




    <?php
        
        
        // Contributions
        $fetchContriQuery = "SELECT * FROM Contributions WHERE story_id = ?";
        $fetchContriStmt = mysqli_prepare($con, $fetchContriQuery);
        mysqli_stmt_bind_param($fetchContriStmt, "i", $storyId);
        mysqli_stmt_execute($fetchContriStmt);
        $row = mysqli_stmt_get_result($fetchContriStmt);
        while ($contribution = mysqli_fetch_assoc($row)) {
            $username = fetchUserDetails($con, $contribution['contributed_by'])['username'];
            echo "<b class='account-username'>" . $username . "</b>"; 
            echo "<br>";
            echo $contribution['contribution_text'];
            echo "<br><br>";
        }

        // Bid button
        if(checkOwnStory($con, $storyId)) {
            // Do not create view bid button if own story
        } else {
            if(alreadyBidded($con, $storyId)) {
                // Do not create view bid button if user already has bidded
            } else {
                echo "<div id='bid-container'>";
                    echo "<input type='text' id='bid-text'>";
                    echo "<button id='bid-btn'>BID</button>";
                echo "</div>";
            }            
        }
    ?>

    <br><br>
    <button id='view-bids-btn'>VIEW BID</button>
    <br><br>
    
    <div id='status'></div>

    <div class='discussion-container' id='discussion'>
        <h2>Discussion</h2>
        <div class="discussion-box">
            <?php
                // Fetching the Discussions
                $fetchDiscussionQuery = "SELECT * FROM Contribution_discussion WHERE message_on = ?";
                $fetchDiscussionStmt = mysqli_prepare($con, $fetchDiscussionQuery);
                mysqli_stmt_bind_param($fetchDiscussionStmt, "i", $storyId);
                mysqli_stmt_execute($fetchDiscussionStmt);
                $discussionResult = mysqli_stmt_get_result($fetchDiscussionStmt);

                while ($discussion = mysqli_fetch_assoc($discussionResult)){
                    $username = fetchUserDetails($con, $discussion['message_by'])['username']; 

                    echo "<b class='account-username'>" . $username . "</b>";
                    echo "<br>";
                    echo $discussion['message_text'];
                    
                    // Checking if the discussion is of the user who is logged in
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
    </div>

    <div id='status'></div>





                


    <!-- Container for End Story confirmation popup -->
    <div class="popup-container" id='end-story-popup'>
        <div class="popup-header">End story</div>
        <div class="popup-content">
            <p>Trigger for the last 3 contributions</p>
            <p>All the followers will be notified</p>
        </div>
        <div class="close-button" id="close-end-story-popup">Cancel</div>
        <div class="close-button" id="confirm-end-story-btn">Delete</div>
    </div>

    <!-- Container for Followers Pop-up -->
    <div class="popup-container" id="followers-popup">
        <div class="popup-content">
            <p>Followers</p>
            <div class="following-list" id='follower-list'>
                <?php fetchStoryFollowers($con, $storyId); ?>
            </div>
        </div>
        <div class="close-button" id="close-followers-popup">Close</div>
    </div>

    <!-- Container for Followers Pop-up -->
    <div class="popup-container" id="contributors-popup">
        <div class="popup-content">
            <p>Contributors</p>
            <div class="following-list" id='follower-list'>
                <?php fetchStoryContributors($con, $storyId); ?>
            </div>
        </div>
        <div class="close-button" id="close-contributors-popup">Close</div>
    </div>


    </div>
</body>
</html>

