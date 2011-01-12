<div id="node-<?php print $node->nid; ?>" class="container node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> <?php if ($teaser) { print ' teaser'; } ?> <?php if (!$page) { print "not-page"; } ?> clear-block">

  <div class="user-picture grid-1">
    <?php print $picture ?>
  </div>
  <div class="grid-10">
    <?php if (!$page): ?>
      <h3 class="node-title grid-9 alpha"><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h3>
      <div class="new-comment-count grid-2 omega"><?php print $new_comment_count; ?></div>
    <?php endif; ?>
    <?php if ($submitted && !$page): ?>
      <div class="node-submitted clear-block"><?php print $submitted ?></div>
    <?php endif; ?>
    <div class="content clear-block">
      <?php if (!$page): ?>
        <div class="trimmed-content">
          <?php print dewey_trim_text($content, 200) ?>
          <a href="#" class="expand-post">(more)</a>
        </div>
      <?php endif; ?>
      <div class="full-content">
        <?php print $content; ?>
      </div>
    </div>
  </div>
  
</div>
