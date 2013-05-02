<?php
/**
 * Create section title
 * Used as fallback if no image exist
 * Also set body classes for node pages other than 'page-node'
 */
$title_section = arg(0);

if ($title_section == 'node' || $title_section == 'search') {
  // Fix classes for body
  $url_array = explode('/', drupal_get_path_alias($_GET['q']));

  //$body_classes .= ' page-'. implode('-',$url_array); // skip this, just get the first 3
  if (arg(0)) {
    $body_classes .= ' page-'. arg(0);
  }

  if (arg(1)) {
    $body_classes .= '-'. arg(1);
  }

  if ($title_section == 'node') {
    if (arg(2)) {$body_classes .= '-'. arg(2);}
  }

  $title_section = implode(' ',$url_array);
  $title_section = str_replace('node','',$title_section);
}

/**
 * fix for comment on forums
 */
if(is_numeric(arg(2)) && is_numeric(arg(3))){
  $node = node_load(arg(2));
  $node_type = $node->type;
}

if ($node_type == 'forum'){
  unset($right); // remove right sidebar
  $body_classes .= ' page-forum-comment';
  $body_id = ' id="forum"';
}

// fix for regular comments
if(arg(1) == 'reply' && is_numeric(arg(2))){
  $node = node_load(arg(2));
  $node_type = $node->type;
}

if ($node_type == 'articles'){
  $body_classes .= ' node-type-articles';
}
elseif ($node_type == 'blog'){
  $body_classes .= ' node-type-blog';
}
elseif ($node_type == 'forum'){
  $body_classes .= ' node-type-forum';
  $body_id = ' id="forum"';
  $right = '';
  $title = 'Reply to Topic';
}

// remove right sidebar for forum nodes
if (strpos($body_classes, 'node-type-forum')){
  unset($right);
  $body_id = ' id="forum"';
}

/**
 * Change some page titles
 */
switch($_GET['q']) {
  case 'user/register':
    $title = 'Register';
    $tabs = '';
    break;

  case 'forward':
    $title_section = '';
    $tabs = '';
    break;
}

if (arg(0) == 'blog' && is_numeric(arg(1))) {
  $blog_user = user_load ( array('uid' => arg(1)) );
}

/**
 * Create and set back button
 */
if ($node->type == 'forum') {

  $parent_name = $node->taxonomy[key($node->taxonomy)]->name;
  $parent_tid =  $node->taxonomy[key($node->taxonomy)]->tid;
  
  $back_to_previous = '<a href="'.url('forum/'.$parent_tid).'">'.$parent_name.'</a>';

  if ($user->uid == 0) {
    $url = url('user/login').'?destination=comment/reply/'.$node->nid.'%2523comment-form';
  } else {
    $url = url('comment/reply/'.$node->nid).'%2523comment-form';
  }

  $footer_links = ' | <a href="'.$url.'">'.t('Add comment').'</a>';
} elseif ($node->type == 'blog') {
  $back_to_previous = '<a href="'.url('blog').'">'.t('Blog index').'</a>';
} elseif ($node->type == 'articles') {
  $back_to_previous = '<a href="'.url('articles').'">'.t('Article index').'</a>';
} else {
  // nothing...
}

/**
 * Create add comment link
 */
if ($node->type){
  
}
/**
 * Set a fixed body id based on path,
 * pathautho must be set up correctly
 * for this to work as intended.
 */ 
if (!$body_id) {
  $body_id = ' id="page-'.arg(0).'"';
}

/**
 * Get blog owner details,
 * 081210 - Added if statment to just load user on correct page 
 */
if (arg(0) == 'blog') {
  $u= user_load(array("uid" => $blog_user->uid));
}

/**
 * Clean up javascript inline code
 */
