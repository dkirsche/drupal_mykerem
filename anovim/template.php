<?php
// $Id: template.php,v 1.16 2007/10/11 09:51:29 goba Exp $

include('template_ss81.php');
include('template_deuxcode.php');

/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return a string containing the breadcrumb output.
 */
function phptemplate_breadcrumb($breadcrumb) {
  if (!empty($breadcrumb)) {
    If (drupal_get_title() == 'View Article Entry') {
      $breadcrumb[] = l('Articles','articles'); 
    }
    $breadcrumb[] = drupal_get_title();
    
    // change breadcrumb for forum topic posts
    if (arg(0)=='node' && arg(1)=='add' && arg(2)=='forum') {
      
      $term = taxonomy_get_term(arg(3));
      $parent = taxonomy_get_parents(arg(3));
      //print key($parent);
      //print_r($parent);
      unset($breadcrumb);
      $breadcrumb[] = l('Home',''); 
      $breadcrumb[] = l('Forum','forum');
      $breadcrumb[] = l($parent[key($parent)]->name,'forum/'.$parent[key($parent)]->tid);
      $breadcrumb[] = l($term->name,'forum/'.$term->tid);
      $breadcrumb[] = 'Create Forum Topic';
    }
    
    return '<ul><li>'. implode($breadcrumb, ' › </li><li>') .'</li></ul>';
  }
}

/**
 * Allow themable wrapping of all comments.
 */
function phptemplate_comment_wrapper($content, $node) {
	global $user;
  if (!$content || $node->type == 'forum') {
    return '<div id="comments">'. $content .'</div>';
  }
  else {
  	if($user->uid) {
  		return '<div id="comments"><h2 class="comments">'. t('Comments') .'</h2>'. $content .'</div>';
  	}else {
  		return '<div id="comments"><h2 class="comments">'. t('Comments') .'</h2>'. $content .'<p>'.t('Please ').l('login','user').t(' to post a comment').'</p></div>';
  	}
  }
}

/**
 * Override or insert PHPTemplate variables into the templates.
 */
function phptemplate_preprocess_page(&$vars) {
  $vars['tabs2'] = menu_secondary_local_tasks();

  // Create additional template files for pages
  if (module_exists('path')) {
    $alias = drupal_get_path_alias(str_replace('/edit','',$_GET['q']));
    if ($alias != $_GET['q']) {
      $suggestions = array();
      $template_filename = 'page';
      foreach (explode('/', $alias) as $path_part) {
        $template_filename = $template_filename . '-' . $path_part;
        $suggestions[] = $template_filename;
      }
    }
    $vars['template_files'] = $suggestions;
  }

}

/**
 * Returns the rendered local tasks. The default implementation renders
 * them as tabs. Overridden to split the secondary tasks.
 *
 * @ingroup themeable
 */
function phptemplate_menu_local_tasks() {
  return menu_primary_local_tasks();
}

function phptemplate_comment_submitted($comment) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $comment),
      '!datetime' => format_date($comment->timestamp)
    ));
}

function phptemplate_node_submitted($node) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $node),
      '!datetime' => format_date($node->created),
    ));
}

/**
 * Generates IE CSS links for LTR and RTL languages.
 */
function phptemplate_get_ie_styles() {
  global $language;

  $iecss = '<link type="text/css" rel="stylesheet" media="all" href="'. base_path() . path_to_theme() .'/style-fix-ie.css" />';
  $ie6css = '<link type="text/css" rel="stylesheet" media="all" href="'. base_path() . path_to_theme() .'/style-fix-ie6.css" />';
  
  if (defined('LANGUAGE_RTL') && $language->direction == LANGUAGE_RTL) {
    $iecss .= '<style type="text/css" media="all">@import "'. base_path() . path_to_theme() .'/fix-ie-rtl.css";</style>';
  }

  return $iecss;
}


/**
 * Implementation of hook theme
 */
function anovim_theme() {
  return array(
    // Override the default user login form
    'user_login_block' => array(
      'template' => 'user_login',
      'arguments' => array('form' => NULL),
    ),
    // Override forward form
    'forward_form' => array(
      'arguments' => array('form' => NULL),
    ),
	//Overrride comment form
	'comment_form' => array('arguments' => array('form' => NULL),),
	
	//Override forum form
  //'forum_node_form' => array('arguments' => array('form' => NULL),),
  );
}

function anovim_preprocess_user_login_block(&$variables) {
  $variables['intro_text'] = t('This is my awesome login form');
  $variables['rendered'] = drupal_render($variables['form']);
}

// theme the crap out the comment form
function anovim_comment_form($form) {
//  $noded=node_load(arg(2));
//  if($noded->type=='forum') {
//  $form['_author']['#type'] = 'hidden';
  $form['_author']['#title'] = '';	
  $form['_author']['#value'] = '';
//  }	
  return drupal_render($form);
}


