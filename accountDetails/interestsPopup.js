// This file is used for the pop up for the interests box in accountDetails.php

$(document).ready(function() {
    var userId = $('#user-id').text().match(/\d+/)[0];

    // Open popup
    $("#interest-container-main").on("click", "#add-interest", function() {
        $("#interest-popup").fadeIn();

        popupActive();
    })

    // Close popup
    $("#close-interest-popup, .overlay").click(function () {
        $("#interest-popup").fadeOut();

        $.post("updateInterests.php", {
            userId: userId
        }, function(data) {
            $("#interest-container-main").html(data);
        })
        
        popupInActive();
    });
})