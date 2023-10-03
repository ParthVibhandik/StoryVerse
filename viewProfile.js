// Attach this file to wherever there are usernames on the page, and the user should be redirected to their profile
// Add the class account-username to every username for this to work
// This file redirects the user to the accountDetails page of any user they have clicked
// This file gets the username of the account the user has clicked
// This username is posted to a php file which returns the userId of the user with a link to accountDetails
// This file then redirects to that link
// Link the baseUrl.js file too for this to work

$(document).ready(function() {
    $(".account-username").click(function() {
        // Stope the event associated to the parent element
        event.stopPropagation();
        var username = $(this).html();
        $.post(baseUrl + "/getUserId.php", {
            username: username
        }, function(data) {
            // Redirecting to as link received in form of data
            window.location.href = baseUrl + data;
        })
    })

    // This code is repeated, but attached to a different event listener
    // This is done to be compatible with the dynamically created usersnames in search containers
    $("#search-user-output").on("click", ".account-username", function() {
        var username = $(this).html();
        $.post(baseUrl + "/getUserId.php", {
            username: username
        }, function(data) {
            // Redirecting to as link received in form of data
            window.location.href = baseUrl + data;
        })
    })
})