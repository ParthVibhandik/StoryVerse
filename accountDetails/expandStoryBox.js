// This file is used to expand the story-containers in the accountDetails.php page

$(document).ready(function () {
    $(this).find(".story-start").hide();
    $(".link-story").hover(
      function () {
        // Mouse over
        $(this).addClass("expanded");
        $(this).find(".story-title").show();
        $(this).find(".story-start").show();
      },
      function () {
        // Mouse out
        $(this).removeClass("expanded");
        $(this).find(".story-title").show();
        $(this).find(".story-start").hide();
      }
    );
});