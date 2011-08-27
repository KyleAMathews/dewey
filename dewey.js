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
  // Clean up tipsy tooltips after clicking on a flag.
  Drupal.behaviors.eduglu_flag_tipsy = function(context) {
    $("a.flag").click(function() {
      $(".tipsy").fadeOut();
    });
  }
  // prevent double-click on submit
  $('input[type=submit]').click(function(){
    if($.data(this, 'clicked')){
      return false;
    }
    else{
      $.data(this, 'clicked', true);
      return true;
    }
  });
}
