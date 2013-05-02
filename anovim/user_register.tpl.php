<?php
//print_r($form);

$form['account']['name']['#title'] = '';
$form['account']['name']['#description'] = '';
$form['account']['name']['#attributes'] = array('class' => 'info_input1');

$form['account']['mail']['#title'] = '';
$form['account']['mail']['#description'] = '';
$form['account']['mail']['#attributes'] = array('class' => 'info_input1');

$form['account']['pass']['pass1']['#title'] = '';
$form['account']['pass']['pass2']['#title'] = '';
$form['account']['pass']['#description'] = '';
$form['account']['pass']['pass1']['#attributes'] = array('class' => 'info_input1');
$form['account']['pass']['pass2']['#attributes'] = array('class' => 'info_input1');

$form['submit']['#value'] = 'Submit';
$form['submit']['#attributes'] = array('class' => 'info_submit');

$form['General Information']['profile_first_name']['#title'] = '';
$form['General Information']['profile_first_name']['#attributes'] = array('class' => 'info_input1');

$form['General Information']['profile_last_name']['#title'] = '';
$form['General Information']['profile_last_name']['#attributes'] = array('class' => 'info_input1');

$form['General Information']['profile_country']['#title'] = '';
$form['General Information']['profile_country']['#attributes'] = array('class' => 'info_input1');

$form['General Information']['profile_state']['#title'] = '';
$form['General Information']['profile_state']['#attributes'] = array('class' => 'info_input1');

$form['General Information']['profile_city']['#title'] = '';
$form['General Information']['profile_city']['#attributes'] = array('class' => 'info_input1');
?>
<div class="main_blogmidtop">
  <div class="blog_content">
    <div class="generl_box">
      <h1>General Information:</h1>
      <label class="info_label1">First Name:</label>
      <label class="info_label1">Email:</label>
      <div class="clr"></div>
      <?php print drupal_render($form['General Information']['profile_first_name']); ?>
      <?php print drupal_render($form['account']['mail']); ?>
      <div class="clr"></div>
      <label class="info_label1">Last Name:</label>
      <label class="info_label1">Location:</label>
      <div class="clr"></div>
      <?php print drupal_render($form['General Information']['profile_last_name']); ?>
      <?php print drupal_render($form['General Information']['profile_country']); ?>
      <div class="clr"></div>
      <label class="info_label1">State:</label>
      <label class="info_label1">City:</label>
      <div class="clr"></div>
      <?php print drupal_render($form['General Information']['profile_state']); ?>
      <?php print drupal_render($form['General Information']['profile_city']); ?>
      <div class="clr"></div>
    </div>
  </div>
</div>

<div class="main_blogmid">
  <div class="blog_content">
    <div class="generl_box">
      <label class="info_label1">Username:</label>
      <div class="clr"></div>
      <?php print drupal_render($form['account']['name']); ?>
      <div class="clr"></div>
      <label class="info_label1">Password:</label>
      <label class="info_label3">Password Confirmation:</label>
      <div class="clr"></div>
      <?php print drupal_render($form['account']['pass']); ?>
      <div class="clr"></div>
      <div class="spacer"></div>
      <div class="info_submitbox">
      <?php print drupal_render($form['submit']); ?>
      </div>
    </div>

    <div class="spacer11"></div>
  </div>
</div>
<div class="main_blogbottom"></div>
<?php
print drupal_render($form);
?>
