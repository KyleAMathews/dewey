<?php // $Id: page.tpl.php,v 1.15.4.7 2008/12/23 03:40:02 designerbrent Exp $ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>">
<head>
	<title><?php print $head_title ?></title>
	<meta http-equiv="content-language" content="<?php print $language->language ?>" />
	<?php print $meta; ?>
  <?php print $head; ?>
  <?php print $styles; ?>
  <!--[if lte IE 7]>
    <link rel="stylesheet" href="<?php print $path; ?>blueprint/blueprint/ie.css" type="text/css" media="screen, projection">
  	<link href="<?php print $path; ?>css/ie.css" rel="stylesheet"  type="text/css"  media="screen, projection" />
  <![endif]-->  
  <!--[if lte IE 6]>
  	<link href="<?php print $path; ?>css/ie6.css" rel="stylesheet"  type="text/css"  media="screen, projection" />
  <![endif]-->  
</head>

<body class="<?php print $body_classes; ?>">

<div class="container clearfix">
  <div id="global">
    
    <a title="<?php print $site_name; ?><?php if ($site_slogan != '') print ' &ndash; '. $site_slogan; ?>" href="<?php print url() ?>">
    	<img id="logo-image" src= "<?php print $path; ?>images/island-logo.png" />
    </a>
    
    <?php if($global_links = menu_navigation_links('menu-global-links')) : ?>
      <?php print theme('links', $global_links, array('id' => 'nav', 'class' => 'links span-8')) ?>
    <?php endif; ?>
    
    <?php print $header; ?>
    
    <?php if (isset($secondary_links)) : ?>
      <?php print theme('links', $secondary_links, array('id' => 'subnav', 'class' => 'links last span-5')) ?>
    <?php endif; ?>
    
    <?php if (isset($user->name)) : ?>
    	<div id="header-name">
    	<?php print $user->name; ?>
    	</div>
    <?php endif; ?>
        
  </div>

  <?php if ($left): ?>
    <div class="<?php print $left_classes; ?>"><?php print $left; ?></div>
  <?php endif ?>
  
  <div class="<?php print $center_classes; ?>">
    <?php
      if ($breadcrumb != '') {
        print $breadcrumb;
      }

      if ($tabs != '') {
        print '<div class="tabs">'. $tabs .'</div>';
      }

      if ($messages != '') {
        print '<div id="messages">'. $messages .'</div>';
      }
      
      print "<div id='front-header-search'>";
      print "<h2 class='title'>Learning Through Conversations</h2>";
      //print drupal_get_form('search_form', '', '', '', '');
      print $search_box;
      print '<br class="clear" />';
      print "</div>";

      print $help; // Drupal already wraps this one in a class      
      
      print $content;
      //print $feed_icons;
      
    ?>

    <?php if ($footer_message | $footer): ?>
      <div id="footer" class="clear">
        <?php if ($footer): ?>
          <?php print $footer; ?>
        <?php endif; ?>
        <?php if ($footer_message): ?>
          <div id="footer-message"><?php print $footer_message; ?></div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <?php if ($right): ?>
    <div class="<?php print $right_classes; ?>"><?php print $right; ?></div>
  <?php endif ?>
  


</div>
  <?php print $scripts ?>
  <?php print $closure; ?>
</body>
</html>
