$(document).ready(function() {
    var storyId = $(".storyId").attr('id');

    $(".upvote").click(function() {
        var activeVoteElement = $(this);
        var bidId = activeVoteElement.attr('id').match(/\d+$/)[0];
        
        // Removing the selected class from all occurrences of upvote
        $(".upvote").removeClass("selected");

        $.post("vote/checkUserVote.php", {
            bidId: bidId
        }, function(data) {
            // add vote if not voted
            if(data == 0) {
                $.post("vote/addVote.php", {
                    bidId: bidId
                }, function(data) {
                    if(data == "success") {
                        activeVoteElement.addClass('selected');
                        prevSelection = bidId;
                    } else {
                        console.log(data);
                    }
                });
            }
            // remove vote if already voted
            else {
                $.post("vote/removeVote.php", {
                    bidId: bidId
                }, function(data) {
                    if(data == "success") {
                        activeVoteElement.removeClass('selected');
                    }
                })
            }
        })   
    });

    $("#contribute").click(function() {
        $.post("contributeBid.php", {            
            storyId: storyId
        }, function(data) {
            $("#status").html(data);
        })
    });

    $("#post-discussion").click(function() {
        var message = $("#discussion-input").val();
        $.post('postDiscussion.php', {
            storyId: storyId,
            message: message
        })
    });

    $(".delete-discussion-btn").click(function() {
        var messageId = $(this).attr('id');
        $.post("deleteDiscussion.php", {
            messageId: messageId
        }, function(data) {
            $("#status").html(data);
        })
    })
})