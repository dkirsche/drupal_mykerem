<div id="block-<?php print $block->module .'-'. $block->delta; ?>" class="block block-<?php print $block->module ?>">

<?php if (!empty($block->subject)): ?>
  <h2 class="title"><?php print $block->subject ?></h2>  
<?php endif;?>

  <div class="content">
    <div class="small_box">
    <ul>
    <?php
    //+ COUNT(DISTINCT(c.cid)))
    // get users with post count
    $result = db_query("
    SELECT (COUNT(DISTINCT(n.nid))) as count, u.name, u.uid
    FROM {users} u
    LEFT JOIN {node} n ON u.uid = n.uid
    LEFT JOIN {comments} c ON c.uid = u.uid
    WHERE u.uid != 0
      AND n.status = 1
      AND n.type = 'blog'
      AND (n.uid = u.uid OR c.uid = u.uid)
    GROUP BY n.uid
    ORDER BY u.name ASC
    LIMIT 20");

    //$list = array();
    
    while($row = db_fetch_array($result)){
      print "<li>". l(check_plain($row['name']), 'blog/'. $row['uid']) ." (". $row['count'] .")</li>";
    }
    ?>
    </ul>
    </div>
  </div>
</div>
