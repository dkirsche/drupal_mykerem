<div id="block-<?php print $block->module .'-'. $block->delta; ?>" class="block block-<?php print $block->module ?>">

<?php if (!empty($block->subject)): ?>
  <h2 class="title"><?php print $block->subject ?></h2>  
<?php endif;?>

  <div class="content">
    <div class="small_box">
    <ul>
    <?php
    // get users with post count
    $subsql = "
      SELECT COUNT(f.feed_nid)
      FROM {feedapi_node_item_feed} f
      WHERE f.feed_nid = n.nid
    ";
    
    $result = db_query("
      SELECT n.title as name,n.nid,(".$subsql.") as count
      FROM {node} n
      WHERE n.status != 0
        AND n.type = '%s'
      ORDER BY n.title ASC
    ",'feed');

    while($row = db_fetch_array($result)){
      print "<li>". l(check_plain($row['name']), 'feed-item/'. $row['nid']) ." (". $row['count'] .")</li>";
    }
    ?>
    </ul>
    </div>
  </div>
</div>
