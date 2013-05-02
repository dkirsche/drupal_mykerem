<?php
// Get and set variables
global $base_path;
$uid = arg(1);

if (arg(1) == 'archive') {
  $uid = arg(3);
}

if (!is_numeric($uid)) {
  $uid = 0;
}
drupal_add_js('
$(document).ready(function(){
    $("#ajax_maindate").hide();
    $("h4.month1 a").click(function () {
      $("#ajax_maindate").toggle("fast");
      $("#ajaxpop-calendar-data").load("/calendar.php", {value: '.date('Y').', user: '.$uid.'}, function(){});
    });
    $("#ajax_datebox1 .close").click(function () {
      $("#ajax_maindate").toggle("fast");
    });
    $("#ajax_pop_nav_prev").click(function () {
      $("#ajaxpop-calendar-data").load("/calendar.php", {value: $("#ajaxcal-year-nav .prev").text(), user: '.$uid.'}, function(){
        $("#calendar-headline").text($("#ajaxcal-year-nav .curr").text())        
      });
    });
    $("#ajax_pop_nav_next").click(function () {
      $("#ajaxpop-calendar-data").load("/calendar.php", {value: $("#ajaxcal-year-nav .next").text(), user: '.$uid.'}, function(){
        $("#calendar-headline").text($("#ajaxcal-year-nav .curr").text())
      });
    });
  });'
,'inline');
// Create dates
$prev_month = mktime(0, 0, 0, date('n')-1, 1, date('Y'));
?>
<div id="block-<?php print $block->module .'-'. $block->delta; ?>" class="block block-<?php print $block->module ?>">
<?php if (!empty($block->subject)): ?>
  <h2 class="title"><?php print $block->subject ?></h2>  
<?php endif;?>
  <div class="content">
    <h3 class="text_5">View previous blogs by month</h3>
    <h4 class="month">» <a href="<?php print url('blogs/archive/'.date('Ym',$prev_month).'/'.$uid) ?>">Previous Month (<?php print date('F',$prev_month) ?>)</a></h4>
    <div class="clr"/></div>
    <h4 class="month1">» <a href="javascript:void(0);">Choose Month</a></h4>
    <img alt="calnder" src="<?php print url($directory.'/images/calnder.jpg') ?>" class="clander"/>
    <div class="clr"/></div>
  </div>
  <div id="ajax_maindate">
    <span id="init-cal" style="display:none;visibility:hidden"><?php print date('Y') ?></span>
  		<div id="ajax_date">
  			<div id="ajax_datebox">
				<div id="ajax_datetop">
					<a id="ajax_pop_nav_prev" href="javascript:void(0);"><img src="<?php print $base_path.$directory ?>/images/top_icon.jpg" alt="top" border="0" /></a>
					<h1 id="calendar-headline"><?php print date('Y') ?></h1>
					<a id="ajax_pop_nav_next" href="javascript:void(0);"><img src="<?php print $base_path.$directory ?>/images/top_icon1.jpg" alt="top" border="0" /></a>
					<div class="clr"></div>
			  </div>
			  <div id="ajax_datebox1">
			  		<div style="float:right">
              <h4 class="close1">&times;</h4>
			  		 <h4 class="close"><a href="javascript:void(0);">Close</a></h4>
			  		</div>
					<div class="clr"></div>
					<div class="spacer5"></div>
		      <div id="ajaxpop-calendar-data">  
            loading...
          </div>
					<div class="clr"></div>
			  </div>
			
  		</div>
  	</div>
  </div>
</div>