$scripts = str_replace('
//--><!]]>
</script>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
','',$scripts);


// --- begin page output ---

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
<head>
  <title><?php print $head_title ?></title>
  <?php print $head ?>
  <?php print $styles ?>
  <!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="<?php print $base_path . $directory ?>/style-fix-ie.css"><![endif]-->
  <!--[if lt IE 7]><link rel="stylesheet" type="text/css" href="<?php print $base_path . $directory ?>/style-fix-ie6.css"><![endif]-->
  <?php print $scripts ?>
  <link type="text/css" rel="stylesheet" media="all" href="<?php print $base_path . $directory ?>/ss81.css" />
</head>
<body<?php print $body_id; ?> class="<?php print $body_classes; ?>">
	<div id="main_bacground">
<!--wraper_start-->
   	  <div id="wraper">
<!--header_start-->
       	<div id="header">
       		<div class="logo"><img src="<?php print $base_path . $directory ?>/images/mykerem_logobold.jpg" alt="MyKerem" title="<?php print drupal_get_path_alias($_GET['q']) .' - '. $_GET['q']; ?>" width="231" height="147" /></div>
          <div class="images"><img src="<?php print $base_path . $directory ?>/images/header_img.jpg" alt="img" /></div>
          <div class="clr"></div>
        </div>
        <div class="clr"></div>
<!--header_end-->
<!--nav_start-->
	  	   <div id="nav">
          <?php if (isset($primary_links)) : ?>
            <?php print theme('links', $primary_links, array('class' => 'primary-links')) ?>
          <?php endif; ?>
          <div class="clr"></div>
        </div><!--nav_end-->
        <div class="clr"></div>
<!--content-->
             <div id="mid_content">

             <?php if ($content_top): print '<div id="blog_bannger">'. $content_top .'</div>'; endif; ?>
             <?php // if ($mission): print '<div id="mission">'. $mission .'</div>'; endif; ?>
             <?php if($is_front) {
             ?><div id="page"><?
              } else {
              ?><div id="page_blog">
                <div id="blog_topnav">
                <?php print $breadcrumb; ?>
                  <div class="clr"></div>
                  <?php if($title_section=="about-us"):?>
                    <div id="ton86_imagehandler"><img src="<?php print $base_path . $directory ?>/images/about.jpg" alt="img" /></div>
                  <?php elseif($title_section=="contact-us"):?>
                    <div id="ton86_imagehandler"><img src="<?php print $base_path . $directory ?>/images/contact.jpg" alt="img" /></div>
                  <?php elseif($title_section=="privacy-policy"):?>
                    <div id="ton86_imagehandler"><img src="<?php print $base_path . $directory ?>/images/privacy.jpg" alt="img" /></div>
                  <?php elseif($title_section=="terms-conditions"):?>
                    <div id="ton86_imagehandler"><img src="<?php print $base_path . $directory ?>/images/terms.jpg" alt="img" /></div>
                  <?php elseif($title_section=="search"):?>
                    <div id="ton86_imagehandler"><img src="<?php print $base_path . $directory ?>/images/section_search.jpg" alt="img" /></div>
                  <?php elseif($title_section=="webtv"):?>
                    <div id="ton86_imagehandler"><img src="<?php print $base_path . $directory ?>/images/webtv.jpg" alt="img" /></div>
                  <?php elseif($node->type=="poll"):?>
                    <div id="ton86_imagehandler"><img src="<?php print $base_path . $directory ?>/images/pol2x.jpg" alt="img" /></div>
                  <?php elseif($title_section=="poll"):?>
                    <div id="ton86_imagehandler"><img src="<?php print $base_path . $directory ?>/images/poll.jpg" alt="img" /></div>
                  <?php elseif(arg(0)=="forward"):?>
                    <div id="ton86_imagehandler"><img src="<?php print $base_path . $directory ?>/images/tools.jpg" alt="img" /></div>
                  <?php else:?>
                    <div id="section-headline"><?php if ($title_section): print ''. $title_section .''; endif; ?></div>
                  <?php endif; ?>
                  <div class="clr"></div>
                </div>
                <div class="clr"></div>
              <? } ?>

              <!--content_left-->
              <?php
              if($right) {
                $mid_id = 'mid_contentleft';
              } else {
                $mid_id = 'mid_contentleft2';
              }
              ?><div id="<?php print $mid_id; ?>">
              

              <?php if ($is_front): print '<div id="left_top">'. $content_front .'</div>'; endif; ?>

                    <div id="left">
                    <?php if ($left): ?>
                        <?php print $left ?>
                    <?php endif; ?>
                    </div>


                    <div id="right">

                     	  <div class="main_blog">
                        <?php
                        // insert forum header links
                        if (arg(0) == 'forum') {
                        ?>
                   		<div class="forum_righttop">
                        <div class="forum_topnav">
                          <ul>
                            <li><a href="<?php print url('forum') ?>">Forum Index</a>&nbsp; |</li>
                            <li><a href="<?php print url('forum/unanswered') ?>">View Unanswered posts</a>&nbsp; |</li>
                            <li><a href="<?php print url('forum/active') ?>">View Active Topics</a>&nbsp; | </li>
                            <li><a href="<?php print url('forum/today') ?>">Today’s Posts</a></li>
                          </ul>
                        <div class="clr"></div>
                        </div>
                        <h4 class="forum_text">It is currently <?php print date("F j, Y, g:i a"); ?></h4>
                        <div class="clr" style="margin-bottom:10px"></div>
                        </div><!-- /forum_righttop -->
                        <?php } //end forum header links ?>
                          
                        	  <?php if (arg(0) != 'blog') { ?>
                            <div class="main_blogtop<?php if (!$is_front): print '1'; endif; ?>">
                        	    <div class="blog_tille<?php if ( arg(0) == 'blogs' && arg(1) == 'search'  ) { print 'nono'; } ?><?php if ( arg(0) == 'forum' && arg(1) != ''  ) { print ' subforum'; } ?>">
                                <?php print '<p class="ptext2">'.$back_to_previous.$footer_links.'</p>'; ?>
                                <?php if ($title): print '<h1'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title .'</h1>'; endif; ?>
                                <?php if ($is_front): print '<img src="'. $base_path . $directory .'/images/recent.jpg" alt="recent" /><h2>Recent Articles</h2><h4><a href="'.url('articles').'">VIEW ALL ARTICLES</a></h4>'; endif; ?>
                                <div class="clr"></div>
                              </div>
                            </div>
                            <div class="clr"></div>
                            <?php } else {?>
                            <div class="main_blogtop1">
                              <div class="blog_tillenono">
                                <div class="blog1">
                                  <img src="<?php print $base_path . $directory; ?>/images/recent1.jpg" alt="recent" />
                                  <h1><?php print $blog_user->name.'\'s Blog'; ?></h1>
                                  <div class="clr"></div>
                                </div>
                              </div>
                            </div>
                            <div class="blog_content">
                              <div class="blog_tille2">
                                <p class="ton86_creationdate">Creation date: <?php print format_date($u->created, $type = 'custom', $format = 'm.j.y', $timezone = NULL, $langcode = NULL); ?></p>
								<div class="back_main1">
                                  <!-- <div class="spacer1"></div> -->
                                  <!--<div class="bac_box"><h4><a href="#">back</a></h4></div>-->
                                  <div class="bac_box3">
                                    <?php print theme('pager');//print user_pager($blog_user->uid); ?>
                                    <div class="clr"></div>
                                  </div>
                                  <!--<div class="bac_box"><h4><a href="#">next</a></h4></div>-->
                                  <div class="clr"></div>
                                </div>
								<div class="clr"></div>
								  <h2><span><?php print t('Showing ').ton86_count_post($blog_user->uid).t(' Posts'); ?></span></h2>
                              </div>
                            </div>
                            <?php } ?>
                            <?php
                            if($is_front) {
                              $main_class = 'main_blogmid clear-block';
                            } else {
                              $main_class = 'main_artmidtop';
                            }
                            ?><div class="<?php print $main_class; ?>">

                            
                              <?php if (arg(0) != 'user') { ?>
                                <?php if ($tabs): print '<div id="tabs-wrapper" class="clear-block">'; endif; ?>
                                <?php if ($tabs && $title_section != 'Blog entry'): print '<ul class="tabs primary">'. $tabs .'</ul>'; endif; ?>
                                <?php if ($tabs): print '</div>'; endif; ?>
                              <?php } ?>
                              <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
                              
                            	<div class="blog_content">
                              <?php if ($show_messages && $messages): print $messages; endif; ?>
                              <?php print $help; ?>
                                <?php if ($content_main_top): print '<div id="content_main_top">'. $content_main_top .'</div>'; endif; ?>
                                <?php print $content ?>
                            	</div>
                            </div>
                            <div class="main_blogbottom"></div>
                            <?php
                            if ($node->type == 'forum') {
                              print '<p class="ptext2">'.$back_to_previous.$footer_links.'</p>';
                            }
                            ?>
                        </div>

                        <?php print $content_middle ?>
                        <!-- kaffe -->
                        <?php
                        //print theme('pager');
                        //print user_pager($blog_user->uid);
                        ?>
                        

                        <?php if ($content_bottom): ?>
                        <div class="main_blog">
                        <?php if ($is_front): ?>
                        	<div class="main_blogtop">
                        	  <div class="blog_tille">
                               	  <img src="<?php print $base_path . $directory ?>/images/recent1.jpg" alt="recent" />
                                    <h2>Blogs</h2>
                                    <h4><a href="/blogs">VIEW ALL BLOGS</a></h4>
                                    <div class="clr"></div>
                              </div>
                            </div>
                            <div class="clr"></div>
                          <?php endif; // is_front ?>
                            <div class="main_blogmid">
                            	<div class="blog_content">
                            	<?php print $content_bottom ?>
                           	  </div>
                            </div>
                            <div class="main_blogbottom"></div>
                            
                        </div>
                        <?php endif; // content_bottom ?>
                    </div><!-- /#right -->
                </div>
<!--content_leftend-->

                <?php if ($right): ?>
                	<div id="mid_contentright">
                	<?php print $right ?>
                </div><!-- /#mid_contentright -->
                <?php endif; ?>

               	 <div class="clr"></div>
                 </div>

                <?php if ($banner_bottom): ?>
                	<div id="banner2"><?php print $banner_bottom ?></div>
                <?php endif; ?>
                <div class="spacer2"></div>
                <div class="clr"></div>
             </div><!-- /content_end -->
        <div class="clr"></div>

      </div><!-- /wraper_end -->
		<div id="bg_bottom"><img src="<?php print $base_path . $directory ?>/images/bg_bootm.jpg" alt="bg" /></div>
    </div>

 <!--footer-->
 	<div id="footer">
 	  <?php print $footer ?>
 	  <p><?php print $footer_message ?></p>
    <div class="clr"></div>
  </div>
  <!--footer_end-->
  <?php print $closure ?>
</body>
</html>
