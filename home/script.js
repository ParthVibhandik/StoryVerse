// This file is the general JavaScript of the home.php file

$(document).ready(function() {    
    $(".story-container").click(function() {
        var storyId = $(this).attr('id');
        window.location.href = "../viewStory/viewStory.php?storyId=" + encodeURIComponent(storyId);
    })
})