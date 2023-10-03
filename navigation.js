// This file contains the navigation system of the website including the header

$(document).ready(function() {

    // Posting the story when post is clicked in create story popup
    $("#post-story-btn").click(function() {
        // Only if the button is clickable, ie all creiteria is met
        if($(this).hasClass('can-post')) {
            var storyTitle = $("#story-title").val();
            var storyStart = $("#story-start").val();
            $.post(baseUrl + "/postStory.php", {
                storyTitle: storyTitle,
                storyStart: storyStart
                }, function(data) {
                    $("#create-story-status").html(data);

                    setTimeout(() => {
                        $("#story-title").val('');
                        $("#story-start").val('');

                        popupInActive();

                        $("#create-story-status").html('');
                        $("#create-story-word-counter").html('0');

                        $("#post-story-btn").removeClass("can-post");
                        $("#post-story-btn").addClass("cant-post");

                        $("#create-story-word-counter").removeClass('correct-amt-words');
                        $("#create-story-word-counter").addClass('incorrect-amt-words');

                    }, 1000);
                }
            )
        }
    })

    // Counting the words in story-start in create-story popup container
    $("#story-start").keyup(function() {
        canPostStory();
    })
    $("#story-title").keyup(function() {
        canPostStory();
    })


    // This button redirects to the last page
    $("#back-btn").click(function() {
        window.history.back();
    })

    // Clicking on the logo, redirects to the home page
    $("#logo").click(function() {
        window.location.href = baseUrl + "/home/home.php";
    })

    // Popup for create-story
    $("#create-story-btn").click(() => {
        $("#create-story-popup").fadeIn();

        popupActive();
    })

    // Close the create-story popup
    $("#close-create-story-popup").click(function() {
        popupInActive();
    })

    // Link to the users own profile
    $(".profile-link").click(function() {
        $.post(baseUrl + "/navigateOwnAccount.php", {
            baseUrl: baseUrl
        }, function(data) {
            window.location.href = data;
        });
    })

    // Event listener for when clicked from search box
    $("#search-story-output").on("click", ".link-story", function() {
        var storyId = $(this).attr('id');
        window.location.href = baseUrl + "/viewStory/viewStory.php?storyId=" + encodeURIComponent(storyId);
    })

    // Fade in the search content when clicked on search box
    $("#search-box").click(function() {
        $("#search-content").fadeIn();

        // Remove the other popups
        $(".popup-container").fadeOut();

        // add the overlay to the body
        $(".grey-overlay").fadeIn();
    })

    // Fade out when close is clicked
    $("#close-search-btn").click(function() {
        $("#search-content").fadeOut();
        $(".grey-overlay").fadeOut();

        // Remove other popups if any
        $(".popup-container").fadeOut();
    })

    // Toggle between search tabs
    // For explanation, view accountDetails/postToggle.js
    showTab("search-user-output");
    $(".search-tab").click(function() {
        $(".search-tab").removeClass("active");
        $(this).addClass("active");

        $(".search-tab-content").hide();
        var target = $(this).data('target');
        showTab(target);
    })

    // When the user searches using the search box
    $("#search-box").keyup(function() {
        var currentSearch = $(this).val();

        if(currentSearch != '') {
            // For searching user
            $.post(baseUrl + "/searchUser.php", {
                baseUrl: baseUrl,
                currentSearch: currentSearch
            }, function(data) {
                $("#search-user-output").html(data);
            })
    
            // For searching story
            $.post(baseUrl + "/searchStory.php", {
                currentSearch: currentSearch
            }, function(data) {
                $("#search-story-output").html(data);
            })
        } 
    })
    
    // Fade out the popups when clicked elsewhere
    $(".grey-overlay").click(function() {
        popupInActive();
    })
})

function showTab(tabId) {
    $('#' + tabId).show();
}

// Clicking the escape key, closes every popup
$(document).keydown(function(e) {
    if(e.keyCode == 27) {
        popupInActive();
    }
})

// When the popup is active, add grey overlay and stop scroll and other elements happening
function popupActive() {
    $(".grey-overlay").fadeIn();
}

// Remove grey overlay and start scroll and other elements happening
function popupInActive() {
    $(".popup-container").fadeOut();
    $("#search-content").fadeOut();
    $(".grey-overlay").fadeOut();
}

function canPostStory() {
    var words = $("#story-start").val().split(/\s+/);
    words = words.filter(function(word) {
        return word.trim() !== '';
    });

    storyStart = false;
    storyTitle = false;

    $("#create-story-word-counter").html(words.length);

    // Check if the number of words is correct in story start
    if(words.length >= 20 && words.length <= 30) {
        $("#create-story-word-counter").removeClass('incorrect-amt-words');
        $("#create-story-word-counter").addClass('correct-amt-words');

        storyStart = true;
    } else {
        $("#create-story-word-counter").addClass('incorrect-amt-words');
        $("#create-story-word-counter").removeClass('correct-amt-words');

        storyStart = false;
    }
    // Check if title is good to go

    if($("#story-title").val() != '') {
        $("#create-story-status").html("");

        storyTitle = true;
    } else {
        $("#post-story-btn").click(function() {
            $("#create-story-status").html("Enter a title");
        })

        storyTitle = false;
    }

    if(storyStart && storyTitle) {
        $("#post-story-btn").removeClass("cant-post");
        $("#post-story-btn").addClass("can-post");
    } else {
        $("#post-story-btn").addClass("cant-post");
        $("#post-story-btn").removeClass("can-post");
    }
}