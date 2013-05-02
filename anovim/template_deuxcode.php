<?php
/******************************************************************************
 * template_deuxcode.php
 ******************************************************************************/

/**
 * Add table sorter to profile pages
 */ 
if (arg(0)=='search' && arg(1) == 'profiles') {
  drupal_add_js(path_to_theme().'/js/jquery.tablesorter.min.js');
  drupal_add_js('
  $(document).ready(function(){
    $("#profile_search-results table").tablesorter();
  });
  ','inline');
}


/**
 * Redirect from Blog to Blogs
 */ 
if (drupal_get_path_alias($_GET['q'])=='blog') {
  drupal_goto('blogs');
}





/**
 * Forward form modifications
 */ 
if (arg(0)=='forward') {
  
  // Set title for forward page
  drupal_set_title('Your Message');
  
  /**
   * Override the default forward form
   */     
  function anovim_forward_form($form) {

    // Change form values
    $form['message']['message']['#title'] = 'Your Message';
//    $form['message']['subject']['#title'] = 'Subject';
//    $form['message']['body']['#title'] = 'Body';
    $form['message']['page']['#title'] = '';

    // hide elements
    $form['message']['subject']['#type'] = 'hidden';
    $form['message']['body']['#type'] = 'hidden';

    // Re-order output
    $output = '<p>'.t('You are going to email the following:').'</p>';    
    $output .= '<h2>'.drupal_render($form['message']['page']).'</h2>';
//    $output .= '<div class="container-inline">';
//    $output .= drupal_render($form['message']['subject']);
//    $output .= '</div><br />';
//    $output .= '<div class="container-inline">';
//    $output .= drupal_render($form['message']['body']);
//    $output .= '</div>';
    $output .= '<div class="blog_tille"></div>';

    $output .= drupal_render($form);

    $link = "javascript:location.href='".url($_GET['path'])."';";
    $form = array(
      '#type' => 'markup',
      '#value' => '<input type="button" class="form-submit" value="Cancel" name="cancel" onclick="'.$link.'" />',
    );

    $output .= drupal_render($form);
    return $output;
  }
} // END if forward page






/**
 * Add prefilled value to profile search block
 * TODO: $block->enabled 
 */ 
if (drupal_get_path_alias($_GET['q'])=='profile') {

  drupal_add_js(path_to_theme().'/js/jquery.example.min.js');
  drupal_add_js('
  $(document).ready(function(){
    $("input#edit-ps-keys").example("Enter search terms");
  });
  ','inline');
}

/*
* Override filter.module's theme_filter_tips() function to disable tips display.
*/
function phptemplate_filter_tips($tips, $long = FALSE, $extra = '') {
  return '';
}
function phptemplate_filter_tips_more_info () {
  return '';
}

/******************************************************************************
 * Custom functions
 ******************************************************************************/
// returns the widget
function deuxcode_fivestar_rating_widget($node,$numstars) {
  // If user is allowed to vote, show vote form
  if (user_access('rate content')){
    $widget = ''.fivestar_widget_form($node).'';
  }
  // otherwise show static widget
  else {
    $result = db_result(db_query('SELECT v.value FROM {votingapi_cache} v WHERE v.content_id = %d AND v.function = "average" LIMIT 1', $node->nid));
    $widget = ''.theme('fivestar_static', $result, 5).'';
  }
  return $widget;
}
// returns avarage voting value for a node, or false
function deuxcode_fivestar_rating_widget_result($node, $numstars = 5, $type = 'average') {
  if ($node){
    $value = db_result(db_query('SELECT v.value FROM {votingapi_cache} v WHERE v.content_id = %d AND v.function = "average" LIMIT 1', $node->nid));
  }
  if ($value != 0){
    $value = round($value/100*$numstars,1);
  } else {
    $value = FALSE;
  }
  return $value;
}
/******************************************************************************
 * Forward form changes
 ******************************************************************************/

if ( $_GET['q'] == 'forward' ) {


}

/**
 * Format the links for event calendars
 *
 * @param vars
 *   An array of email variables
 */
function phptemplate_forward_email($vars) {

  $style = "<style>
      <!--
        html, body {margin:0; padding:0; background-color:#fff;}
        #container {margin:0 auto; width:670px; font:normal 10pt arial,helvetica,sans-serif;}
        #header {width:670px; margin:0; text-align:center;}
        #body {width:630px; margin:0; padding:5px 20px; text-align:left; background-color:#fff;}
        #footer {width:670px; height:35px; margin:0; padding:5px 0 0 0; font-size:9pt; text-align:center; color:#fff;}
        .ad_footer, .message, .article  {font-size:10pt; padding:0;}
        .frm_title, .frm_txt {font-size:12pt;}
        .frm_txt {padding-bottom:15px;}
        .links {font-size:10pt; font-style:italic;}
        .article_title {font-size:12pt;}
        .dyn_content { padding-top:10px;}
      -->
    </style>";

  $output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    '.$style.'
    <base href="'.url('',array('absolute' => TRUE)).'" />
  </head>
  <body>
    <div id="container">
      <div id="header">'.l('<img src="'.$vars['forward_header_image'].'" border="0" alt="'.$vars['site_name'].'">', '',array('absolute' => TRUE,'html' => TRUE)).'</div>
      <div id="body">
        <div class="frm_txt">'.$vars['forward_message'].'</div>';
        if ($vars['message']) { $output .= '
        <div class="frm_title">'.t('Message from Sender').':</div>
        <div class="frm_txt"><p><b>'.$vars['message'].'</b></p></div>';}
        $output .= '
        <div><b>'.l($vars['content']->title, 'forward/emailref',array('absolute' => TRUE, 'query' => 'path='.$vars['path'])).'</b>';
        if (!empty($vars['content']->type) && (theme_get_setting('toggle_node_info_'.$vars['content']->type))) { $output .= '
        <br /><i>'.t('by %author', array('%author' => $vars['content']->name)).'</i>';}
        $output .= '
        </div>
        <div class="article">'.$vars['content']->teaser.'</div>
        <div class="links">'.l(t('Click here to read more on our site'), 'forward/emailref',array('absolute' => TRUE, 'query' => 'path='.$vars['path'])).'</div>
        <div class="dyn_content"><br />'.$vars['dynamic_content'].'</div>
        <div class="ad_footer"><br />'.$vars['forward_ad_footer'].'<br></div>
      </div>
      <div id="footer">'.$vars['forward_footer'].'</div>
    </div>
  </body>
</html>';

  return $output;
}

/**
 * Format the links for event calendars
 *
 * @param vars
 *   An array of email variables
 */
function phptemplate_forward_postcard($vars) {

  $style = "<style>
     <!--
        html, body {margin:0; padding:0; background-color:#fff;}
        #container {margin:0 auto; width:670px; font:normal 10pt arial,helvetica,sans-serif;}
        #header {width:670px; margin:0; text-align:center;}
        #body {width:630px; margin:0; padding:5px 20px; text-align:left; background-color:#fff;}
        #footer {width:670px; height:35px; margin:0; padding:5px 0 0 0; font-size:9pt; text-align:center; color:#fff;}
        .ad_footer, .message, .article  {font-size:10pt; padding:0;}
        .frm_title, .frm_txt {font-size:12pt;}
        .frm_txt {padding-bottom:15px;}
        .links {font-size:10pt; font-style:italic;}
        .article_title {font-size:12pt;}
        .dyn_content { padding-top:10px;}
      -->
    </style>";

  $output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    '.$style.'
    <base href="'.url('',array('absolute' => TRUE)).'" />
  </head>
  <body>
    <div id="container">
      <div id="header">'.l('<img src="'.$vars['forward_header_image'].'" border="0" alt="'.$vars['site_name'].'">', '',array('absolute' => TRUE,'html' => TRUE)).'</div>
      <div id="body">
        <div class="frm_txt">'.$vars['forward_message'].'</div>';
        if ($vars['message']) { $output .= '
        <div class="frm_title">'.t('Message from Sender').':</div>
        <div class="frm_txt">'.$vars['message'].'</div>';}
        $output .= '
        <div class="links">'.l(t('Click here to visit our site'), 'forward/emailref/'.$vars['nid'],array('absolute' => TRUE)).'</div>
        <div class="dyn_content"><br />'.$vars['dynamic_content'].'</div>
        <div class="ad_footer"><br />'.$vars['forward_ad_footer'].'<br></div>
      </div>
      <div id="footer">'.$vars['forward_footer'].'</div>
    </div>
  </body>
</html>';

  return $output;
}






?>
