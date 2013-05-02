<?php
#  print '<pre>';
#  print_r( $node);
#  print '</pre>';
?>
<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

<?php // print $picture ?>

<?php if ($page == 0): ?>
  <h2 class="title"><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
<?php endif; ?>

  <?php if ($submitted): ?>
    <h4 class="submitted"><?php print t('Posted: ') . format_date($node->created); ?></h4>
  <?php endif; ?>

  <div class="content clear-block">
    <?php print $content ?>
    <?php
    // Show only on teaser view and if link exist
    if ($teaser && $node->readmore) {
      print '<p><a href="'. $node_url .'" title="'.t('Read full article').'" class="readmore">'.t('Read more').'</a></p>';
    }
    ?>
  </div>

<!--
   <div class="clear-block">
    <div class="meta">
    <?php if ($taxonomy): ?>
      <div class="terms"><?php print $terms ?></div>
    <?php endif;?>
    </div>

    <?php if ($links): ?>
      <div class="links"><?php print $links; ?></div>
    <?php endif; ?>
  </div>
-->
</div>
<!-- <div class="spacer"></div> -->
