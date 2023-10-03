// This file is used for the popup of delete account in the accountDetails.php page

$(document).ready(function() {
    $("#delete-account").click(function() {
        $("#delete-popup").fadeIn();

        popupActive();
    })

    $("#close-delete-popup").click(function() {
        $("#delete-popup").fadeOut();

        popupInActive();
    })
})