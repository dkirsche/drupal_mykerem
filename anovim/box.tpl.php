<?php
if (arg(0) == 'node' && is_numeric(arg(1))) {
  $nid = arg(1);
  $node = node_load(array('nid' => $nid));
  $type = $node->type;
}
?>

<div class="box <?php print $type; ?>">
  <?php if ($type == 'blog' && $title =='Post new comment'):?>
    <h2>Add Your Own Comment</h2>
  <?php elseif($type == 'articles' && $title=='Post new comment'): ?>
    <h2>Add Your Own Comment</h2>  
  <?php else: ?>
    <h2><?php print $title ?></h2>
  <?php endif;?>
  <div class="content"><?php print $content ?></div>
</div>
