// Global killswitch: only run if we are in a supported browser.
if (Drupal.jsEnabled) {
  Drupal.behaviors.eduglu_front_page = function() {
    $(document).ready( function() {
      $(".activity-teaser-expand").click(function() {
          $(this).parent().hide();
          $(this).parent().next().next().addClass("prefix-2 alpha");
          $(this).parent().next().show();

          return false;
      });
    });
  }
  $(document).ready(function() {
    $("#global_nav a:contains('my groups')").click( function() {
      var position = $("#global_nav a:contains('my groups')").offset();
      var boxWidth = $("#my-groups").width();

      position.top = 36;
      position.left = position.left - (boxWidth - 56);

      // Set new position for my-groups div.
      $("#my-groups").css(position);
      $("#my-groups").toggle();

      // So clicking anywhere will close the my-groups div.
      if ($("#my-groups:visible").length > 0) {
        $(document).click(function() {
          $("#my-groups").toggle();
          $(document).unbind('click');
        }); 
      }
      else {
        $(document).unbind('click');
      }

      // So link doesn't go anywhere.
      return false;
    });

    // If the window is resized and the my-groups div is visible, reset
    // the div's position.
    $(window).resize(function() {
      if ($("#my-groups:visible").length > 0) {
        var position = $("#global_nav a:contains('my groups')").offset();

        position.top = 23;
        position.left = position.left - 40;

        // Set new position for my-groups div.
        $("#my-groups").css(position);
      }
    });
  });
}

