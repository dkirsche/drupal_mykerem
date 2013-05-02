<?php
/**
 * @file comment.tpl.php
 * Default theme implementation for comments.
 *
 * Available variables:
 * - $author: Comment author. Can be link or plain text.
 * - $content: Body of the post.
 * - $date: Date and time of posting.
 * - $links: Various operational links.
 * - $new: New comment marker.
 * - $picture: Authors picture.
 * - $signature: Authors signature.
 * - $status: Comment status. Possible values are:
 *   comment-unpublished, comment-published or comment-preview.
 * - $submitted: By line with date and time.
 * - $title: Linked title.
 *
 * These two variables are provided for context.
 * - $comment: Full comment object.
 * - $node: Node object the comments are attached to.
 *
 * @see template_preprocess_comment()
 * @see theme_comment()
 */

// Get user post count
$topic_count = db_result(db_query("SELECT COUNT(*) FROM {node} n WHERE n.type = 'forum' AND n.status = 1 AND n.uid = %d", $comment->uid));
$comment_count = db_result(db_query("SELECT COUNT(*) FROM {comments} c WHERE c.status = 0 AND c.uid = %d", $comment->uid));
$total_posts = $topic_count + $comment_count;

// Get user profile info
$account = user_load(array('uid' => $comment->uid));
profile_load_profile($account);
$location = location_country_name($account->profile_country);
if (!$location) { $location = 'N/A'; }


/**
 * Get user picture
 */

if ($comment->picture) {
  $user_image_filepath = $comment->picture;
  $userpic = '<a href="'.url($comment->uid).'">'.theme('imagecache', 'forum_user_images', $user_image_filepath).'</a>';
} else {
  $userpic = '<a href="'.url($comment->uid).'"><img src="'. url(variable_get('user_picture_default', '')).'" alt="'.t('User has no personal picture').'"></a>';
}
//print_r($comment);
//print $comment->cid;

?>
<div class="comment<?php print ($comment->new) ? ' comment-new' : ''; print ' '. $status ?> clear-block comment-<?php print $zebra; ?>">

<div class="preview_box">
	<div class="preview_img">
		<a class="img_text" href="<?php print url('user/'.$comment->uid); ?>"><?php print $comment->name; ?></a>
		<div class="user-picture pre_img"><?php print $userpic ?></div>
		<!--<h4>Posts</h4>
		<h3>&raquo; <?php print $total_posts; ?></h3>
		<h4>Location</h4>
		<h3>&raquo; <?php print $location; ?></h3>-->
	</div>
	
	<div class="preview_text">
		<h2 class="title"><?php print $title ?></h2>
		<h4><?php print format_date($comment->timestamp); ?>  |  <?php print link_report_abuse('comment',$comment->cid); ?></h4>
    <?php print $content ?>
    <div class="ton86_infobox">
      <p>Username : <span><?php print $comment->name; ?></span></p>
      <p>Forum Posts : <span><?php print $total_posts; ?></span></p>
      <p>Location : <span><?php print $location; ?></span></p>
      <div class="clr"></div>
    </div>
    <?php if ($signature): ?>
    <div class="user-signature clear-block">
      <?php print $signature ?>
    </div>
    <?php endif; ?>
	</div>
	<div class="clr"></div>
	<?php print $links ?>
</div>
</div>
