<?php
#  print '<pre>';
#  print '<!-- ';
#  print_r( $node);
#  print ' -->';
#  print '</pre>';

/**
 * Get user post count
 */
$topic_count = db_result(db_query("SELECT COUNT(*) FROM {node} n WHERE n.type = 'forum' AND n.status = 1 AND n.uid = %d", $uid));
$comment_count = db_result(db_query("SELECT COUNT(*) FROM {comments} c WHERE c.status = 0 AND c.uid = %d", $uid));
$total_posts = $topic_count + $comment_count;

/**
 * Get user info
 */
$account = user_load(array('uid' => $node->uid));
profile_load_profile($account);
$location = location_country_name($account->profile_country);
if (!$location) { $location = 'N/A'; }

/**
 * Get user picture
 */ 
if ($node->picture) {
  $user_image_filepath = $node->picture;
  $userpic = '<a href="'.url('user/'.$node->uid).'">'.theme('imagecache', 'forum_user_images', $user_image_filepath).'</a>';
} else {
  $userpic = '<a href="'.url('user/'.$node->uid).'"><img src="'. url(variable_get('user_picture_default', '')).'" alt="'.t('User has no personal picture').'"></a>';
}

?>
<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

<div class="preview_box">
	<div class="preview_img">
		<a class="img_text" href="<?php print url('user/'.$node->uid); ?>"><?php print $node->name; ?></a>
		<div class="user-picture pre_img"><?php print $userpic ?></div>
		<!--<h4>Posts</h4>
		<h3>&raquo; <?php print $total_posts; ?></h3>
		<h4>Location</h4>
		<h3>&raquo; <?php print $location; ?></h3>-->
	</div>
	
	<div class="preview_text">
		<h2 class="title"><?php print $title ?></h2>
		<h4><?php print format_date($node->created); ?>  |  <?php print link_report_abuse('node',$node->nid); ?></h4>
    <?php print $content ?>
    <div class="ton86_infobox">
      <p>Username : <span><?php print $node->name; ?></span></p>
      <p>Forum Posts : <span><?php print $total_posts; ?></span></p>
      <p>Location : <span><?php print $location; ?></span></p>
      <div class="clr"></div>
    </div>
	</div>
	<div class="clr"></div>
</div>
<!--
 
<?php if ($page == 0): ?>
  <h2 class="title"><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
<?php endif; ?>

  <?php if ($submitted): ?>
    <h4 class="submitted"><?php print t('Posted: ') . format_date($node->created); ?></h4>
  <?php endif; ?>

  <div class="content clear-block">
    <?php print $content ?>
    <?php
    // Show only on teaser view and if link exist
    if ($teaser && $node->readmore) {
      print '<p><a href="'. $node_url .'" title="'.t('Read full article').'" class="readmore">'.t('Read more').'</a></p>';
    }
    ?>
  </div>
-->
<!--
   <div class="clear-block">
    <div class="meta">
    <?php if ($taxonomy): ?>
      <div class="terms"><?php print $terms ?></div>
    <?php endif;?>
    </div>

    <?php if ($links): ?>
      <div class="links"><?php print $links; ?></div>
    <?php endif; ?>
  </div>
-->
</div>
<?php
/*
// footer links
$footer_links = '
<div id="forum-footer-links" class="ptext2" style="position:absolute;bottom:5px">
  <a href="'.url('forum').'">'.t('Forum index').'</a>';

if (arg(1) != 'reply'){
  $footer_links .= ' | <a href="'.url('comment/reply/'.$node->nid.'').'#comment-form">'.t('Add comment').'</a>';
}
print $footer_links .'</div>';
*/
?>
