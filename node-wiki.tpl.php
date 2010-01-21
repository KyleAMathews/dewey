<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> clear-block">

<h2 class="node-title"><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>

  <div class="content clear-block">
    <?php print $content ?>
  </div>
  
  <?php print $groups_string; ?>
  <?php if (!empty($terms)): ?>
    <div class="terms"><?php print "Tags: ". $terms ?></div>
  <?php endif;?>
  
  <?php if ($last_edit):?>
    <br />
	<span class="last-edit"><?php print $last_edit; ?></span>  
  <?php endif; ?>
<?php
  if ($links) {
    print '<div class="node-links">'. $links .'</div>';
  }
?>

</div>
