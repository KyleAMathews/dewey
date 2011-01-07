<div id="node-<?php print $node->nid; ?>" class="container node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> <?php if ($teaser) { print ' teaser'; } ?> clear-block">

  <div class="user-picture grid-1">
    <?php print $picture ?>
  </div>
  <div class="grid-11">
    <?php if (!$page): ?>
      <h3 class="node-title"><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h3>
    <?php endif; ?>
    <?php if ($submitted): ?>
      <div class="node-submitted clear-block"><?php print $submitted ?></div>
    <?php endif; ?>

    <div class="content clear-block">
      <?php if ($teaser): ?>
        <div class="trimmed-content">
          <?php print dewey_trim_text($content, 200) ?>
          <a href="#" class="expand-post">(more)</a>
        </div>
      <?php endif; ?>
      <div class="full-content">
      <?php if ($teaser): ?>
        <?php print $full_content; ?>
      <?php else: ?>
        <?php print $content; ?>
      <?php endif; ?>
      </div>
    </div>
  </div>
  
</div>
