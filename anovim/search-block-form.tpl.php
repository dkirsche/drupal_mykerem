<?php
/**
 * Available variables:
 * - $search_form: The complete search form ready for print.
 * - $search: Array of keyed search elements. Can be used to print each form
 *   element separately.
 *
 * Default keys within $search:
 * - $search['search_block_form']: Text input area wrapped in a div.
 * - $search['submit']: Form submit button.
 * - $search['hidden']: Hidden form elements. Used to validate forms when submitted.
 */

/**
 * Add prefilled values to input field
 */ 
drupal_add_js($directory.'/js/jquery.example.min.js');
drupal_add_js('
  $(document).ready(function(){
    $("input#edit-search-block-form-1").example("Enter search terms");
  });
','inline');

?>
<label for="edit-search-block-form-1"><strong>Search this site:</strong></label>
<input type="text" maxlength="128" name="search_block_form" id="edit-search-block-form-1" size="15" value="" title="Enter the terms you wish to search for." class="form-text" />

<p class="ptext2"><a href="<?php print url('search') ?>" id="sidebar-block-advanced-search-toggle">Advanced Search ›</a></p>
<?php
//print $search['search_block_form'];
  print $search['submit'];
  print $search['hidden'];
?>
