<?php
// $Id: box.tpl.php,v 1.3 2007/12/16 21:01:45 goba Exp $

/**
 * @file box.tpl.php
 *
 * Theme implementation to display a box.
 *
 * Available variables:
 * - $title: Box title.
 * - $content: Box content.
 *
 * @see template_preprocess()
 */
?>
<div class="box">

<?php if ($title): ?>
  <h4><?php print $title ?></h4>
  <div class="picture">
  <?php print $user_picture; ?>
  </div>
  <?php print $user_name; ?>
<?php endif; ?>

  <div class="content"><?php print $content ?></div>
</div>
