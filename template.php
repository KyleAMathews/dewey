<?php
/**
 * Preprocessor for page.tpl.php template file.
 */
function dewey_preprocess_page(&$vars, $hook) {
  global $user;
  $space = spaces_get_space();
  //print_r($space);
  $context = context_get();
  $vars['space'] = $space;
  $vars['context'] = $context;
  
  // If user is not a member of the group, add an "Join group" button.
  if (($space->controllers->variable->space_type == "og") && !in_array($space->id, array_keys($user->og_groups))) {
    $add_group = array('add-group' => array('title' => t('Join group'), 'href' => 'og/subscribe/' . $space->id));
    $vars['context_links'] = theme('links', $add_group);
  }

  // If we're on on a user page or a group page, add an add group link.
  if (empty($vars['context_links']) && !in_array($space->controllers->variable->space_type, array('og', 'user')) && $user->uid != 0) {
    $add_group = array('add-group' => array('title' => t('Add Group'), 'href' => 'node/add/group'));
    $vars['context_links'] = theme('links', $add_group);
  }

  // Path to theme
  $vars['path'] = base_path() . path_to_theme() .'/';
  
  // Create login or account page.
  list($user_picture, $user_picture_preset) = dewey_comment_user_picture($user->picture, $user->uid);
  $user_picture = "<div class='picture grid-1'>" . $user_picture . "</div>";
  $vars['user_picture'] = $user_picture;
  if ($user->uid) {
    $vars['user_account'] = $user_picture . " " . l($user->name, 'user') . "   " 
      . l('logout', 'logout');
  }
  else {
    $vars['user_account'] = l('Login', 'user', array('attributes' => array('class' => 'user-account')));
  }
  $vars['user_account'] = "<span class='user-links'>"
    . $vars['user_account'] . "</span>";
  
  // Set title
  if ($space) {
    if (!empty($space->group->title)) {
      $vars['space_title'] = l(strtoupper($space->group->title), '<front>');
    }
  }
  else {
    $vars['space_title'] = $vars['title'];
  }
  
  // Create the menu item for the group settings tab for group admins.
  if ($space->type == 'og') {
    $result = db_result(db_query("SELECT uid
                        FROM {og_uid}
                        WHERE is_admin = 1
                        AND nid = %d
                        AND uid = %d", $space->id, $user->uid));

    if ($result || $user->uid == 1) {
      $vars['space_settings'] = '<ul class="links admin-links"><li class="space-settings first">' . l("Group Settings", "node/" . $space->id . "/edit") . '</li></ul>';
    }
  }
  
  // Add custom breadcrumb.
  $active_menu = "";
  if (!empty($context['context'])) {
    $contexts = array_keys($context['context']);
    foreach ($contexts as $item ) {
      if (preg_match('/spaces-feature.*/', $item, $matches)) {
        $active_menu = $context['context'][$item]->reactions['menu'];
      }
    }
  }
  $breadcrumb .= "<div id='breadcrumb'";
  $breadcrumb .= l("Home", base_path(), array('external' => true)) . " > ";
  if (isset($space->id)) {
    $breadcrumb .= l($space->group->title, "");
    if (!empty($active_menu)) {
      $breadcrumb .= " > "  . l(capitalizeWords($active_menu), $active_menu);
    }
  }
  else {
    $breadcrumb = trim($breadcrumb, " >");
  }
  $breadcrumb .= "</div>";
  $vars['breadcrumb'] = $breadcrumb;
  
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
}

/**
 * Preprocessor for node.tpl.php template file.
 */
function dewey_preprocess_node(&$vars) {
  $node = $vars['node'];
  // Create conversation bubble.
  if ($vars['teaser']) {
    $vars['conversation_bubble'] = dewey_make_conversation_bubble($vars); 
  }

  // Stick with node.tpl.php, not node-og-group-post
  $key = array_search('node-og-group-post', $vars['template_files']);
  if ($key !== FALSE) {
    $vars['template_files'][$key] = NULL;
  }
  
  // Remove the comment count from node teasers.
  $new_links = array();
  if (!empty($vars['node']->links)) {
    foreach ($vars['node']->links as $key => $values) {
      if ($key != "comment_comments") {
        $new_links[$key] = $values;
      }
    }
  }

  // Overwrite the $links variable with our new links.
  $vars['links'] = theme('links', $new_links, array('class' => 'links inline'));
  
  $vars['last_changed'] = "<em>Last changed " . format_date($vars['changed']) . "</em>";
}

/*
 * Preprocessor for comment.tpl.php template file
 */
function dewey_preprocess_comment(&$vars) {
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
  
  // Override default picture size.
  list($picture, $preset) = dewey_comment_user_picture($vars['comment']->picture, $vars['comment']->uid);
  $vars['picture'] = $picture;
  $vars['preset'] = $preset;
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

function dewey_make_conversation_bubble($vars) {
  $output .= '<div class="grid-3 conversation-bubble-container">';
  if ($vars['comment_count'] > 0) {
    
    // Fetch text of first comment and format.
    $first_comment = db_result(db_query("SELECT comment
                                        FROM {comments}
                                        WHERE nid = %d
                                        ORDER BY timestamp
                                        LIMIT 1", $vars['node']->nid));

    // Remove any quotes if the comment came via email.
    $first_comment = _og_mailinglist_remove_quotes($first_comment);

    // Remove line breaks / html / and trim.
    $first_comment = dewey_trim_text($first_comment);
    
    // Fetch pictures of commenters
    $results = db_query("SELECT DISTINCT c.uid, c.cid, u.picture
                        FROM {comments} c
                        JOIN {users} u
                        WHERE c.uid = u.uid
                        AND nid = %d
                        ORDER BY c.timestamp
                        LIMIT 20", $vars['node']->nid);
    $commenters = array();
    while ($data = db_fetch_array($results)) {
      $commenters[$data['uid']] = array('cid' => $data['cid'],
                                        'picture' => $data['picture']);
    }
    
    $output .= '<p class="conversation-bubble">';
    $output .= "<em>" . l($first_comment, "node/" . $vars['node']->nid) . "</em>";
    $output .= "<img src='" . base_path() . path_to_theme() . "/images/conversation-angle.png' />";
    $output .= '<br /><br />';
    // Add pictures
    $output .= dewey_add_conversation_bubble_pictures($commenters, $vars['node']->nid);
    $output .= '</p>';
    if ($vars['comment_count'] > 1) {
      $output .= '<p class="conversation-teaser">' . l('See all ' . $vars['comment_count']
          . ' comments >>', "node/" . $vars['node']->nid) . '</p>';
    }
    else {
      $output .= '<p class="conversation-teaser">' . l('See the ' . $vars['comment_count']
          . ' comment >>', "node/" . $vars['node']->nid) . '</p>';
      
    }
  }
  
  $output .= '</div>';
  
  return $output;
}

function dewey_add_conversation_bubble_pictures($commenters, $nid) {
  foreach ($commenters as $uid => $data) {
    if (isset($data['picture']) && module_exists('imagecache')) {
      $attr = array('class' => 'commenter-picture');
      $preset = '25x25_crop';
      
      $attr['class'] .= ' picture-'. $preset;
      if (file_exists($data['picture'])) {
        $image = theme('imagecache', $preset, $data['picture']);
      }
      else {
        $default_image = variable_get('user_picture_default', '');
        $image = theme("imagecache", $preset, $default_image);
      }
      $path = 'node/'. $nid;
      $fragment = "comment-" . $data['cid'];
      $output .= l($image, $path, array('attributes' => $attr,
                                        'fragment' => $fragment,
                                        'html' => true));
    }
  }
  
  return $output;
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
  return t('<em>!username, <span class="date">!datetime</span></em>',
    array(
      '!username' => theme('username', $node),
      '!datetime' => format_date($node->created, 'custom', "j M Y - g:i A"),
    ));
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
              array('class' => 'username', 'title' => t('View user profile.')),
              'purl' => array('disabled' => TRUE)
              ));
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
      $output = l($object->name, $object->homepage, array('attributes' => array('rel' => 'nofollow'),
                                          'purl' => array('disabled' => TRUE)));
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

function dewey_comment_user_picture($picture, $uid) {
  if (isset($picture) && module_exists('imagecache')) {
    $attr = array('class' => 'user-picture');
    $preset = '30x30_crop';
    
    $attr['class'] .= ' picture-'. $preset;
    if (file_exists($picture)) {
      $image = imagecache_create_url($preset, $picture);
      $attr['style'] = 'background-image: url('. $image .')';
    }
    else {
      $default_image = variable_get('user_picture_default', '');
      $image = imagecache_create_url($preset, $default_image);
      $attr['style'] = 'background-image: url('. $image .')';
    }
    $path = 'user/'. $uid;
    $picture = l("k", $path, array('attributes' => $attr,
                             'purl' => array('disabled' => true)));
    return array($picture, $preset);
  }
}

/****************
 * Utility functions
 ****************/
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

/**
 * Capitalize all words
 * @param string Data to capitalize
 * @param string Word delimiters
 * @return string Capitalized words
 * Function taken from http://www.php.net/manual/en/function.ucwords.php#95325
 */
function capitalizeWords($words, $charList = null) {
    // Use ucwords if no delimiters are given
    if (!isset($charList)) {
        return ucwords($words);
    }

    // Go through all characters
    $capitalizeNext = true;

    for ($i = 0, $max = strlen($words); $i < $max; $i++) {
        if (strpos($charList, $words[$i]) !== false) {
            $capitalizeNext = true;
        } else if ($capitalizeNext) {
            $capitalizeNext = false;
            $words[$i] = strtoupper($words[$i]);
        }
    }

    return $words;
}
