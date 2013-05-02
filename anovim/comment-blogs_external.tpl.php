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

// FIX!
// Should be real name if set in profile
  $user_name = $author;

?>
<div class="comment<?php print ($comment->new) ? ' comment-new' : ''; print ' '. $status ?> clear-block">

  <?php // print $picture ?>
  <!-- <h3><?php print $title ?></h3> -->
  
  <h4>
    <span><?php print $user_name ?></span>
    | <?php print format_date($comment->timestamp, 'custom', 'm.d.y g:ia T' ) ?>
    | <?php print link_report_abuse('comment',$comment->cid); ?>
  </h4>

  <div class="content">
    <?php print $content ?>
    <?php if ($signature): ?>
    <div class="user-signature clear-block">
      <?php print $signature ?>
    </div>
    <?php endif; ?>
  </div>
  <?php // print $links; ?>
</div>
