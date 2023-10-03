// This file contains the general JavaScript for viewStory.php

$(document).ready(function() {    
    var storyId = $(".initial").attr('id');

    $("#bid-btn").click(function() {
        var bid = $("#bid-text").val();
        $.post('bids/addBid.php', {
            bid: bid,
            storyId: storyId
        }, function(data) {
            $("#status").html(data);
            if(data == "Successfully bidded") {
                $("#bid-container").remove();
            }
        });
    })

    $("#view-bids-btn").click(function() {        
        window.location.href = "bids/viewBids.php?storyId=" + encodeURIComponent(storyId);
    })    

    $("#post-discussion").click(function() {
        var message = $("#discussion-input").val();
        $.post('postDiscussion.php', {
            storyId: storyId,
            message: message
        })
        $("#discussion-input").val("");
    })

    $(".delete-discussion-btn").click(function() {
        var messageId = $(this).attr('id');
        $.post("deleteDiscussion.php", {
            messageId: messageId
        }, function(data) {
            $("#status").html(data);
        })
    })

    $("#story-follow-btn").click(function() {
        var storyId = $("#story-id").html().match(/\d+/)[0];
        $.post("followStory.php", {
            storyId: storyId
        }, function(data) {
            $("#story-follow-btn").html(data);
        })
    })

    // Add Tag
    $("#enter-tag-btn").click(function() {
        var tag = $("#enter-tag-field").val().toLowerCase();

        // For adding the tag, and displaying it in the conatiner
        if(tag != '') {
            $.post("addTag.php", {
                storyId: storyId,
                tag: tag
            }, function(data) {
                $("#tag-display").html(data);
    
                // Clear the text field
                $("#enter-tag-field").val('');
    
                // Updating tag count
                $.post("updateTagCount.php", {
                    storyId: storyId
                }, function(data) {
                    $("#tag-count").html(data);
                })
            })
        }
    })

    // Delete Tag
    $("#tag-display").on("click", ".edit-tag-display-box", function() {
        var tagId = $(this).attr('id').match(/\d+/)[0];

        $.post("deleteTag.php", {
            storyId: storyId,
            tagId: tagId
        }, function(data) {
            // Update the existing tags and tag count after the deletion is successful
            $("#tag-display").html(data);

            // Updating tag count
            $.post("updateTagCount.php", {
                storyId: storyId
            }, function(data) {
                $("#tag-count").html(data);
            })
        });
    });

    // When the pop up for tags is closed, update the container in the body to reflect changes made
    $("#close-tag-popup").click(function() {
        $("#tags-popup").fadeOut();

        $.post("updateTags.php", {
            storyId: storyId
        }, function(data) {
            $("#story-tags").html(data);
        })

        // Also clear any errors displayed
        $(".tags-error").html('');
            
    })

})