/**
 * Function for handling abuse links.
 * 
 * @param $type
 *  possible values are 'comment' (default), 'node', or 'user'
 * 
 * @return a string containing the link
 * 
 * TODO: check if the item is flagged or not      
 */
function link_report_abuse($type = 'comment', $item_id = NULL) {
  if ($item_id > 0) {
    $link = '<a href="'. url('flag_content/add/'.$item_id.'/'.$type) .'">'.t('Report Abuse').'</a>';
  } else {
    $link = '';
  }
  return $link;
}

/**
 * Function for pager in forum
 * @param $TopicID
 * get the $TopicID topic for queries
 *
 */
function ton86_pager_forum_list($TopicID,$NumTopic) {
  $ton86_path  = base_path() . path_to_theme() . '/';
  $output='';
  $options = array('attributes' => array('class' => 'ton86_pager'));

  
  $NumberOfResults=comment_num_all($TopicID);
  $Limit = 10; //Number of results per page
  $NumberOfPages=ceil($NumberOfResults/$Limit);
   
  $rows = array();
  $page_no=1;
  
  for($i=0;$i<$NumberOfPages;$i++)
		{
			if ($page_no==1){
				$rows[] = array(l($page_no,t('node/').$TopicID,$options));
			}else{
				$rows[] = array(l($page_no,'node/'.$TopicID.'?page='.$page_no,$options));
			}
			$page_no++;
		}
  if ($NumTopic>0){
  	$total_comment=comment_num_all($TopicID);
    $output .='<div class="back_main">';
    $output .='<div class="list_img"><img src="'.$ton86_path.'images/list_img.jpg" alt="img" /></div>';
    $output .='<div class="bac_box3">';
    $output .='<ul class="ton86_pager" id="'.$total_comment.'">';
    $output .=ton86_count_pages($rows);
    $output .='</ul>';
    $output .='<div class="clr"></div>';
    $output .='</div>';
    $output .='<div class="clr"></div>';
    $output .='</div>'; 	
  }

  return $output;
}

/**
 * function count how many pages and divide it
 * 
 */
function ton86_count_pages($no_row) {
	$var= '';
	for($i=0;$i<count($no_row);$i++)
		{
			$var.='<li>'.urldecode(implode($no_row[$i],' ')).'</li>';
		}
	return $var;
}

/**
 * function to get the forum pager
 * 
 */
function ton86_forum_pager($node) {
  $comments_per_page = _comment_get_display_setting('comments_per_page', $node);
  $num_comments = $node->num_comments;

	if (($num_comments) >= $comments_per_page) {
		$total_pages = ceil(($num_comments) / $comments_per_page);

		for ($i = 0; $i < $num_comments; $i += $comments_per_page)	{
      $items[] = array(
        'class' => 'forum-pager-item',
        'data' => ($i == 0 ? t('Pages: ') . '[ ' : '')
        . l(t($i + 1), "node/$node->nid", array('query' => array('page' => $i)))
        . ($i + $comments_per_page > $num_comments - 1 ? ' ]' : ''),
      );

			if ($i == 0 && $total_pages > 5) {
				$items[] = array('data' => '...');
				$i += ($total_pages - 4) * $comments_per_page;
			}
		}
  	return theme('item_list', $items, NULL, 'ul', array('class' => 'forum-pager'));
	}
  //print '<pre>'; print_r($items); print '</pre>';
}

/**
 * function to get topic owner
 * 
 */
function ton86_get_topic_owner($tid) {
	
  $options = array('attributes' => array('class' => 'ton86_moderator'));
  $query=db_query("SELECT f.nid,f.tid,n.uid,u.name FROM forum f LEFT JOIN node n ON f.nid=n.nid LEFT JOIN users u ON n.uid=u.uid WHERE n.nid=%d", $tid);
  $account=db_fetch_object($query);
  $moderators = l($account->name,'user/'.$account->uid,$options);

  return $moderators;
}

/**
 * function to replace the tilte for blog
 * 
 */
function phptemplate_comment_form_box($edit, $title = NULL) {
  if(arg(0)=='blog') {
  	$title='Add your Own Comment';
	return theme('box', $title, drupal_get_form('comment_form', $edit, $title));
  }else {
    return theme('box', $title, drupal_get_form('comment_form', $edit, $title));	
  }
}

/**
 * function to get no. of post
 * 
 */
function ton86_count_post($uid) {
	$from = time() - (61 * 24 * 60 * 60);
	$to=time()  + (1 * 24 * 60 * 60) ;

	$result=db_query("SELECT COUNT(nid) AS total FROM {node} n WHERE (n.created BETWEEN $from AND $to) AND (n.type in ('blog') AND n.status != 0) AND n.uid=$uid");
	$no_post=db_fetch_object($result);
	return $no_post->total;
}


