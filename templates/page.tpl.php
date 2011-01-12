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
  <?php if (!empty($top_bar)) { print $top_bar; } ?>
  <div id="page" class="container-16 clear-block">

    <div id="space-title" class="clear-block">
      <?php if ($space_title): ?>
        <h2><?php print $space_title; ?></h2>
      <?php endif; ?>
    </div>
    
    <div id="feature-tabs">
      <?php if ($primary_links): ?>
        <?php print theme('links', $primary_links, array('id' => 'features-menu', 'class' => 'links primary-links')) ?>
      <?php endif; ?>
      <?php if ($space_settings): ?>
        <?php print $space_settings; ?>
      <?php endif; ?>
    </div>
    <?php $title_tabs = FALSE; ?>
    <div id="content-container">
      <?php if ($tabs || $space_user_links || $context_links): ?>
        <div id="context-links">
          <?php if ($tabs): ?>
            <div class="tabs alpha grid-12">
              <?php print $tabs; ?>
            </div>
          <?php endif; ?>
          <div id="space-context-links" class="omega grid-4 <?php if (!$tabs) { echo "prefix-13"; } ?> ">
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
        </div>
      <?php else: ?>
        <div id="context-links">
          <?php if ($title && $space && $logged_in): ?>
            <?php $title_tabs = TRUE ?>
            <h3 class="title grid-12" id="page-title">
              <?php print $title; ?>
            </h3>
          <?php endif; ?>        
        </div>
      
      <?php endif; ?>
  
      <div id="main" class="column <?php print ns('grid-16', $left, 4, $right, 4) . ' ' . ns('push-4', !$left, 4); ?>">
        <?php if ($breadcrump): ?>
          <?php print $breadcrumb;?>
        <?php endif; ?>
        <?php if ($title && $space && !$title_tabs): ?>
          <h2 class="title grid-12" id="page-title">
            <?php print $title; ?>
          </h2>
        <?php endif; ?>
        <?php print $messages; ?>
        <?php print $help; ?>
            
        <div id="main-content" class="region alpha <?php print ns('grid-16', $left, 4, $right, 4); ?>">
          <?php print $pre_content; ?>
          <?php print $content; ?>
          <?php print $post_content; ?>
        </div>
  
        <span class="grid-1 prefix-1">  
	  <?php print $feed_icons; ?>
        </span>
      </div>
      
  
      <?php if ($left): ?>
        <div id="sidebar-left" class="sidebar grid-4 <?php print ns('pull-12', $right, 3); ?>">
          <?php print $left; ?>
        </div>
      <?php endif; ?>

      <div id="sidebar-right" class="sidebar grid-4">
        <?php if ($right): ?>
          <?php print $right; ?>
        <?php endif; ?>
      </div>
      
      <div id="footer">
        <?php if ($footer): ?>
          <div id="footer-region" class="grid-16 clear-block">
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
