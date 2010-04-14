<?php
// $Id: views-view-fields.tpl.php,v 1.6 2008/09/24 22:48:21 merlinofchaos Exp $
/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->separator: an optional separator that may appear before a field.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
?>
<?php
$group_name = db_result(db_query("SELECT p.value
                                 FROM {purl} p
                                 JOIN {og_ancestry} o
                                 WHERE o.group_nid = p.id
                                 AND o.nid = '%s'", $fields['nid']->raw));
$group_id = db_result(db_query("SELECT group_nid
                               FROM {og_ancestry}
                               WHERE nid = '%s'", $fields['nid']->raw));
if (empty($fields['comment_count']->content)) {
  $comment_count = 0;
}
else {
  $comment_count = $fields['comment_count']->content;
}
?>
<div class="grid-16 activity-item">
  <div class='grid-2 activity-picture alpha'>
    <?php print $fields['picture']->content; ?>
  </div>

  <div class='grid-14 activity-title omega'>
    <h3>
      <?php
        print "<span class='activity-item-group-name'>[" . l($group_name, "node/" . $group_id) . "]</span> ";
        print "<span class='activity-node-title'>" . $fields['title']->content . "</span>";  
      ?>
    </h3>
  </div>
  
  <div class='grid-12 activity-teaser omega'>
    <?php print dewey_trim_text($fields['body']->content, 200); ?>
    <a href="#" class="activity-teaser-expand">Expand this post &raquo;</a>
  </div>
  
  <div class='grid-12 activity-body omega'>
    <?php print check_markup($fields['body']->raw); ?>
  </div>

  <div class="grid-8 activity-meta">
    <?php print $fields['created']->content; ?> by <?php print $fields['name']->content; ?>
    | <span class="activity-comment-count">
    <?php
      if (!empty($comment_count)) {
          print l($comment_count . " " . format_plural($comment_count, "comment", "comments"),
          "node/" . $fields['nid']->raw, array('fragment' => 'comments'));
      }
      else {
        print l("No comments", "node/" . $fields['nid']->raw);
      }
      ?>
      </span>
      <span class="activity-new-comments">
    <?php if ($fields['new_comments']->raw) {print "| " . $fields['new_comments']->content;} ?>
      </span>
  </div>
</div>
<br />
