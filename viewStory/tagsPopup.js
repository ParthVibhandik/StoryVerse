// This file contains the code for the popup of tags

$(document).ready(function() {
    $("#add-story-tags").click(function() {
        $("#tags-popup").fadeIn();
    })

    $("#close-tag-popup").click(function() {
        $("#tags-popup").fadeOut();
    })
})