/**
 * Returns a clean unformatted string
 */
function deuxcode_sanitize_str($string) {
  $clean = trim($string);
	$clean = str_replace('<p>', '', $clean);
  $clean = str_replace('</p>', '', $clean);
	$clean = strip_tags($clean);
  return $clean;
}
/**
 * Returns n words from a string
 */
function deuxcode_shorten_str($string, $wordsreturned) {
	$array = explode(" ", $string);
	array_splice($array, $wordsreturned);
	$retval = implode(" ", $array)."...";
  return $retval;
}


/**
 * Ton code (moved from page.tpl.php)
 */
drupal_add_js('
  $(function() {
    var zIndexNumber = 10000;
    $("div").each(function() {
      $(this).css("zIndex", zIndexNumber);
      zIndexNumber -= 10;
    });
  });
','inline');

/**
 * function to change the pager in blog
 * 
 */
function anovim_pager($tags = array(), $limit = 10, $element = 0, $parameters = array(), $quantity = 9) {
  global $pager_page_array, $pager_total;
  
//  if(arg(0)=="blog" || arg(0)=="blogs" || arg(0)=="forum") {
	  // Calculate various markers within this pager piece:
	  // Middle is used to "center" pages around the current page.
	  $pager_middle = ceil($quantity / 2);
	  // current is the page we are currently paged to
	  $pager_current = $pager_page_array[$element] + 1;
	  // first is the first page listed by this pager piece (re quantity)
	  $pager_first = $pager_current - $pager_middle + 1;
	  // last is the last page listed by this pager piece (re quantity)
	  $pager_last = $pager_current + $quantity - $pager_middle;
	  // max is the maximum page number
	  $pager_max = $pager_total[$element];
	  // End of marker calculations.
	
	  // Prepare for generation loop.
	  $i = $pager_first;
	  if ($pager_last > $pager_max) {
	    // Adjust "center" if at end of query.
	    $i = $i + ($pager_max - $pager_last);
	    $pager_last = $pager_max;
	  }
	  if ($i <= 0) {
	    // Adjust "center" if at start of query.
	    $pager_last = $pager_last + (1 - $i);
	    $i = 1;
	  }
	  // End of generation loop preparation.
	
	  $li_first = theme('pager_first', (isset($tags[0]) ? $tags[0] : t('First')), $limit, $element, $parameters);
	  $li_previous = theme('pager_previous', (isset($tags[1]) ? $tags[1] : t('Previous')), $limit, $element, 1, $parameters);
	  $li_next = theme('pager_next', (isset($tags[3]) ? $tags[3] : t('Next')), $limit, $element, 1, $parameters);
	  $li_last = theme('pager_last', (isset($tags[4]) ? $tags[4] : t('Last')), $limit, $element, $parameters);
	
	  if ($pager_total[$element] > 1) {
	    if ($li_first) {
	      $items[] = array(
	        'class' => 'pager-first',
	        'data' => $li_first,
	      );
	    }
	    if ($li_previous) {
	      $items[] = array(
	        'class' => 'pager-previous',
	        'data' => $li_previous,
	      );
	    }
	    if ($li_next) {
	      $items[] = array(
	        'class' => 'pager-next',
	        'data' => $li_next,
	      );
	    }
	    // When there is more than one page, create the pager list.
	    if ($i != $pager_max) {
	      if ($i > 1) {
	        $items[] = array(
	          'class' => 'pager-ellipsis',
	          'data' => '…',
	        );
	      }
	      // Now generate the actual pager piece.
	      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
	        if ($i < $pager_current) {
	          $items[] = array(
	            'class' => 'pager-item',
	            'data' => theme('pager_previous', $i, $limit, $element, ($pager_current - $i), $parameters),
	          );
	        }
	        if ($i == $pager_current) {
	          $items[] = array(
	            'class' => 'pager-current',
	            'data' => $i,
	          );
	        }
	        if ($i > $pager_current) {
	          $items[] = array(
	            'class' => 'pager-item',
	            'data' => theme('pager_next', $i, $limit, $element, ($i - $pager_current), $parameters),
	          );
	        }
	      }
	      if ($i < $pager_max) {
	        $items[] = array(
	          'class' => 'pager-ellipsis',
	          'data' => '…',
	        );
	      }
	    }
	    // End generation.
	    //NEXT is HERE.. ORIGINALLY
	    if ($li_last) {
	      $items[] = array(
	        'class' => 'pager-last',
	        'data' => $li_last,
	      );
	    }
	    return theme('item_list', $items, NULL, 'ul', array('class' => 'pager'));
	  } 	
//  } // end if blog...
}
