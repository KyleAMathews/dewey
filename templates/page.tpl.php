<?php
// $Id: page.tpl.php,v 1.1.2.1 2009/02/24 15:34:45 dvessel Exp $
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">

<head>
  <title><?php print $head_title; ?></title>
  <meta http-equiv="content-language" content="<?php print $language->language ?>" />
  <?php print $meta; ?>
  <?php print $head; ?>
  <?php print $styles; ?>
</head>

<body class="<?php print $body_classes; ?>">
  <?php print $my_groups; ?>
  <div id="page" class="container-16 clear-block">

    <div id="site-header" class="clear-block">
      <div id="global_nav" class="clear-block">
      <?php if ($secondary_menu_links): ?>
        <?php print $secondary_menu_links; ?>
      <?php endif; ?>
      <?php if ($user_account): ?>
       <?php print $user_account; ?>
      <?php endif; ?>
      </div>
      <div id="branding" class="clear-block">
      <a class="logo grid-10 alpha" title="<?php print $site_name; ?><?php if ($site_slogan != '') print ' &ndash; '. $site_slogan; ?>" href="<?php print base_path() ?>">
    	<img id="logo-image" src= "<?php if (isset($logo)) {print $logo;} ?>" />
      </a>
      <?php if ($search_box): ?>
        <div id="search-box"><?php print $search_box; ?></div>
      <?php endif; ?>
      </div>

    </div>

    <div id="space-title" class="clear-block grid-16 alpha">
      <?php if ($space_title): ?>
        <h2><?php print $space_title; ?></h2>
      <?php endif; ?>
    </div>
    
    <div id="feature-tabs" class="grid-16 alpha">
      <?php if ($primary_links): ?>
        <?php print theme('links', $primary_links, array('id' => 'features-menu', 'class' => 'links primary-links')) ?>
      <?php endif; ?>
      <?php if ($space_settings): ?>
        <?php print $space_settings; ?>
      <?php endif; ?>
    </div>

    <div id="content-container">
      <div id="context-links">
        <?php if ($space_user_links): ?>
          <div class="button">
            <?php print $space_user_links; ?>
          </div>
        <?php endif; ?>
        
        <?php if ($context_links): ?>
          <div class="button">
            <?php print $context_links; ?>
          </div>
        <?php endif; ?>
      </div>
  
      <div id="main" class="column <?php print ns('grid-16', $left, 4, $right, 3) . ' ' . ns('push-4', !$left, 4); echo ($right) ? " alpha" : ""; ?>">
        <?php if ($tabs): ?>
          <div class="tabs">
            <?php print $tabs; ?>
          </div>
        <?php endif; ?>
        <?php if ($breadcrump): ?>
          <?php print $breadcrumb;?>
        <?php endif; ?>
        <?php if ($title && $space): ?>
          <h1 class="title grid-13" id="page-title">
            <?php print $title; ?>
          </h1>
        <?php endif; ?>
        <?php print $messages; ?>
        <?php print $help; ?>
            
        <div id="main-content" class="region <?php print ns('grid-16', $left, 4, $right, 3); ?>">
          <?php print $content; ?>
        </div>
  
        <span class="grid-1 prefix-1">  
	  <?php print $feed_icons; ?>
        </span>
      </div>
      
  
      <?php if ($left): ?>
        <div id="sidebar-left" class="column sidebar region alpha grid-4 <?php print ns('pull-12', $right, 3); ?>">
          <?php print $left; ?>
        </div>
      <?php endif; ?>

      <div id="sidebar-right" class="column sidebar region omega grid-3">
        <?php if ($right): ?>
          <?php print $right; ?>
        <?php endif; ?>
      </div>
      
      <div id="footer" class="prefix-1 suffix-1">
        <?php if ($footer): ?>
          <div id="footer-region" class="region grid-16 clear-block">
            <?php print $footer; ?>
          </div>
        <?php endif; ?>
    
        <?php if ($footer_message): ?>
          <div id="footer-message" class="grid-14">
            <?php print $footer_message; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
    
  </div>
  <?php print $scripts; ?>
  <?php print $closure; ?>
</body>
</html>
