<?php
// $Id: forum-topic-list.tpl.php,v 1.4.2.1 2008/10/22 18:22:51 dries Exp $
/**
 * @file forum-topic-list.tpl.php
 * Theme implementation to display a list of forum topics.
 *
 * Available variables:
 * - $header: The table header. This is pre-generated with click-sorting
 *   information. If you need to change this, @see template_preprocess_forum_topic_list().
 * - $pager: The pager to display beneath the table.
 * - $topics: An array of topics to be displayed.
 * - $topic_id: Numeric id for the current forum topic.
 *
 * Each $topic in $topics contains:
 * - $topic->icon: The icon to display.
 * - $topic->moved: A flag to indicate whether the topic has been moved to
 *   another forum.
 * - $topic->title: The title of the topic. Safe to output.
 * - $topic->message: If the topic has been moved, this contains an
 *   explanation and a link.
 * - $topic->zebra: 'even' or 'odd' string used for row class.
 * - $topic->num_comments: The number of replies on this topic.
 * - $topic->new_replies: A flag to indicate whether there are unread comments.
 * - $topic->new_url: If there are unread replies, this is a link to them.
 * - $topic->new_text: Text containing the translated, properly pluralized count.
 * - $topic->created: An outputtable string represented when the topic was posted.
 * - $topic->last_reply: An outputtable string representing when the topic was
 *   last replied to.
 * - $topic->timestamp: The raw timestamp this topic was posted.
 *
 * @see template_preprocess_forum_topic_list()
 * @see theme_forum_topic_list()
 */

/**
 * Get sort by parameter
 */
$sortby = $_GET['sort'];
if ($sortby == 'asc') {
  $sortby = 'desc';
} else {
  $sortby = 'asc';
}

$ton86_path  = base_path() . path_to_theme() . '/';
//print '...'.$topic->icon.'...';


/**
 * Set new title
 *//*
$parent = taxonomy_get_parents(arg(1));  // get parent forum
$term = taxonomy_get_term(arg(1));       // get current forum
drupal_set_title($parent[key($parent)]->name.': '.$term->name); // set title
*/

?>
<table id="forum-sticky-topic-<?php print $topic_id; ?>">
   <thead>
    <tr>
      <th class="forum" colspan="2"><a href="?sort=<?php print $sortby ?>&amp;order=Topics"><?php print t('Announcements'); ?></a></th>
      <th class="replies" style="width:50px"><a href="?sort=<?php print $sortby ?>&amp;order=Replies"><?php print t('Replies');?></a></th>
      <th class="replies" style="width:50px"><a href="?sort=<?php print $sortby ?>&amp;order=Views"><?php print t('Views'); ?></a></th>
      <th class="last-reply" style="width:100px"><a href="?sort=<?php print $sortby ?>&amp;order=Last+Reply"><?php print t('Last post'); ?></a></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($topics as $topic): ?>
    <?php if($topic->sticky): ?>
    <tr class="<?php // print $topic->zebra;?>">
      <td class="icon"><img src="<?php print $ton86_path.'images/mail2.jpg';//$topic->icon; ?>" alt="Announcement" title="Announcement" /></td>
      <td class="title">
	    <?php print $topic->title; ?>
	    <!-- GET FORUM OWNER -->
	    <p class="content_ptext"><?php print t("By: ").ton86_get_topic_owner($topic->nid); ?></p>
	    <!-- END GET FORUM OWNER -->
	    <!-- START FOR BACK MAIN/PAGER -->
		<?php // print ton86_pager_forum_list($topic->nid,$topic->num_comments) ?>
		<?php //print_r($topics) ?>
		<!-- END FOR BACK MAIN/PAGER-->			  
	  </td>
    <?php if ($topic->moved): ?>
      <td colspan="3"><?php print $topic->message; ?></td>
    <?php else: ?>
      <td class="replies">
        <?php print $topic->num_comments; ?>
        <?php if ($topic->new_replies): ?>
          <br />
          <a href="<?php print $topic->new_url; ?>"><?php print $topic->new_text; ?></a>
        <?php endif; ?>
      </td>
      <td class="replies">
	  <?php
	  $statistics = statistics_get($topic->nid);
	  if($statistics['totalcount']) {
	  	$reads = $statistics['totalcount'];
	  }else {
	  	$reads=0;
	  }
	  ?>
		<?php print $reads ; ?>
	  </td>
      <td class="last-reply"><?php
        if ($topic->last_reply = 'n/a') {
        	print $topic->created;
        }
        else {
          print $topic->last_reply;
        }
      ?></td>
    <?php endif; ?>
    </tr>
    <?php endif; ?>
  <?php endforeach; ?>
  </tbody>
