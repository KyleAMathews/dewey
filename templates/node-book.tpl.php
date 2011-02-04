<div id="node-<?php print $node->nid; ?>" class="container node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> clear-block">

  <div class="meta grid-10 clear-block">
    <div class="grid-8 omega meta-info">
      <?php if ($last_changed): ?>
        <?php print $last_changed; ?>
      <?php endif; ?>
      <?php if ($teaser): ?>
        <h3 class="node-title grid-8 alpha"><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h3>
        <div class="new-comment-count grid-2 omega"><?php print $new_comment_count; ?></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="content clear-block">
    <?php print $content ?>
    <?php if (!empty($post_object)) print $post_object ?>

  </div>
  
  <?php
    if ($links || $terms) {
      print '<div class="node-links alpha grid-10">';
      if (!empty($links)) { print $links; }
      if (!empty($terms)) { print $terms; }
      print '</div>';
    }
  ?>
  
</div>
