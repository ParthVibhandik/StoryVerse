// This file is used for the pop up for the followers, and following box in accountDetails.php

$(document).ready(function () {
    // Show Followers Pop-up
    $("#show-followers").click(function () {
        $("#followers-popup").fadeIn();

        popupActive();
    });

    // Show Following Pop-up (Similar to Followers)
    $("#show-following").click(function () {
        $("#following-popup").fadeIn();

        popupActive();
    });

    // Close Pop-up
    $("#close-followers-popup, .overlay").click(function () {
        $("#followers-popup").fadeOut();

        popupInActive();
    });
    
    $("#close-following-popup, .overlay").click(function () {
        $("#following-popup").fadeOut();

        popupInActive();
    });
});