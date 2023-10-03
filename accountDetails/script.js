// This file contains the general JavaScript for the accountDetails.php

$(document).ready(function() {
    var userId = $('#user-id').text().match(/\d+/)[0];

    $("#confirm-delete-btn").click(function() {
        window.location.href = "deleteAccount.php";
    })

    $(".link-story").click(function() {
        var storyId = $(this).attr('id');
        window.location.href = baseUrl + "/viewStory/viewStory.php?storyId=" + encodeURIComponent(storyId);
    })

    $(".follow-user-btn").click(function() {
        var followThisUser = $(this).attr('id').match(/\d+/)[0];
        $.post("followUser.php", {
            accToBeFollowed: followThisUser
        }, function(data) {
            $("#follow-user" + followThisUser).html(data);
        })
        // This is inverted, ie, when follow, add follow inactive
        if($(this).text() == "Follow") {
            $(this).addClass('follow-active');
            $(this).removeClass('follow-inactive');
        } else {
            $(this).addClass('follow-inactive');
            $(this).removeClass('follow-active');
        }
    })    

    $(".remove-follower-btn").click(function() {
        var followerToRemove = $(this).attr('id').match(/\d+/)[0];
        $.post("removeFollower.php", {
            followerToRemove: followerToRemove
        }, function(data) {
            $("#follower-list").html(data);
        })
    })

    $(".interest-container").click(function() {
        var interestId = $(this).attr('id').match(/\d+/)[0];

        $.post("interestManagement.php", {
            interestId: interestId
        }, function(data) {
            $("#interest-status").html(data);
        })

        // Add the inactive class if active and vice versa
        // check if the status 10/10
        if($.trim($("#interest-status").text()) == '10/10') {
            // Dont add active class even if clicked
            // But can remove class from others
            if($(this).hasClass("interest-active")) {
                $(this).removeClass("interest-active");
            }
        } else {
            // Can add or remove without hindrance
            if($(this).hasClass("interest-active")) {
                $(this).removeClass("interest-active");
            } else {
                $(this).addClass("interest-active");
            }
        }
    })

    $("#logout").click(function() {
        window.location.href="../login/logout.php";
    })

    // Updating the followers, and following counts
    $(".close-button").click(function() {
        $.post("updateFollowing.php", function(data) {
            $(".following-count").html(data);
        })
    })
})