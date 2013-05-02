<?php
drupal_add_js($directory.'/js/jquery.example.min.js');
drupal_add_js('
$(document).ready(function(){
  $("#ajax_search").hide();

  $("#frm-search-blog-advanced").click(function () {
    $("#ajax_search").toggle("fast");
  });

  $(".ajax_can").click(function () {
    $("#ajax_search").toggle("fast");
  });
  $("input#blog-keys").example("Enter search terms");
});', 'inline');
?>
<div id="block-<?php print $block->module .'-'. $block->delta; ?>" class="block block-<?php print $block->module ?>">
  <?php
  if ($block->subject) {
    ?>
    <h2 class="title"><?php print $block->subject ?></h2>
    <?php
  }
  ?>

  <div class="content">
    <label for="edit-search-block-form-1"><strong class="text_5">Search through our blogs:</strong></label>
    <form action="/blogs/search" method="get" id="search-form" class="search-form">
      <div class="form-item">
        <input type="text" class="input-text" value="" size="25" name="content" id="blog-keys" />
        <p class="ptext2"><a id="frm-search-blog-advanced" href="javascript:void(0);">Advanced Search</a></p>
        <input type="submit" value="Search" name="op" title="Search" alt="Search" class="form-submit" />
      </div>
    </form>
  </div>

  <div id="ajax_search">
    <div id="ajax_searchmid">
      <div id="ajax_searchtop">
        <h1>Advanced Search</h1>
      </div>

      <?php print '<div id="ajax_con">' . drupal_get_form('ss81_asearch_blog_form') . '</div>'; ?>
    </div>

    <img class="ajax_img" src="<?php print url(path_to_theme().'/images/ajax_bottom.jpg'); ?>" alt="" />
  </div>
</div>
