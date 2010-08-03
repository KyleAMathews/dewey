<div id="node-<?php print $node->nid; ?>" class="container-10 node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> clear-block">

  <div class="meta grid-10 clear-block">
    <div class="user-picture grid-2 alpha">
      <?php print $picture ?>
    </div>
    <div class="grid-8 omega meta-info">
      <?php if ($teaser): ?>
        <h2 class="node-title"><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
      <?php endif; ?>
      <?php if ($submitted): ?>
        <div class="node-submitted clear-block"><?php print $submitted ?></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="content alpha grid-12 clear-block">
    <?php print $content ?>
  </div>
  
  <?php print $conversation_bubble; ?>
  <?php print $groups_string; ?>
  <?php
    if ($links || $terms) {
      print '<div class="node-links alpha grid-10">';
      if (!empty($links)) { print $links; }
      if (!empty($terms)) { print $terms; }
      print '</div>';
    }
  ?>
  
</div>
