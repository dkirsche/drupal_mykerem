<?php
/**
 * Debugging
 *//*
  print '<!-- ';
  print_r($node);
  print ' -->';
*/

//block-views-featured_article-block_1
//views-view.tpl.php

/**
 * Set necessary variables
 */
global $base_path;      // Make variable accessible
global $node_count;     // Counter for nodes
$pagelink = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];  // link to this node

/**
 * Node Counter
 */
if(!$node_count) $node_count = 0;
$node_count ++;

/**
 * Get image
 * but get it just on featured article, if image exist and if full page view 
 */ 
if ($node->view->name == 'featured_article' || $page != 0) {
  if ($node->field_article_image[0]['filepath'] != '') {
  //$first_post = TRUE; // flag for first post on article page
    $image_filepath = $node->field_article_image[0]['filepath'];
    $image = '<div class="field-field-article-image">'.theme('imagecache', 'article_image_thumb', $image_filepath).'</div>';
    $styles_extra = 'style="font-size:1.6em"';
  }
}

/**
 * Get CCK fields
 */
// source field
if ($node->field_article_source[0]['title'] != '') {
  $source = '| '. t('Source: ') . content_format('field_article_source', $node->field_article_source[0]);
}
// author field
if ($node->field_article_author[0]['value'] != '') {
  $author = $node->field_article_author[0]['value'];
} else {
  $author = l($node->name,'user/'.$node->uid);
}


/**
 * only show on page view 
 */
if ($page != 0) {

  // Set new page title
  drupal_set_title('View Article Entry');
  
  // Add js for ratings ratings
  drupal_add_js('
  $(document).ready(function(){
      $("#ratelink-rate-popup").hide();
      $("a.ratelink").click(function () {
        $("#ratelink-rate-popup").toggle("fast");
      });
    });'
  ,'inline');
}


// --- Start page output ---
?>
<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> node-cnt<?php print $node_count ?>">

<?php if ($page == 0) { ?>
  <h2 class="title" <?php print $styles_extra ?>><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
<?php } else { ?>
  <h1 class="title"><span><?php print $title ?></span></h1>
<?php } ?>

<?php if ($first_post || $page != 0): ?>
  <h2 class="author">Author: <?php print $author ?>
  <?php
    // get avarage rating
   if (deuxcode_fivestar_rating_widget_result($node)) {
   	print '| Rating '. deuxcode_fivestar_rating_widget_result($node) .'';
   }
  ?>
  </h2>
<?php endif; ?>

<?php if ($submitted): ?>
  <h4 class="submitted"><?php print t('Published: ') . format_date($node->created, 'custom', 'm.d.y' ); ?> <?php print $source; ?></h4>
<?php endif; ?>

  <div class="content clear-block">
    <?php print $image ?>
    <?php
    
    $text = $content;

    // Show only on teaser view
    if ($page == 0) {
      if ($node->view->name != 'featured_article') {
        $text = deuxcode_sanitize_str($text);
        $text = deuxcode_shorten_str($text,35);
      }
      else {
        //$text = deuxcode_sanitize_str($text);
        $text = deuxcode_shorten_str($text,150);
      }
      $text .= ' <a href="'. $node_url .'" title="'.t('Read full article').'" class="readmore">'.t('Read more').'</a>';
    }
    print '<p>'.$text.'</p>';
    ?>
  </div>

<?php
/**
 * Shown only on teaser
 */
if (1 == 0) { ?>
  <div class="comment">
    <div class="small_box1">
      <img src="<?php print $base_path . $directory ?>/images/small_imt.jpg" alt="comment" />
      <h4><a href="<?php print $node_url; ?>/#comments"><?php print format_plural($comment_count, t('1 Comment'), t('@count Comments')); ?></a>&nbsp;&nbsp; |</h4>
      <div class="clr"></div>
    </div>

   <div class="small_box2">
      <img src="<?php print $base_path . $directory ?>/images/plus.jpg" alt="plusl" />
      <h4><a href="javascript:void(0);" onclick="javascript:bookmarksite('<?php print str_replace('\\',"'",$node->title) ?>', '<?php print $pagelink ?>')">Favorite</a></h4>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
<?php }; ?>

<?php
/**
 * Shown only on page views
 */ 
if ($page != 0) {
?>
<div class="spacer"></div>
<div class="comment1">
  <div class="small_box6">
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
  <div class="clr"/></div>
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
  /**
   * Print info for comments when not logged in
   */     
  if ($user->uid == 0) {
    print '<div class="spacer"></div><p>'.$node->links['comment_forbidden']['title'].'</p>';
  }
?>
</div>
<?php }; ?>
</div><!-- /.node -->

<?php
/**
 * Just show the most recent text where we want...
 */ 
if (arg(0) == 'articles' && arg(1) == '' && $node_count == 1 && date('n',$node->created) == date('n') ){
?>
<!-- <div class="blog_tille">
  <h3>Current Articles</h3>
  <div class="clr"></div>
</div><div class="spacer5"></div> --><?php
}
?>
