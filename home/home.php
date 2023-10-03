<?php
include "../functions.php";
include "../connection.php";

$userData = checkLogin($con);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
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
            <div class="notification">Notif</div>
            <div class="profile-link" style="background-image: url('<?php
                $loggedInUserDetails = checkLogin($con);
                if($loggedInUserDetails['profile_picture_url'] == 0) {
                    echo "accountDetails/pics/default.jpg";
                } else {
                    echo "../accountDetails/" . $loggedInUserDetails['profile_picture_url']; 
                } ?>'); background-size: cover; background-repeat: no-repeat;">
            </div>
        </div>
    </section>

    <div class="wrapper">

    <!-- They grey overlay for when search is selected -->
    <div class="grey-overlay" style='display: none'></div>

    <h1>Hello</h1>
    <br><br>
    <br><br>

    <h1>ONGOING STORIES</h1>
    <div id='status'></div>
    <div>
        <?php
            $getStoryQuery = "SELECT * FROM Stories";
            $getStoryResult = mysqli_query($con, $getStoryQuery);

            while($ongoingStories = mysqli_fetch_assoc($getStoryResult)) {

                $getUsernameQuery = "SELECT username FROM Users WHERE user_id = ?";
                $usernameStmt = mysqli_prepare($con, $getUsernameQuery);
                mysqli_stmt_bind_param($usernameStmt, 'i', $ongoingStories['created_by']);
                mysqli_stmt_execute($usernameStmt);
                $username = mysqli_fetch_assoc(mysqli_stmt_get_result($usernameStmt));

                echo "<div class='story-container' id='" . $ongoingStories['story_id'] . "'>";
                    echo "<h3 class='account-username'>" . $username['username'] . "</h3>";
                    echo $ongoingStories['story_start'] . "<br><br>";
                echo "</div>";

            }
        ?>
    </div>

    <h1>FOLLOWING STORIES</h1>
    <div>
        <?php
            // Getting the id of the stories that the user follows
            $getFollowStoryQuery = "SELECT * FROM story_follow WHERE user_id = ?";
            $getFollowStoryStmt = mysqli_prepare($con, $getFollowStoryQuery);
            mysqli_stmt_bind_param($getFollowStoryStmt, "i", $_SESSION['user_id']);
            mysqli_stmt_execute($getFollowStoryStmt);
            $getFollowStoryResult = mysqli_stmt_get_result($getFollowStoryStmt);
            
            while ($followStory = mysqli_fetch_assoc($getFollowStoryResult)) {
                // Fetching the actual story and displaying it
                $fetchFollowStoryQuery = "SELECT * FROM Stories WHERE story_id = ?";
                $fetchFollowStoryStmt = mysqli_prepare($con, $fetchFollowStoryQuery);
                mysqli_stmt_bind_param($fetchFollowStoryStmt, "i", $followStory['story_id']);
                mysqli_stmt_execute($fetchFollowStoryStmt);
                $fetchFollowStoryResult = mysqli_stmt_get_result($fetchFollowStoryStmt);

                while ($fetchFollowStory = mysqli_fetch_assoc($fetchFollowStoryResult)) {
                    $username = fetchUserDetails($con, $fetchFollowStory['created_by'])['username'];

                    echo "<div class='story-container' id='" . $fetchFollowStory['story_id'] . "'>";
                        echo "<h3 class='account-username'>" . $username . "</h3>";
                        echo $fetchFollowStory['story_start'] . "<br><br>";
                    echo "</div>";
                }
            }
            
        ?>        
    </div>
    
    </div>

</body>
<script src="../baseURL.js"></script>
<script src="../viewProfile.js"></script>
<script src="script.js"></script>
<script src="../navigation.js"></script>
</html>