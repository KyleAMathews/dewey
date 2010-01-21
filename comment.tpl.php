<div class="clear-block comment<?php print ($comment->new) ? ' comment-new' : ''; print(isset($comment->status) && $comment->status == COMMENT_NOT_PUBLISHED) ? ' comment-unpublished' : ''; if (isset($author_comment)) print ' author'; print ' '. $zebra; ?>">

  <div class="comment-content">
    <div class="meta">
      <div class="permalink clearfix">
        <?php if ($comment->new) : ?>
          <a id="new"></a>
          <span class="new"><?php print drupal_ucfirst($new) ?></span>
        <?php endif; ?>
        
      </div>
      <?php if ($submitted): ?>
        <div class="comment-submitted">
          <div class="picture">
            <?php print $picture ?>
          </div>
          <span class="username">
          <?php print theme('username', $comment); ?>
          </span> 
          <span class="date">
           <?php
             print $submitted;
           ?>
          </span>
        </div>
      <?php endif; ?>
    </div>
    
    <div class="content">
      
      <?php print $content ?>
      <?php if ($signature): ?>
        <div class="user-signature clear-block">
          <?php print $signature ?>
        </div>
        <?php print $edit_this_comment ?>
      <?php endif; ?>
    </div>

    <?php if ($links): ?>
      <div class="links"><?php print $links ?></div>
    <?php endif; ?>
  </div>
</div>
