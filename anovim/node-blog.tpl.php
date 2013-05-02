<?php
// Debugging

#print '<!-- ';
#print_r( $node);
#print ' -->';


// Make variable accessible
global $base_path;

// Set new title
if ($page != 0) {
  drupal_set_title('View Blog Entry');
}

// add bookmarking script
drupal_add_js($directory .'/scripts.js');

// link to this node
$pagelink = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];


/**
 * ajax for rating
 *//*
if ($page != 0) {
  drupal_add_js('
  $(document).ready(function(){
      $("#ratelink-rate-popup").hide();
      $("a.ratelink").click(function () {
        $("#ratelink-rate-popup").toggle("fast");
      });
    });'
  ,'inline');
}
*/
?>

<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

<?php
if ($page != 0 && $user->uid == $node->uid) {
  ?>
  <div class="art_box">
   	<div class="modify_box">
      <img src="<?php print $base_path . $directory ?>/images/modify.jpg" alt="Click to edit" />
      <h4><a href="<?php print url('node/'.$node->nid.'/edit') ?>">Modify This Post</a> &nbsp;|</h4>
    </div>

    <div class="modify_box">
      <img src="<?php print $base_path . $directory ?>/images/delet.jpg" alt="Click to delete" />
      <h4><a href="<?php print url('node/'.$node->nid.'/delete') ?>">Delete This Post</a></h4>
    </div>

   	<div class="clr"></div>
  </div>
  <?php
}

if ($page == 0) {
  ?>
  <h2 class="title"><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
  <?php
}
else {
  ?>
  <div class="spacer5"></div>
  <h1 class="title"><span><?php print $title ?></span></h1>
  <h4 class="author">
    Author: <a href="<?php print url('user/'.$node->uid) ?>" class="nounderline"><?php print $node->name ?></a>
    <span class="small">(<a class="small" href="<?php print url('blog/'.$node->uid) ?>"> View All <?php print $node->name ?>´s Blogs</a> )</span>
  </h4>
  <?php
}

if ($submitted) {

  $statistics = statistics_get($node->nid);
  if ($statistics) {
    $reads = '| Viewed: '. format_plural($statistics['totalcount'], '1 Time', '@count Times');
  } else {
    $reads = '| Viewed: 0 Times';
 	}
  ?>
  <h4 class="submitted">
    <?php if ($page == 0) { print t('Posted by: ') . l($node->name,'user/'.$node->uid) .' | '; } ?>
    <?php print t('Posted: ') . format_date($node->created, 'custom', 'm.d.y g:ia T' ); ?>
    <?php print $reads ; ?>
    <?php
      // get avarage rating
     if (deuxcode_fivestar_rating_widget_result($node)) {
     	print '| Rating '. deuxcode_fivestar_rating_widget_result($node) .'';
     }
    ?>
  </h4>
  <?php
}
?>

  <div class="content clear-block">
    <?php print $image ?>
    <?php
    $text = $content;

    // Show only on teaser view
    if ($page == 0) {
      $text = deuxcode_sanitize_str($text);
      // Set word count different on personal blogs
      if (arg(0)=='blog' && is_numeric(arg(1))) {
        $wordcount = 50; // personal blog
      } else {
        $wordcount = 35; // community blog pages
      }
      $text = deuxcode_shorten_str($text,$wordcount);
      $text .= ' <a href="'. $node_url .'" title="'.t('Read full article').'" class="readmore">'.t('Read more').'</a>';
    }
    print '<p>'.$text.'</p>';

    ?>
  </div>

<?php
if ($page == 0) {
  ?>
  <div class="comment">
    <div class="small_box1">
      <img src="<?php print $base_path . $directory ?>/images/small_imt.jpg" alt="comment" />
      <h4><a href="<?php print $node_url; ?>/#comments"><?php print format_plural($comment_count, t('1 Comment'), t('@count Comments')); ?></a>&nbsp;&nbsp; |</h4>
      <div class="clr"></div>
    </div>

    <div class="small_box2">
      <img src="<?php print $base_path . $directory ?>/images/plus.jpg" alt="plusl" />
      <h4><a href="javascript:void(0);" onclick="javascript:bookmarksite('<?php print str_replace('\'',"\'",$node->title) ?>', '<?php print $pagelink ?>')">Favorite</a></h4>
      <div class="clr"></div>
    </div>

    <div class="clr"></div>
  </div>
  <?php
}