</table>
<div class="spacer"></div>
<table id="forum-topic-<?php print $topic_id; ?>">
   <thead>
    <tr>
      <th class="forum" colspan="2"><a href="?sort=<?php print $sortby ?>&amp;order=Topics"><?php print t('Topics'); ?></a></th>
      <th class="replies" style="width:50px"><a href="?sort=<?php print $sortby ?>&amp;order=Replies"><?php print t('Replies');?></a></th>
      <th class="replies" style="width:50px"><a href="?sort=<?php print $sortby ?>&amp;order=Views"><?php print t('Views'); ?></a></th>
      <th class="last-reply" style="width:100px"><a href="?sort=<?php print $sortby ?>&amp;order=Last+Reply"><?php print t('Last post'); ?></a></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($topics as $topic): ?>
  <?php
  // Hack to put alt and title text on icons
   $topic_icon = $topic->icon;
   $topic_icon = str_replace('/misc/forum-new.png" alt="" title=""','/misc/forum-new.png" alt="New Post" title="New Post"',$topic_icon);
   $topic_icon = str_replace('/misc/forum-default.png" alt="" title=""','/misc/forum-default.png" alt="Viewed Post" title="Viewed Post"',$topic_icon);
   $topic_icon = str_replace('/misc/forum-hot.png" alt="" title=""','/misc/forum-hot.png" alt="Hot Topic" title="Hot Topic"',$topic_icon);
   $topic_icon = str_replace('/misc/forum-closed.png" alt="" title=""','/misc/forum-closed.png" alt="Topic Closed" title="Topic Closed"',$topic_icon);
  ?>
    <?php if(!$topic->sticky): ?>
    <tr class="topic<?php // print $topic->zebra;?>">
      <td class="icon"><?php print $topic_icon; ?></td>
      <td class="title">
	    <?php print $topic->title; ?>
	    <!-- GET FORUM OWNER -->
	    <p class="content_ptext"><?php print t("By: ").ton86_get_topic_owner($topic->nid); ?></p>
	    <!-- END GET FORUM OWNER -->
	    <!-- START FOR BACK MAIN/PAGER -->
		<?php // print ton86_pager_forum_list($topic->nid,$topic->num_comments) ?>
		<?php //print_r($topics) ?>
		<!-- END FOR BACK MAIN/PAGER-->
	  </td>
    <?php if ($topic->moved): ?>
      <td colspan="3"><?php print $topic->message; ?></td>
    <?php else: ?>
      <td class="replies">
        <?php print $topic->num_comments; ?>
        <?php if ($topic->new_replies): ?>
          <br />
          <a href="<?php print $topic->new_url; ?>"><?php print $topic->new_text; ?></a>
        <?php endif; ?>
      </td>
      <td class="replies">
	  <?php
	  $statistics = statistics_get($topic->nid);
	  if($statistics['totalcount']) {
	  	$reads = $statistics['totalcount'];
	  }else {
	  	$reads=0;
	  }
	  ?>
		<?php print $reads ; ?>
	  </td>
      <td class="last-reply"><?php
        if ($topic->last_reply = 'n/a') {
        	print $topic->created;
        }
        else {
          print $topic->last_reply;
        }
      ?></td>
    <?php endif; ?>
    </tr>
    <?php endif; ?>
  <?php endforeach; ?>
  </tbody>
</table>
