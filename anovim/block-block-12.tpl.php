<?php
/**
 * Override Search Articles block
 */
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
  $("input#article-keys").example("Enter search terms");
});
','inline');

?>
<div id="block-<?php print $block->module .'-'. $block->delta; ?>" class="block block-<?php print $block->module ?>">
<?php if ($block->subject): ?>
  <h2 class="title"><?php print $block->subject ?></h2>
<?php endif;?>

  <div class="content">
    <label for="edit-search-block-form-1"><strong class="text_5">Search through our articles</strong></label>
    <form action="/search/node" method="post" id="search-form" class="search-form">
      <div class="form-item">
        <input type="text" id="article-keys" class="input-text" value="" size="25" name="keys" />
      </div>
      <input type="submit" value="Search" name="op" title="Search Articles" alt="Search Articles" class="form-submit" />
      <input type="hidden" value="<?php print drupal_get_token('search_form'); ?>" name="form_token" />
      <input type="hidden" value="search_form" id="edit-search-form" name="form_id" />
      <input type="hidden" name="type[articles]" id="edit-type-articles" value="blog" />
    </form>
  </div>
</div>