if ($page != 0) {
  ?>
  <div class="spacer"></div>
  <div class="comment1">
    <div class="small_box1 nomarg">
      <img src="<?php print $base_path . $directory ?>/images/small_imt.jpg" alt="comment" />
      <h4><?php print format_plural($comment_count, t('1 Comment'), t('@count Comments')); ?>&nbsp;&nbsp; |</h4>
      <div class="clr"></div>
    </div>

    <div class="small_box6">
      <img src="<?php print $base_path . $directory ?>/images/commetn.jpg" alt="coment" />
      <h4><a href="#comments">Add Comment</a>&nbsp; |</h4>
      <div class="clr"></div>
    </div>

    <div class="small_box7 rating-widget">
    <?php
      // get and print the fivestar widget
      print deuxcode_fivestar_rating_widget($node,5);
    ?>
    </div>

    <div class="small_box7">
      <img src="<?php print $base_path . $directory ?>/images/mail.jpg" alt="mail" />
      <h4><a href="<?php print url('forward',array('query' => array('path' => 'node/'.$node->nid))) ?>">Tell A Friend </a></h4>
      <div class="clr"></div>
    </div>

    <div class="clr"></div>
  </div>


  <div class="art_box nopad"></div>

  <div id="share">
    <h1>Share:</h1>
    <div class="share_box">
      <img class="share_img" src="<?php print $base_path . $directory ?>/images/stum.jpg" alt="stum" />
      <h3 class="share_text"><a href="http://www.stumbleupon.com/submit?url=<?php print $pagelink; ?>&amp;title=<?php print $title; ?>">StumbleUpon</a> |</h3>
      <div class="clr"></div>
    </div>

    <div class="share_box1">
      <img class="share_img" src="<?php print $base_path . $directory ?>/images/del.jpg" alt="del" />
      <h3 class="share_text"><a href="http://del.icio.us/post?url=<?php print $pagelink; ?>&amp;title=<?php print $title; ?>">Del.icio.us</a> |</h3>
      <div class="clr"></div>
    </div>

    <div class="share_box2">
      <img class="share_img" src="<?php print $base_path . $directory ?>/images/feed.jpg" alt="feed" />
      <h3 class="share_text"><a href="http://www.facebook.com/sharer.php?u=<?php print $pagelink; ?>&amp;t=<?php print $title; ?>">Facebook</a>|</h3>
      <div class="clr"></div>
    </div>

    <div class="share_box3">
      <img class="share_img" src="<?php print $base_path . $directory ?>/images/tewter.jpg" alt="tweter" />
      <h3 class="share_text"><a href="http://twitthis.com/twit?url=<?php print $pagelink; ?>">Twitter</a> |</h3>
      <div class="clr"></div>
    </div>

    <div class="share_box4">
      <img class="share_img" src="<?php print $base_path . $directory ?>/images/dig.jpg" alt="dig" />
      <h3 class="share_text"><a href="http://digg.com/submit?phase=2&amp;url=<?php print $pagelink; ?>&amp;title=<?php print $title; ?>">Dig</a> |</h3>
      <div class="clr"></div>
    </div>

    <div class="share_box3">
      <img class="share_img" src="<?php print $base_path . $directory ?>/images/myspace.jpg" alt="myspace" />
      <h3 class="share_text"><a href="http://www.myspace.com/Modules/PostTo/Pages/?t=<?php print $title; ?>&amp;c=<?php print $pagelink; ?>">MySpace</a></h3>
      <div class="clr"></div>
    </div>

    <div class="clr"></div>
  </div>
  <?php
}
?>
</div>
