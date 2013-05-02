<?php
// Set default variables
$form_name_value = 'Username';
$form_pass_value = 'Password';

function loginform() {
  $loginform = user_login_block();
  $loginform['name']['#title'] = "";
  $loginform['name']['#default_value'] = $form_name_value;
  $loginform['pass']['#title'] = "";
  $loginform['pass']['#default_value'] = $form_pass_value;
  $loginform['links']['#value'] = '<p class="ptext2"><a href="'.url('user/password').'">Forgot Password?</a>&nbsp;&nbsp;|&nbsp;&nbsp;  <a href="'.url('user/register').'">New User</a></p>';
//print '<!-- '.print_r($loginform) .' -->';
  return $loginform;
}
print drupal_get_form('loginform');

/**
 * Add prefilled values to input field
 */
drupal_add_js('sites/all/themes/anovim/js/jquery.example.min.js');
drupal_add_js('
$(document).ready(function(){
  $("input#edit-name-1").example("'.$form_name_value.'");
  $("input#edit-pass-1").example("'.$form_pass_value.'");
});
','inline');

?>
