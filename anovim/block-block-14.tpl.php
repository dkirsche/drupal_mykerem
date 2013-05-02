<div id="block-<?php print $block->module .'-'. $block->delta; ?>" class="block block-<?php print $block->module ?>">

<?php if (!empty($block->subject)): ?>
  <h2 class="title"><?php print $block->subject ?></h2>  
<?php endif;?>

  <div class="content">
    <?php print drupal_get_form('forumjump_dropdown_form'); ?>    
  </div>
</div>
<?php
/**
 * Returns a jump form
 */ 
function forumjump_dropdown_form() {
  // Set values
  $vid = 1;
  $formname = "forumjump";
  $name_length = 19;
  // Get data
  $vocabulary = taxonomy_get_tree($vid);
  $containers = variable_get('forum_containers', array());
  // Initialise  array
  $options[] = t('Jump to...');  
  //Populate options array with value / name
  foreach ( $vocabulary as $forum ) { 
    $container_id = array_search($forum->tid, $containers);
    if (is_numeric($container_id)){ // it's a container
      $options[url('forum/'.$forum->tid)] = ''. substr($forum->name,0,$name_length);
    } else {
      $options[url('forum/'.$forum->tid)] = '› '.substr($forum->name,0,$name_length-2);
    }
  }
  // Build dropdown
  $form['forum'] = array(
    '#type' => 'select',
    '#name' => $formname,
    '#id' => $formname,
    '#title' => '',
    '#default_value' => '',
    '#options' => $options,
    '#description' => '',
    '#multiple' => $multiple = FALSE,
    '#required' => $required = FALSE,
    '#attributes' => array('onChange' => "top.location.href=document.getElementById('$formname').options[document.getElementById('$formname').selectedIndex].value"),
  );
  return $form;
}
?>
