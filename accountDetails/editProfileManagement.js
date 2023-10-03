// This file is responsible to manage the edit profile and view profile tabs

var ogName, ogAbout, ogEmail, ogUsername;

$(document).ready(function() {
    $(".profile-toggle button").click(function() {
        var $toggleDiv = $(this).closest(".profile-toggle");
        var currentState = $toggleDiv.data("state");
        
        // Code for when edit is clicked, ie changing to edit mode
        if (currentState === "edit") {
            // Text on button changes
            $toggleDiv.data("state", "view");
            $(this).text("View Profile");

            // Fading out the about and created sections
            $(".about-interest-container").fadeOut();
            $(".created-contributed-container").fadeOut();
            // Fading in the edit profile section
            setTimeout(() => {
                $(".edit-profile-container").fadeIn();
                $("#change-pic-btn").fadeIn();
            }, 500);        

        } else {
            // Changing back everything to view mode
            $toggleDiv.data("state", "edit");
            $(this).text("Edit Profile");

            // Fading out the edit profile section
            $(".edit-profile-container").fadeOut();
            $("#change-pic-btn").fadeOut();
            // Fading in the about and created sections
            setTimeout(() => {
                $(".about-interest-container").fadeIn();
                $(".created-contributed-container").fadeIn();
            }, 500);
        }
    });

    // Loading in the original values of the fields
    ogName = $("#name-field").val();
    ogEmail = $("#email-field").val();
    ogUsername = $("#username-field").val();
    ogAbout = $("#about-field").val();    

    // Check if values are changed by user
    $("#name-field").keyup(function() {
        checkIfChanged();
    })
    $("#email-field").keyup(function() {        
        checkIfChanged();
    })
    $("#username-field").keyup(function() {        
        checkIfChanged();
    })
    $("#email-field").keyup(function() {
        checkIfChanged();
    })

    // For resetting the values
    $("#edit-profile-reset").click(function() {
        if($(this).hasClass('edit-clickable')) {
            $("#name-field").val(ogName);
            $("#username-field").val(ogUsername);
            $("#email-field").val(ogEmail);
            $("#about-field").val(ogAbout);

            makeUnclickable();
        }        
    })

    // For saving the values
    $("#edit-profile-save").click(function() {
        if($(this).hasClass('edit-clickable')) {

            var name = $("#name-field").val();
            var username = $("#username-field").val();
            var email = $("#email-field").val();
            var about = $("#about-field").val();

            if(name != ogName) {
                $.post("updateStringFields.php", {
                    field: "name",
                    change: name
                })
                makeUnclickable();                
                // making the ogname as the changed name, after saving
                ogName = name;
            }
            if(username != ogUsername) {
                $.post("updateStringFields.php", {
                    field: "username",
                    change: username
                })
                makeUnclickable();
                ogUsername = username;
            }
            if(email != ogEmail) {
                $.post("updateStringFields.php", {
                    field: "email",
                    change: email
                })
                makeUnclickable();
                ogEmail = email;
            }
            if(about != ogAbout) {
                $.post("updateStringFields.php", {
                    field: "about",
                    change: about
                })
                makeUnclickable();
                ogAbout = about;
            }
        }        
    })

    // For the change pic button
    $("#change-pic-btn").click(function() {
        $("#change-pic-popup").fadeIn();

        popupActive();
    })

    // Close change pic popup
    $("#close-change-pic-popup").click(function() {
        popupInActive();
    })

    // Inside the change-pic popup
    // Toggling between bg and fg containers (next and prev buttons)
    $("#bg-fg-nxt").click(function() {
        $("#background-container").fadeOut();
        setTimeout(() => {
            $("#foreground-container").fadeIn();
        }, 500);
        $(this).removeClass('bg-fg-ui-clickable');
        setTimeout(() => {
            $("#bg-fg-prev").addClass('bg-fg-ui-clickable');
        }, 500);
    })
    $("#bg-fg-prev").click(function() {
        $("#foreground-container").fadeOut();
        setTimeout(() => {
            $("#background-container").fadeIn();
        }, 500);  
        $(this).removeClass('bg-fg-ui-clickable');
        setTimeout(() => {
            $("#bg-fg-nxt").addClass('bg-fg-ui-clickable');
        }, 500);        
    })

    // On clicking on a color
    var currentBgColor = $(".current-pic").css("background-color");
    $(".preset-bg-color").click(function() {
        currentBgColor = $(this).css("background-color");
        // Making sure the uploaded pic doesnt show up
        if(currentFgPicChanged) {
            $(".current-pic").css("background-image", currentFgPic);
        } else {
            $(".current-pic").css("background-image", "none");
        }
        $(".current-pic").css("background-color", currentBgColor);

        $("#save-change-pic-btn").fadeIn();
        $("#edit-pic-upload").fadeOut();
    })

    // On choosing color through color picker
    $("#edit-pic-color-picker").change(function() {
        currentBgColor = $(this).val();
         // Making sure the uploaded pic doesnt show up
         if(currentFgPicChanged) {
            $(".current-pic").css("background-image", currentFgPic);
        } else {
            $(".current-pic").css("background-image", "none");
        }
        $(".current-pic").css("background-color", currentBgColor);

        $("#save-change-pic-btn").fadeIn();
        $("#edit-pic-upload").fadeOut();
    })

    // On clicking on a foreground
    var currentFgPic = $(".current-pic").css("background-image");
    var currentFgPicChanged = false;
    $(".preset-fg-pic").click(function() {
        // If changed, let the code know
        currentFgPicChanged = true;
        currentFgPic = $(this).css("background-image");
        $(".current-pic").css("background-image", currentFgPic);

        $("#save-change-pic-btn").fadeIn();
        $("#edit-pic-upload").fadeOut();
    })

    // On clicking save
    $("#save-change-pic-btn").click(function() {
        // Happens only if fg is changed
        if(currentFgPicChanged) {
            $.post("saveProfilePic.php", {
                bg: currentBgColor,
                fg: currentFgPic
            })
            popupInActive();
            setTimeout(() => {
                window.location.reload();    
            }, 500);
        }
    })

    // User selecting file
    $("#photoUploadForm").submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: "uploadProfilePic.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
            }
        });

        popupInActive();
        setTimeout(() => {
            window.location.reload();    
        }, 500);
    });

    // When user selects file, change the background of current pic container
    $("#edit-pic-input").change(function() {
        var selectedFile = this.files[0];

        if (selectedFile) {
            var imageUrl = URL.createObjectURL(selectedFile);
            $(".current-pic").css("background-image", "url(" + imageUrl + ")");
        }

        // Bring in the upload button, and fadeout the save button
        $("#edit-pic-upload").fadeIn();
        $("#save-change-pic-btn").fadeOut();
    })
});

