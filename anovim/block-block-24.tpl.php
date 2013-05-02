<?php
// set variables
$date = mktime(0, 0, 0, date('n')-1, 1, date('Y'));

$yyyymm = date('Y',$date) . date('m',$date); 

?>
<div class="blog_tille">
  <h3 title="<?php print date('F',$date); ?> Articles"><?php print date('F',$date); ?> Articles</h3>
  <p class="artp1"><a href="<?php print url('articles/'.date('Y',$date).date('m',$date)) ?>">VIEW ALL <?php print strtoupper(date('F',$date)); ?> ARTICLES</a></p>
  <div class="clr"></div>
</div>
<?php
// sql
$sql = "
  SELECT n.nid
    FROM {node} n
    WHERE n.type = 'articles'
      AND n.status <> 0
      AND DATE_FORMAT(FROM_UNIXTIME(n.created), '%Y%m') = '%s'
";
$results = db_query($sql, $yyyymm);

$result = array();
while($result = db_fetch_array($results)){
  $node = node_load($result['nid']);
  print theme('node', $node);
}

?>
