<?php
include "../connection.php";
include "../functions.php";

$userId = $_GET['userId'];

$getUserDataQuery = "SELECT * FROM Users WHERE user_id = ?";
$getUserDataStmt = mysqli_prepare($con, $getUserDataQuery);
mysqli_stmt_bind_param($getUserDataStmt, 'i', $userId);
mysqli_stmt_execute($getUserDataStmt);
$userData = mysqli_fetch_assoc(mysqli_stmt_get_result($getUserDataStmt));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../style.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" 
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" 
    crossorigin="anonymous"></script>  
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

    <!-- Wrapper is everything except the header -->
    <div class="wrapper">

    <div id="back-btn"></div>

    <!-- They grey overlay for when search is selected -->
    <div class="grey-overlay" style='display: none'></div>

    <section class='user-details'>

        <!-- The left container has the profile pic and details and basic ui -->
        <div class="left-container">
            <!-- Profile pic -->
            <div class="profile-pic-container">
                <div class="profile-pic" style="<?php fetchProfilePic($con, $userId); ?> background-size: cover"></div>

                <div id="change-pic-btn" style='display: none'></div>
            </div>
            

            <!-- Name of the user -->
            <div class="user-name">            
                <?php echo "<h2>" . $userData['name'] . "</h2>"; ?>
            </div>

            <!-- Username of the user -->
            <div class="user-username">
                <?php echo "@" . $userData['username']; ?>            
            </div>

            <!-- User id of the user -->
            <div class="user-id" id='user-id'>
                <?php                 
                echo "# " . $userId; 
                ?>
            </div>

            <!-- User follow tab -->
            <div class="user-follow-container">
                <!-- Followers -->
                <div class="user-followers" id='show-followers'>
                    <div class="followers-count">
                        <?php 
                        // Executing query to fetch the user followers
                        countFollowers($con, $userId);
                        ?>
                    </div>
                    <div class="followers-label">
                        Followers
                    </div>
                </div>

                <!-- Following -->
                <div class="user-following" id='show-following'>
                    <div class="following-count">
                        <?php 
                        // Executing query to fetch the user following
                        countFollowing($con, $userId)
                        ?>
                    </div>
                    <div class="following-label">
                        Following
                    </div>
                </div>            
            </div>

            <!-- Details ui -->
            <div class="details-ui">
                <!-- Share profile button -->
                <div>
                    <button class='share-profile'>Share</button>
                </div>

                <!-- Edit profile, delete account -->
                <!-- OR -->
                <!-- Follow, following status -->
                <?php 
                // If own account, display the edit-profile button, and delete account button
                if($userId == $_SESSION['user_id']) {   
                    //  Logout
                    echo "<div id=logout>";
                    echo "<button id='logout'>Logout</button>";
                    echo "</div>";
                    // Edit profile
                    echo "<div class='profile-toggle' data-state='edit'>";
                    echo "<button class='edit-profile' id='edit-profile'>Edit Profile</button>";
                    echo "</div>";
                    // Delete account
                    echo "<div>";
                    echo "<button id='delete-account'>Delete Account</button>";
                    echo "</div>";
                } 
                ?>
            </div>
            <?php
            // If not own account, display the follow button
            if($userId == $_SESSION['user_id']) {
                // Nothing happens
            }
            else {
                if(checkUserFollow($con, $_SESSION['user_id'], $userId)) {
                    // Display FOLLOWING if the user follows
                    echo "<div>";
                    echo "<div class='follow-user-btn follow-in-left-container follow-active' id='user" . $userId . "'>Following</div>";
                    echo "</div>";
                } else {
                    // Display FOLLOW
                    echo "<div>";
                    echo "<div class='follow-user-btn follow-in-left-container follow-inactive' id='user" . $userId . "'>Follow</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>
        
        <!-- The right container has the About me and contribution/created tabs -->
        <div class="right-container">
            <!-- About and interest -->
            <?php
            if($userData['about'] != '' || countUserInterest($con, $userId) != 0) {
                echo "<div class='about-interest-container'>";
                // About
                echo "<div class='about'>";
                if($userData['about'] != '') {
                    // Display about only if not empty
                    echo "<div class='label'>";
                    echo "About " . $userData['name'] . ":";
                    echo "</div>";

                    echo "<div class='content'>";
                    echo $userData['about'];
                    echo "</div>";
                    
                } else {
                    if($userId == $_SESSION['user_id']) {
                        // If own account, display the button to edit profile
                        echo "<a href='editProfile/editProfile.php'>Add About</a>";
                    }
                }
                echo "</div>";

                // Interests
                echo "<div id='interest-container-main'>"; 
                displayInterests($con, $userId);
                echo "</div>";

            } else {
                if($userId == $_SESSION['user_id']) {
                    echo "<div class='about-interest-container'>";
                    echo "<div class='about'>";
                    echo "<a href='editProfile/editProfile.php'>Add About</a>";
                    echo "</div>";
                    echo "<div class='interest'>";
                    echo "<div class='label'>What I write: </div>";
                    echo "<div id='add-interest'></div>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>            

            <!-- Created/Contributed section -->
            <div class="created-contributed-container">
                <div class="tabs">
                    <div class="created tab selected" data-target="created">
                        <div class="amt">
                        <!-- Fetch the amount of stories created -->
                        <?php
                            $countCreatedQuery = "SELECT COUNT(*) AS count_created FROM Stories WHERE created_by = ?";
                            $countCreatedStmt = mysqli_prepare($con, $countCreatedQuery);
                            mysqli_stmt_bind_param($countCreatedStmt, "i", $userId);
                            mysqli_stmt_execute($countCreatedStmt);
                            $countCreated = mysqli_fetch_assoc(mysqli_stmt_get_result($countCreatedStmt))['count_created'];
                            echo $countCreated;
                        ?>
                        </div>
                        <div>Created</div>
                    </div>
                    <!-- Contributions -->
                    <div class="contributed tab" data-target="contributed">
                        <div class="amt">
                        <!-- Fetch the amount of stories contributed -->
                        <?php
                            $countContributedQuery = "SELECT COUNT(*) AS count_contributed FROM Contributions WHERE contributed_by = ?";
                            $countContributedStmt = mysqli_prepare($con, $countContributedQuery);
                            mysqli_stmt_bind_param($countContributedStmt, "i", $userId);
                            mysqli_stmt_execute($countContributedStmt);
                            $countContributed = mysqli_fetch_assoc(mysqli_stmt_get_result($countContributedStmt))['count_contributed'];
                            echo $countContributed;
                        ?>
                        </div>
                        <div>Contributed</div>
                    </div>
                    <!-- Following -->
                    <div class="following-story tab" data-target="following-story">
                        <div class="amt">
                        <!-- Fetch the amount of stories contributed -->
                        <?php
                            $countFollowingQuery = "SELECT COUNT(*) AS count_following FROM story_follow WHERE user_id = ?";
                            $countFollowingStmt = mysqli_prepare($con, $countFollowingQuery);
                            mysqli_stmt_bind_param($countFollowingStmt, "i", $userId);
                            mysqli_stmt_execute($countFollowingStmt);
                            $countFollowing = mysqli_fetch_assoc(mysqli_stmt_get_result($countFollowingStmt))['count_following'];
                            echo $countFollowing;
                        ?>
                        </div>
                        <div>Following</div>
                    </div>
                </div>

                <div class="tab-content" id="created">
                    <?php
                    $createdQuery = "SELECT * FROM Stories WHERE created_by = ?";
                    $createdStmt = mysqli_prepare($con, $createdQuery);
                    mysqli_stmt_bind_param($createdStmt, "i", $userId);
                    mysqli_stmt_execute($createdStmt);
                    $createdResult = mysqli_stmt_get_result($createdStmt);

                    if($createdResult) {
                        while ($createdStories = mysqli_fetch_assoc($createdResult)) {
                            echo "<div class='created-story-box link-story' id='" . $createdStories['story_id'] ."'>";
                            echo "<p class='story-title'>" . $createdStories['story_title'] . "</p>";
                            echo "<p class='story-start'>" . $createdStories['story_start'] . "</p>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
                <div class="tab-content" id="contributed" style="display: none;">
                    <?php
                    $contributedQuery = "SELECT * FROM Contributions WHERE contributed_by = ?";
                    $contributedStmt = mysqli_prepare($con, $contributedQuery);
                    mysqli_stmt_bind_param($contributedStmt, "i", $userId);
                    mysqli_stmt_execute($contributedStmt);
                    $contributedResult = mysqli_stmt_get_result($contributedStmt);

                    if($contributedResult) {
                        while ($contributedStories = mysqli_fetch_assoc($contributedResult)) {                           
                            // Fetch the story title of the contribution
                            $fetchStoryTitleQuery = "SELECT story_title from Stories WHERE story_id = ?";
                            $fetchStoryTitleStmt = mysqli_prepare($con, $fetchStoryTitleQuery);
                            mysqli_stmt_bind_param($fetchStoryTitleStmt, "i", $contributedStories["story_id"]);
                            mysqli_stmt_execute($fetchStoryTitleStmt);
                            $storyTitle = mysqli_fetch_assoc(mysqli_stmt_get_result($fetchStoryTitleStmt))['story_title'];

                            echo "<div class='contributed-story-box link-story' id='" . $contributedStories['story_id'] ."'>";
                            echo "<p class='story-title'>" . $storyTitle . "</p>";
                            echo "<p class='story-start'>" . $contributedStories['contribution_text'] . "</p>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
                <div class="tab-content" id="following-story" style='display: none'>
                    <?php
                    $followingStoryQuery = "SELECT * FROM story_follow WHERE user_id = ?";
                    $followingStoryStmt = mysqli_prepare($con, $followingStoryQuery);
                    mysqli_stmt_bind_param($followingStoryStmt, "i", $userId);
                    mysqli_stmt_execute($followingStoryStmt);
                    $followingStoryResult = mysqli_stmt_get_result($followingStoryStmt);

                    if($followingStoryResult) {
                        while ($followingStoryStories = mysqli_fetch_assoc($followingStoryResult)) {
                            $fetchStoryDetailsQuery = "SELECT * FROM Stories WHERE story_id = ?";
                            $fetchStoryDetailsStmt = mysqli_prepare($con, $fetchStoryDetailsQuery);
                            mysqli_stmt_bind_param($fetchStoryDetailsStmt, "i", $followingStoryStories['story_id']);
                            mysqli_stmt_execute($fetchStoryDetailsStmt);
                            $storyDetails = mysqli_fetch_assoc(mysqli_stmt_get_result($fetchStoryDetailsStmt));

                            echo "<div class='created-story-box link-story' id='" . $storyDetails['story_id'] ."'>";
                            echo "<p class='story-title'>" . $storyDetails['story_title'] . "</p>";
                            echo "<p class='story-start'>" . $storyDetails['story_start'] . "</p>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="edit-profile-container" style='display: none'>
                <div class="edit-fields">
                    <div class="edit-personal-information-container">
                        <span class="edit-label">Personal information</span>
                        <div class="edit-name">
                            <label>Name</label>
                            <input id='name-field' type='text' value='<?php echo $userData['name']; ?>'>
                        </div>
                        <div class="edit-email">
                            <label>Email</label>
                            <input id='email-field' type='text' value='<?php echo $userData['email']; ?>'>
                        </div>
                    </div>

                    <div class="edit-login-details-container">
                        <span class="edit-label">Login details</span>
                        <div class="edit-username">
                            <label>Username</label>
                            <input id='username-field' type='text' value = '<?php echo $userData['username']; ?>' >                
                        </div>

                        <div class="edit-password">
                            <label>Password</label>
                            <input id='password-field' type='text' value = '<?php echo $userData['password']; ?>' >                
                        </div>
                    </div>

                    <div class="edit-profile-details-container">
                        <span class="edit-label">Profile details</span>
                        <div class="edit-about">
                            <label>About</label>
                            <input id='about-field' type='text' value = '<?php echo $userData['about']; ?>' >                
                        </div>
                    </div>
                </div>
                
                <div class="edit-ui">
                    <button id="edit-profile-save" class='edit-unclickable'>Save</button>
                    <button id="edit-profile-reset" class='edit-unclickable'>Reset</button>
                </div>
            </div>

        </div>
    </section>

    <!-- Container for Followers Pop-up -->
    <div class="popup-container" id="followers-popup">
        <div class="popup-content">
            <p>Followers</p>
            
            <div class="following-list" id='follower-list'>
                <?php
                fetchFollowers($con, $userId);
                ?>
            </div>

        </div>
        <div class="close-button" id="close-followers-popup">Close</div>
    </div>

    <!-- Container for Following Pop-up -->
    <div class="popup-container" id="following-popup">
        <div class="popup-content">
            <p>Following</p>
            
            <div class="following-list">
            <?php
                // Executing query to fetch following
                $fetchFollowingQuery = "SELECT * FROM user_follow WHERE user_id = ?";
                $fetchFollowingStmt = mysqli_prepare($con, $fetchFollowingQuery);
                mysqli_stmt_bind_param($fetchFollowingStmt, "i", $userId);
                mysqli_stmt_execute($fetchFollowingStmt);
                $followingList = mysqli_stmt_get_result($fetchFollowingStmt);
                while ($followingUser = mysqli_fetch_assoc($followingList)) {
                    echo "<div class='follower-display-container'>";
                    echo "<div class='displayed-user-details'>";
                    // For the user's pic
                    $displayedUserPic = fetchUserDetails($con, $followingUser['follows_user_id'])['profile_picture_url'];
                    if($displayedUserPic == 0) {
                        echo "<div class='displayed-user-pic' style='background-size: cover; background-repeat: no-repeat; background-image: url(pics/default.jpg)'></div>";
                    } else {
                        echo "<div class='displayed-user-pic' style='background-size: cover; background-repeat: no-repeat; background-image: url(" . $displayedUserPic . ")'></div>";    
                    }
                    echo "<div class='account-username'>" . fetchUserDetails($con, $followingUser['follows_user_id'])['username'] . "</div>";
                    echo "</div>";

                    // Check if the user being displayed is the user own
                    if ($_SESSION['user_id'] == $followingUser['follows_user_id']) {
                        // Dont display button in this case
                    } else {
                        // Check if the user follows the displayed user
                        if (checkUserFollow($con, $_SESSION['user_id'], $followingUser['follows_user_id'])) {
                            echo "<div class='follow-user-btn follow-active' id='user" . $followingUser['follows_user_id'] . "'>Following</div>";
                        } else {
                            echo "<div class='follow-user-btn follow-inactive' id='user" . $followingUser['follows_user_id'] . "'>Follow</div>";
                        }
                    }            
                    echo "</div>";
                }
            ?>
            </div>
        </div>
        <div class="close-button" id="close-following-popup">Close</div>
    </div>

    <!-- Container for interest popup -->
    <div class="popup-container" id="interest-popup">
        <div class="popup-content">
            <div class="popup-header">
                <p>Interests</p>
                <div id="interest-status">
                    <?php
                    echo countUserInterest($con, $userId) . "/10";
                    ?>
                </div>
            </div>
            <div class="all-interests">
                <!-- Fetching all the interests from the database -->
                <?php
                $fetchAllInterestsQuery = "SELECT * FROM Interests";
                $fetchAllInterestsResult = mysqli_query($con, $fetchAllInterestsQuery);
                while ($allInterests = mysqli_fetch_assoc($fetchAllInterestsResult)) {
                    // Check if interest is already there
                    if(checkUserInterest($con, $userId, $allInterests['interest_id'])) {
                        // If interest is already added, add the active class to the container
                        echo "<div class='interest-container interest-active' id='interest-" . $allInterests['interest_id'] . "'>";
                        echo "<div class='interest-pic' style='background-image: url(" . $allInterests['interest_pic_url'] . ")'></div>";
                        echo "<div class='interest-name'>" . $allInterests['interest_name'] . "</div>";
                        echo "</div>";
                    } else {
                        // Dont add the selected class here
                        echo "<div class='interest-container' id='interest-" . $allInterests['interest_id'] . "'>";
                        echo "<div class='interest-pic' style='background-image: url(" . $allInterests['interest_pic_url'] . ")'></div>";
                        echo "<div class='interest-name'>" . $allInterests['interest_name'] . "</div>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
        <div class="close-button" id="close-interest-popup">Done</div>
    </div>

    <!-- Container for delete account confirmation popup -->
    <div class="popup-container" id='delete-popup'>
        <div class="popup-header">Delete account</div>
        <div class="popup-content">
            <p>All your data will be deleted</p>
            <p>Are you sure you want to delete this account?</p>
        </div>
        <div class="close-button" id="close-delete-popup">Cancel</div>
        <div class="close-button" id="confirm-delete-btn">Delete</div>
    </div>

    <!-- Container for create story popup -->
    <div class="popup-container" id='create-story-popup'>
        <div class="popup-header">Create Story</div>
        <div class="popup-content">
        
            <div class="create-story-inputs">
                <div><input type="text" id='story-title' placeholder='Title...'></div>
                <div><textarea type="text" id='story-start' placeholder='Story...'></textarea></div>
            </div>
            
            <div class='create-story-dialogue'>
                <div id="create-story-status"></div>
                <div id="create-story-word-container">
                    <div class="label">Words: </div>
                    <div id='create-story-word-display' class='create-story-word-display'>
                        <span class="min-story-words">20</span>
                        <span class="smallerThan"><</span>
                        <span id="create-story-word-counter" class="incorrect-amt-words">0</span>
                        <span class="smallerThan"><</span>
                        <span class="min-story-words">30</span>
                    </div>
                </div>
            </div>
        </div>
        <div class='close-button cant-post' id="post-story-btn">Post</div>
        <div class="close-button" id="close-create-story-popup">Cancel</div>
    </div>


    <!-- Container for change profile pic popup -->
    <div class="popup-container" id='change-pic-popup'>
        <div class="popup-header">Change profile pic</div>
        <div class="popup-content">
            <div class="current-pic-container">
                <div class="current-pic" style="<?php fetchProfilePic($con, $userId); ?>"></div>
            </div>

                    
            <div class="background-foreground-container">
                <div class="edit-pic-choose-file">
                <form id="photoUploadForm" action="uploadProfilePic.php" method="post" enctype="multipart/form-data">
                    <label for="edit-pic-input" id='label-edit-pic-input'>
                        <input type="file" name="profile-pic" id="edit-pic-input">
                        Choose photo
                    </label>
                    
                    <input type="submit" value="Upload" id='edit-pic-upload' style='display: none'>
                </form>                    
                </div>
                <div class="background-foreground">
                    <div id="background-container">
                        <div class='preset-bg-color' style='background-color: #96fde1;'></div>
                        <div class='preset-bg-color' style='background-color: #fd96b2;'></div>
                        <div class='preset-bg-color' style='background-color: #96b2fd;'></div>
                        <div class='preset-bg-color' style='background-color: #FFC0CB;'></div>
                        <div class='preset-bg-color' style='background-color: #FFD700;'></div>
                        <div class='preset-bg-color' style='background-color: #98FB98;'></div>
                        <div class='preset-bg-color' style='background-color: #ADD8E6;'></div>
                        <div class='preset-bg-color' style='background-color: #FFA07A;'></div>
                        <div class='preset-bg-color' style='background-color: #87CEEB;'></div>
                        <div class='preset-bg-color' style='background-color: #F0E68C;'></div>
                        <div class='preset-bg-color' style='background-color: #FF69B4;'></div>
                        <div class='preset-bg-color' style='background-color: #00FF7F;'></div>
                        <div class='preset-bg-color' style='background-color: #E0FFFF;'></div>
                        <div class='preset-bg-color' style='background-color: #FFE4C4;'></div>
                        <div class="color-picker-container">
                            <input type='color' id='edit-pic-color-picker'>
                        </div>
                    </div>
                    <div id="foreground-container" style='display: none'>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/1.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/2.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/3.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/4.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/5.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/6.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/7.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/8.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/9.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/10.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/11.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/12.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/13.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/14.png")'></div>
                        <div class="preset-fg-pic" style='background-image: url("PresetFg/15.png")'></div>
                    </div>
                </div>
                <div class="background-foreground-ui">
                    <div id="bg-fg-prev" class="bg-fg-ui-unclickable">Prev</div>
                    <div id="bg-fg-nxt" class="bg-fg-ui-clickable">Next</div>
                </div>
            </div>
        </div>
        <div class="close-button" id="close-change-pic-popup">Cancel</div>
        <div class="close-button" id="save-change-pic-btn">Save</div>
    </div>



    </div>
</body>
<script src="editProfileManagement.js"></script>
<script src="../viewProfile.js"></script>
<script src="script.js"></script>
<script src="../navigation.js"></script>
<script src="../toggleTabs.js"></script>
<script src="expandStoryBox.js"></script>
<script src="followersPopup.js"></script>
<script src="../baseURL.js"></script>
<script src="interestsPopup.js"></script>
<script src="deletePopup.js"></script>
</html> 