function checkIfChanged() {
    var name = $("#name-field").val();
    var email = $("#email-field").val();
    var username = $("#username-field").val();
    var about = $("#about-field").val();

    var nameChanged = false, emailChanged = false, usernameChanged = false, aboutChanged = false;

    if(name != ogName) {
        nameChanged = true;
    }
    if(email != ogEmail) {
        emailChanged = true;
    }
    if(username != ogUsername) {
        usernameChanged = true;
    }
    if(about != ogAbout) {
        aboutChanged = true;
    }

    // Managing classes, even if one of the any is changed
    if(nameChanged || emailChanged || usernameChanged || aboutChanged) {
        makeClickable();
    } else {
        makeUnclickable();
    }
}

function makeClickable() {
    $("#edit-profile-save").removeClass('edit-unclickable');
    $("#edit-profile-save").addClass('edit-clickable');

    $("#edit-profile-reset").removeClass('edit-unclickable');
    $("#edit-profile-reset").addClass('edit-clickable');
}

function makeUnclickable() {
    $("#edit-profile-save").removeClass('edit-clickable');
    $("#edit-profile-save").addClass('edit-unclickable');

    $("#edit-profile-reset").addClass('edit-unclickable');
    $("#edit-profile-reset").removeClass('edit-clickable');
}