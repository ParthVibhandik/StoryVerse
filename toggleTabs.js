// This file is used to toggle between created and contributions in the accountDetails.php page

$(document).ready(function() {
    // Set the default tab to 'Created'
    showTab('created');
  
    // Add click event listeners to the tabs
    $('.tab').click(function() {
      // Remove the 'selected' class from all tabs
      $('.tab').removeClass('selected');
  
      // Add the selected class to the clicked tab
      $(this).addClass('selected');
  
      // Hide all tab contents
      $('.tab-content').hide();
  
      // Show the corresponding tab content based on the data-target attribute
      var target = $(this).data('target');
      showTab(target);
    });
  });
  
function showTab(tabId) {
  $('#' + tabId).show();
}