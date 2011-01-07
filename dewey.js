// Global killswitch: only run if we are in a supported browser.
if (Drupal.jsEnabled) {
  Drupal.behaviors.eduglu_front_page = function() {
    $(".activity-teaser-expand").click(function() {
        $(this).parent().hide();
        $(this).parent().next().next().addClass("prefix-2 alpha");
        $(this).parent().next().show();

        return false;
    });
  }
  Drupal.behaviors.eduglu_discussions_toggle = function() {
    $(".expand-post").click(function() {
      $(this).parent().hide();
      $(this).parent().next().show();
      return false;
    });
  }
}
