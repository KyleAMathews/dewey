<div class="clear-block comment<?php print ($comment->new) ? ' comment-new' : ''; print(isset($comment->status) && $comment->status == COMMENT_NOT_PUBLISHED) ? ' comment-unpublished' : ''; if (isset($author_comment)) print ' author'; print ' '. $zebra; ?>">

  <div class="comment-content">
    <div class="meta clear-block">
      <div class="permalink clearfix">
        
      </div>
      <?php if ($submitted): ?>
        <div class="comment-submitted">
          <div class="picture grid-1 alpha">
            <?php print $picture ?>
          </div>
          <div class="username">
          <?php print theme('username', $comment); ?>
          </div> 
          <div class="date grid-3">
           <?php
             print $submitted;
           ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
    
    <?php if ($comment->new) : ?>
      <div class="new">new</div>
    <?php endif; ?>
        
    <div class="content grid-11 clear-block">
      
      <?php print $content ?>
      <?php if ($signature): ?>
        <div class="user-signature clear-block">
          <?php print $signature ?>
        </div>
        <?php print $edit_this_comment ?>
      <?php endif; ?>
    </div>

    <?php if ($links): ?>
      <div class="links grid-11"><?php print $links ?></div>
    <?php endif; ?>
  </div>
</div>
