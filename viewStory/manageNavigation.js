$(document).ready(function() {
    $("#settings-btn").click(function() {
        var $toggleDiv = $(this).closest(".story-toggle");
        var currentState = $toggleDiv.data("state");
        // Code for when edit is clicked, ie changing to edit mode
        if (currentState === "settings") {

            // Text on button changes
            $toggleDiv.data("state", "view");
            $(this).text("View Story");

            // Fading out the view container
            $("#view-container").fadeOut();
            // Fading in the settings section
            setTimeout(() => {
                $("#settings-container").fadeIn();
                $("#change-pic-btn").fadeIn();
            }, 500);        

        } else {
            // Changing back everything to view mode
            $toggleDiv.data("state", "settings");
            $(this).text("Settings");

            // Fading out the edit profile section
            $("#settings-container").fadeOut();
            $("#change-pic-btn").fadeOut();
            // Fading in the about and created sections
            setTimeout(() => {
                $("#view-container").fadeIn();
            }, 500);
        }
    });

    // End story popup
    $("#end-story-btn").click(function() {
        $("#end-story-popup").fadeIn();

        popupActive();
    })
    $("#close-end-story-popup").click(function() {
        $("#end-story-popup").fadeOut();

        popupInActive();
    })

    // Followers popup
    $("#show-followers").click(function() {
        $("#followers-popup").fadeIn();

        popupActive();
    })
    $("#close-followers-popup").click(function() {
        $("#followers-popup").fadeOut();

        popupInActive();
    })

    // Contributers popup
    $("#show-contributors").click(function() {
        $("#contributors-popup").fadeIn();

        popupActive();
    })
    $("#close-contributors-popup").click(function() {
        $("#contributors-popup").fadeOut();

        popupInActive();
    })
})