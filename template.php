<?php

/**
 * Uncomment the following line during development to automatically
 * flush the theme cache when you load the page. That way it will
 * always look for new tpl files.
 */
// drupal_flush_all_caches();

/**
 * Menu item theme override. Adds a child element to expanded/expandable
 * elements so that a spite icon can be added.
 */
function phptemplate_menu_item($link, $has_children, $menu = '', $in_active_trail = FALSE, $extra_class = NULL) {
  if ($has_children) {
    $icon = "<span class='icon'></span>";
    $link = "{$icon} $link";
  }
  return theme_menu_item($link, $has_children, $menu, $in_active_trail, $extra_class);
}

/**
 * Intercept page template variables
 *
 * @param $vars
 *   A sequential array of variables passed to the theme function.
 */
function phptemplate_preprocess_page(&$vars) {
  global $user;
  //dpm($vars);
  $vars['path'] = base_path() . path_to_theme() .'/';
  $vars['user'] = $user;

  // Fixup the $head_title and $title vars to display better.
  $title = drupal_get_title();
  $headers = drupal_set_header();
  
  if($space = spaces_get_space()) {
    $vars['space_title'] = l($space->title, $space->purl . "/" . $space->settings['home']);
    //$title = $space->title;
  }
  
  // wrap taxonomy listing pages in quotes and prefix with topic
  if (arg(0) == 'taxonomy' && arg(1) == 'term' && is_numeric(arg(2))) {
    $title = t('Topic') .' &#8220;'. $title .'&#8221;';
  }
  // if this is a 403 and they aren't logged in, tell them they need to log in
  else if (strpos($headers, 'HTTP/1.1 403 Forbidden') && !$user->uid) {
    $title = t('Please login to continue');
  }
  $vars['title'] = $title;

  if (!drupal_is_front_page()) {
    $vars['head_title'] = $title .' | '. $vars['site_name'];
    if ($vars['site_slogan'] != '') {
      $vars['head_title'] .= ' &ndash; '. $vars['site_slogan'];
    }
  }

  // determine layout
  // 3 columns
  if ($vars['layout'] == 'both') {
    $vars['left_classes'] = 'col-left span-5';
    $vars['right_classes'] = 'col-right span-5 last';
    $vars['center_classes'] = 'col-center span-12';
    $vars['body_classes'] .= ' col-3 ';
  }
  // 2 columns
  else if ($vars['layout'] != 'none') {
    // left column & center
    if ($vars['layout'] == 'left') {
      $vars['left_classes'] = 'col-left span-5';
      $vars['center_classes'] = 'col-center span-18 last';
    }
    // right column & center
    else if ($vars['layout'] == 'right') {
      $vars['right_classes'] = 'col-right span-5 last';
      $vars['center_classes'] = 'col-center span-18';
    }
    $vars['body_classes'] .= ' col-2 ';
  }
  // 1 column
  else {

    $vars['center_classes'] = 'col-center span-24';
    $vars['body_classes'] .= ' col-1 ';
  }

  $vars['meta'] = '';
  // SEO optimization, add in the node's teaser, or if on the homepage, the mission statement
  // as a description of the page that appears in search engines
  if ($vars['is_front'] && $vars['mission'] != '') {
    $vars['meta'] .= '<meta name="description" content="'. dewey_trim_text($vars['mission']) .'" />'."\n";
  }
  else if (isset($vars['node']->teaser) && $vars['node']->teaser != '') {
    $vars['meta'] .= '<meta name="description" content="'. dewey_trim_text($vars['node']->teaser) .'" />'."\n";
  }
  else if (isset($vars['node']->body) && $vars['node']->body != '') {
    $vars['meta'] .= '<meta name="description" content="'. dewey_trim_text($vars['node']->body) .'" />'."\n";
  }
  // SEO optimization, if the node has tags, use these as keywords for the page
  if (isset($vars['node']->taxonomy)) {
    $keywords = array();
    foreach ($vars['node']->taxonomy as $term) {
      $keywords[] = $term->name;
    }
    $vars['meta'] .= '<meta name="keywords" content="'. implode(',', $keywords) .'" />'."\n";
  }

  // SEO optimization, avoid duplicate titles in search indexes for pager pages
  if (isset($_GET['page']) || isset($_GET['sort'])) {
    $vars['meta'] .= '<meta name="robots" content="noindex,follow" />'. "\n";
  }

  /* I like to embed the Google search in various places, uncomment to make use of this
  // setup search for custom placement
  $search = module_invoke('google_cse', 'block', 'view', '0');
  $vars['search'] = $search['content'];
  */
  
  /* to remove specific CSS files from modules use this trick
  // Remove stylesheets
  $css = $vars['css'];
  unset($css['all']['module']['sites/all/modules/contrib/plus1/plus1.css']);
  $vars['styles'] = drupal_get_css($css);   
  */
  
  // Stuff I add
  
  // Check if page is a group page
    $nid = arg(1);
    if (is_numeric($nid)) {
      $vars['is_group'] = db_result(db_query("SELECT nid FROM {node}
      WHERE nid = %d AND type = 'group'", $nid));
    }
    $gid = $vars['is_group'];
    
    // if this is a group, assemble the html for the group wiki tab
    if ($gid) {
      $tabs .= '<ul id="group-tabs">';
      $tabs .= '<li id="group-wiki-tab"></li>';
#      $tabs .= '<li id="group-stats">Groups Stats</li>';
//      $tabs .= '<li id="group-status">Groups Status Updates</li>';
      $tabs .= '</ul>';
      
      $vars['group_tabs'] = $tabs;
    }
}

/**
 * Intercept node template variables
 *
 * @param $vars
 *   A sequential array of variables passed to the theme function.
 */
function phptemplate_preprocess_node(&$vars) {
  //dpm($vars);
  $node = $vars['node']; // for easy reference
  
  $vars['submitted'] = dewey_node_submitted($node);
  
  // Theme $terms
  $terms = "";
  foreach($vars['taxonomy'] as $term) {
  	$terms .= l($term['title'], $term['href']) .", ";
  }
  $terms = trim($terms, " , ");
 
  // Add groups variable
  $groups_string = "";
    if ($vars['og_groups_both']) {
      $groups_string .= '<span class="og_groups">';
        
          $count=count($vars['og_groups_both']);
          if ($count > 1) {
            $groups_string .= t('Groups: ');
          }
          else {
            $groups_string .= t('Group: ');
          }
          $counter=0;
          foreach($vars['og_groups_both'] as $link => $name) {
            $counter++;
            if ($count>$counter){
              $sep=",";
            }
            else{
            $sep="";
            }
            $groups_string .= l($name,'node/'.$link) . $sep." ";}
      $groups_string .= '</span>';
      $groups_string .= '<br />';
  }
  
  $vars['groups_string'] = $groups_string;
    
  
  
  $vars['terms'] = $terms;
  // for easy variable adding for different node types
  switch ($node->type) {
    case 'page':
      break;
  }
  
      // Get html for last person to edit the page
    $uid = db_result(db_query("SELECT uid FROM node_revisions WHERE vid = %d", $vars['vid']));
    $userobj = user_load(array('uid' => $uid));
    $edited_by = theme('username', $userobj);
    
    if ($vars['type'] == 'group_wiki' || $vars['type'] == 'wiki'
    && $vars['created'] != $vars['changed']) {
    	$time_ago = format_interval(time() - $vars['changed'], 1);
    }
    
    /* Adding the variable. */
    $vars['last_edit'] = t('Last edited by !name about @time ago.', 
    array('!name' => $edited_by, '@time' => $time_ago));
    
    // Group wiki variable for group wiki pages
    if ($node->type == "group") {
      $group_wiki_nid = db_result(db_query("SELECT nid 
        FROM {content_type_group_wiki} WHERE field_associated_group_nid = %d", $node->nid));

      // Load node
      $groupwiki_node = node_load(array("nid" => $group_wiki_nid));

      // Theme content
      node_view($groupwiki_node);

      // Set variables
      $vars['group_wiki'] .= "<h2>". $node->title ." Wiki</h2>";
      $vars['group_wiki'] .= $groupwiki_node->content['body']['#value'];
      $vars['group_wiki_edit_link'] = "<h3>". l("Edit the ". $node->title  ." Wiki", 
      "node/". $group_wiki_nid ."/edit") ."</h3>";
    }
}

/**
 * Intercept comment template variables
 *
 * @param $vars
 *   A sequential array of variables passed to the theme function.
 */
function phptemplate_preprocess_comment(&$vars) {
  static $comment_count = 1; // keep track the # of comments rendered
  global $user;
  // if the author of the node comments as well, highlight that comment
  $node = node_load($vars['comment']->nid);
  if ($vars['comment']->uid == $node->uid) {
    $vars['author_comment'] = TRUE;
  }
  
  // Add "edit this comment" link to comments for authors of the comment.
  if ($user->uid == $vars['comment']->uid) {
    $vars['edit_this_comment'] = l('Edit this comment',
                'comment/edit/' . $vars['comment']->cid,
                array('attributes' => array('class' => 'edit-this-comment')));

  }
      
  // only show links for users that can administer links
  if (!user_access('administer comments')) {
    $vars['links'] = '';
  }
  // if subjects in comments are turned off, don't show the title then
  if (!variable_get('comment_subject_field', 1)) {
    $vars['title'] = '';
  }

  $vars['comment_count'] = $comment_count++;  
}

/**
 * Override or insert variables into the block templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
function phptemplate_preprocess_block(&$vars, $hook) {
  $block = $vars['block'];

  // Special classes for blocks.
  $classes = array('block');
  $classes[] = 'block-' . $block->module;
  $classes[] = 'region-' . $vars['block_zebra'];
  $classes[] = $vars['zebra'];
  $classes[] = 'region-count-' . $vars['block_id'];
  $classes[] = 'count-' . $vars['id'];

  $vars['edit_links_array'] = array();
  $vars['edit_links'] = '';
  
  if (user_access('administer blocks')) {
    include_once './' . drupal_get_path('theme', 'dewey') . '/template.block-editing.inc';
    phptemplate_preprocess_block_editing($vars, $hook);
    $classes[] = 'with-block-editing';
  }

  // Render block classes.
  $vars['classes'] = implode(' ', $classes);
}


/**
 * Intercept box template variables
 *
 * @param $vars
 *   A sequential array of variables passed to the theme function.
 */
function phptemplate_preprocess_box(&$vars) {
  // rename to more common text
  if (strpos($vars['title'], 'Post new comment') === 0) {
    $vars['title'] = 'Add your comment';
  }
}

/**
 * Override, remove "not verified", confusing
 *
 * Format a username.
 *
 * @param $object
 *   The user object to format, usually returned from user_load().
 * @return
 *   A string containing an HTML link to the user's page if the passed object
 *   suggests that this is a site user. Otherwise, only the username is returned.
 */
function dewey_username($object, $nohtml = false) {
  if ($object->uid && $object->name) {
    
  	// Pull the name from the user profile node
    $fullname = db_result(db_query("SELECT c.field_name_value FROM 
    {content_type_uprofile} c JOIN {node} n WHERE c.nid = n.nid AND uid = %d", 
    $object->uid));

    if (empty($fullname)) {
      $fullname = $object->name;
    }
    
    if ($nohtml) {
      return $fullname;
    }
    
    // Shorten the name when it is too long or it will break many tables.
    if (drupal_strlen($fullname) > 30) {
      $name = drupal_substr($fullname, 0, 25) .'...';
    }
    else {
      $name = $fullname;
    }

    if (user_access('access user profiles')) {
      $output = l($name, 'user/'. $object->uid, array('attributes' =>
              array('class' => 'username', 'title' => t('View user profile.'))));
    }
    else {
      $output = check_plain($name);
    }
  }
  else if ($object->name) {
    // Sometimes modules display content composed by people who are
    // not registered members of the site (e.g. mailing list or news
    // aggregator modules). This clause enables modules to display
    // the true author of the content.
    if (!empty($object->homepage)) {
      $output = l($object->name, $object->homepage, array('attributes' => array('rel' => 'nofollow')));
    }
    else {
      $output = check_plain($object->name);
    }
  }
  else {
    $output = variable_get('anonymous', t('Anonymous'));
  }

  return $output;
}

/**
 * Override, make sure Drupal doesn't return empty <P>
 *
 * Return a themed help message.
 *
 * @return a string containing the helptext for the current page.
 */
function dewey_help() {
  $help = menu_get_active_help();
  // Drupal sometimes returns empty <p></p> so strip tags to check if empty
  if (strlen(strip_tags($help)) > 1) {
    return '<div class="help">'. $help .'</div>';
  }
}

/**
 * Override, use a better default breadcrumb separator.
 *
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return a string containing the breadcrumb output.
 */
function dewey_breadcrumb($breadcrumb) {
  if (count($breadcrumb) > 1) {
    $breadcrumb[] = drupal_get_title();
    return '<div class="breadcrumb">'. implode(' &rsaquo; ', $breadcrumb) .'</div>';
  }
}

/**
 * Rewrite of theme_form_element() to suppress ":" if the title ends with a punctuation mark.
 */
function dewey_form_element($element, $value) {
  $args = func_get_args();
  return preg_replace('@([.!?]):\s*(</label>)@i', '$1$2', call_user_func_array('theme_form_element', $args));
}

/**
 * Set status messages to use Blueprint CSS classes.
 */
function dewey_status_messages($display = NULL) {
  $output = '';
  foreach (drupal_get_messages($display) as $type => $messages) {
    // dewey can either call this success or notice
    if ($type == 'status') {
      $type = 'success';
    }
    $output .= "<div class=\"messages $type\">\n";
    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>'. $message ."</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div>\n";
  }
  return $output;
}

/**
 * Override comment wrapper to show you must login to comment.
 */
function dewey_comment_wrapper($content, $node) {
  global $user;
  $output = '';

  if ($node = menu_get_object()) {
    if ($node->type != 'forum') {
      $count = $node->comment_count .' '. format_plural($node->comment_count, 'comment', 'comments');
      $count = ($count > 0) ? $count : 'No comments';
      $output .= '<h3 id="comment-number">'. $count .'</h3>';
    }
  }

  $output .= '<div id="comments">';
  $msg = '';
  if (!user_access('post comments')) {
    $dest = 'destination='. $_GET['q'] .'#comment-form';
    $msg = '<div id="messages"><div class="error-wrapper"><div class="messages error">'. t('Please <a href="!register">register</a> or <a href="!login">login</a> to post a comment.', array('!register' => url("user/register", array('query' => $dest)), '!login' => url('user', array('query' => $dest)))) .'</div></div></div>';
  }
  $output .= $content;
  $output .= $msg;

  return $output .'</div>';
}

/**
 * Override, use better icons, source: http://drupal.org/node/102743#comment-664157
 *
 * Format the icon for each individual topic.
 *
 * @ingroup themeable
 */
function dewey_forum_icon($new_posts, $num_posts = 0, $comment_mode = 0, $sticky = 0) {
  // because we are using a theme() instead of copying the forum-icon.tpl.php into the theme
  // we need to add in the logic that is in preprocess_forum_icon() since this isn't available
  if ($num_posts > variable_get('forum_hot_topic', 15)) {
    $icon = $new_posts ? 'hot-new' : 'hot';
  }
  else {
    $icon = $new_posts ? 'new' : 'default';
  }

  if ($comment_mode == COMMENT_NODE_READ_ONLY || $comment_mode == COMMENT_NODE_DISABLED) {
    $icon = 'closed';
  }

  if ($sticky == 1) {
    $icon = 'sticky';
  }

  $output = theme('image', path_to_theme() . "/images/icons/forum-$icon.png");

  if ($new_posts) {
    $output = "<a name=\"new\">$output</a>";
  }

  return $output;
}

/**
 * Override, remove previous/next links for forum topics
 *
 * Makes forums look better and is great for performance
 * More: http://www.sysarchitects.com/node/70
 */
function dewey_forum_topic_navigation($node) {
  return '';
}


/*
 * Override $submit in comments and nodes
 */
function dewey_comment_submitted($comment) {
  $url = check_url(url('node/'. $comment->nid));
  $url .= '#comment-'. $comment->cid;
  if (($comment->timestamp + 604800) > time()) {
    return t('!date ago',
      array(
        '!date' => '<a href="' . $url . '">' . format_interval(time() - $comment->timestamp) . '</a>',
      ));
  }
  else {
    return t('!date',
      array(
        '!username' => theme('username', $comment),
//        '!date' => '<a href="' . $url . '">' . format_date($comment->timestamp, 'medium') . '</a>',
        '!date' => '<a href="' . $url . '">' . format_date($comment->timestamp, 'custom', "j M Y - gA") . '</a>',
     ));
  }
}

function dewey_node_submitted($node) {
  return t('!username <span class="date">!datetime</span>',
    array(
      '!username' => theme('username', $node),
      '!datetime' => format_date($node->created, 'custom', "j M Y - g:i A"),
    ));
}

/**
 * Trim a post to a certain number of characters, removing all HTML.
 */
function dewey_trim_text($text, $length = 150) {
  // remove any HTML or line breaks so these don't appear in the text
  $text = trim(str_replace(array("\n", "\r"), ' ', strip_tags($text)));
  $text = trim(substr($text, 0, $length));
  $lastchar = substr($text, -1, 1);
  // check to see if the last character in the title is a non-alphanumeric character, except for ? or !
  // if it is strip it off so you don't get strange looking titles
  if (preg_match('/[^0-9A-Za-z\!\?]/', $lastchar)) {
    $text = substr($text, 0, -1);
  }
  // ? and ! are ok to end a title with since they make sense
  if ($lastchar != '!' && $lastchar != '?') {
    $text .= '...';
  }
  return $text;
}

// Theme the signup form (we just want a signup button for now).
function phptemplate_signup_user_form($node) {
  return array();
}

/**
 * Implementation of hook_preprocess_user_picture().
 * @TODO: Consider switching to imgaecache_profiles for this.
 */
function dewey_preprocess_user_picture(&$vars) {
  $account = $vars['account'];
  //dpm($vars['picture']);
  if (isset($account->picture) && module_exists('imagecache')) {
    $attr = array('class' => 'user-picture');
    $preset = '30x30_crop';
    
    $attr['class'] .= ' picture-'. $preset;
    if (file_exists($account->picture)) {
      $image = imagecache_create_url($preset, $account->picture);
      $attr['style'] = 'background-image: url('. $image .')';
    }
    else {
      $default_image = variable_get('user_picture_default', '');
      $image = imagecache_create_url($preset, $default_image);
      $attr['style'] = 'background-image: url('. $image .')';
    }
    $path = 'user/'. $account->uid;
    //dpm($attr);
    //drupal_set_message("a message");
    $vars['picture'] = l("k", $path, array('attributes' => $attr));
    $vars['preset'] = $preset;
  }
}

