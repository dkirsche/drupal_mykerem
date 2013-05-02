<?php
// Get user info
$uid = arg(1);	// user id
$u = user_load(array("uid" => $uid));

// if user has a country value,
if ($u->profile_country != '') {
  $profile_country = location_country_name($u->profile_country);
  $profile_state = location_province_name($u->profile_country,$u->profile_state);
} // end if profile country


drupal_set_title('View Profile');

/**
 * Handle access to profile
 */
// set default value to view profile
$view_profile = TRUE;

if ($u->profile_show_to_registered == 1 && $user->uid == 0) {
  $view_profile = FALSE;
  $message = t('This user profile is set to private.');
}
/*
// if the profile is set to public
if (($u->profile_is_public == 0) && ($u->profile_show_to_registered == 0)) {
  $view_profile = FALSE;
  $message = t('This user profile is set to private.');
}
// if show to registered is set
elseif (($u->profile_show_to_registered == 1) && ($user->uid == 0) && ($u->profile_is_public == 0)) {
  $view_profile = FALSE;
  $message = t('You have to be logged in to view this user profile.');
}
*/
// Always show profile to owner and admin

if ($user->uid == $uid || $user->uid == 1) {
  $view_profile = TRUE;
}

// show profile
if ($view_profile) {
  // Get statistics (only when view profile)
  $blog_count = db_result(db_query("SELECT COUNT(*) FROM {node} n WHERE n.type = 'blog' AND n.status = 1 AND n.uid = %d", $uid));
  $article_count = db_result(db_query("SELECT COUNT(*) FROM {node} n WHERE n.type = 'article' AND n.status = 1 AND n.uid = %d", $uid));
  // review content type do not exist yet...
  // $review_count = db_result(db_query("SELECT COUNT(*) FROM {node} n WHERE n.type = 'review' AND n.status = 1 AND n.uid = %d", $uid));
  $review_count = 0;
  $comment_count = db_result(db_query("SELECT COUNT(*) FROM {comments} c WHERE c.status = 0 AND c.uid = %d", $uid));
  ?>
  <div class="main_blogmidtop">
    <div class="blog_content">
      <div class="generl_box">
        <?php  if($account->picture) { print theme('user_picture', $account); }?>
        <div class="user_box">
          <h4>Username: <span class="normal"><?php print $u->name ?></span></h4>
          <?php if (!$u->profile_display_name): ?>
          <h4>Name: <span class="normal"><?php print check_plain($u->profile_first_name .' '. $u->profile_last_name) ?></span></h4>
          <?php endif; ?>
          <?php if($profile_country) { ?>
          	<h4>Location: <span class="normal"><?php print $profile_country.', '.$profile_state; ?></span></h4>
          <?php }; ?>
          <h4>My Favorite Wine: <span class="normal"><?php print check_plain($u->profile_favourite_wine) ?></span></h4>
          <!-- <h4>Role:</h4> -->
        </div>

        <div class="clr"></div>
      </div>
    </div>
  </div>
  <div class="main_blogmid">
    <div class="blog_content">
      <div class="spacer3"></div>

      <h1><a href="#">About Me:</a></h1>
      <p><?php print nl2br(check_plain($u->profile_about_me)) ?></p>
      <div class="spacer"></div>

      <h1><a href="#">Wine Styles I like:</a></h1>
      <p><?php print nl2br(check_plain($u->profile_wine_styles_i_like)) ?></p>
      <div class="spacer"></div>

      <div id="mid_nav">
        <div id="mid_navbox">
          <?php
          // code for tabs
          drupal_add_js("
          $(document).ready(function() {
            $('#tbl-profile-info-wines1,#tbl-profile-info-wines2').hide();
            $('li.start a').click(function(){
              $('#tbl-profile-info-stats').show();
              $('li.start a').addClass('active');
              $('#tbl-profile-info-wines1,#tbl-profile-info-wines2').hide();
              $('li.start1 a,li.start2 a').removeClass('active');
            });

            $('li.start1 a').click(function(){
              $('#tbl-profile-info-wines1').show();
              $('li.start1 a').addClass('active');
              $('#tbl-profile-info-stats,#tbl-profile-info-wines2').hide();
              $('li.start a,li.start2 a').removeClass('active');
            });

            $('li.start2 a').click(function(){
              $('#tbl-profile-info-wines2').show();
              $('li.start2 a').addClass('active');
              $('#tbl-profile-info-stats,#tbl-profile-info-wines1').hide();
              $('li.start a,li.start1 a').removeClass('active');
            })
          });","inline");
          ?>

          <div id="nav_box">
            <ul>
              <li class="start"><a class="active" href="javascript:void(0);">STATS</a></li>
              <li class="start1"><a href="javascript:void(0);">WINES I LIKE</a></li>
              <li class="start2"><a href="javascript:void(0);">WINES I DON'T LIKE</a></li>
            </ul>
          </div>

          <table id="tbl-profile-info-stats" class="tbl-profile-info">
          <tr class="odd">
            <th>Blog Entries:</th>
            <td><?php print $blog_count ?></td>
          </tr>

          <tr class="even">
            <th>Articles:</th>
            <td><?php print $article_count ?></td>
          </tr>

          <tr class="odd">
            <th>Comments:</th>
            <td><?php print $comment_count ?></td>
          </tr>

          <tr class="even">
            <th>Reviews:</th>
            <td><?php print $review_count ?></td>
          </tr>
          </table>

          <table id="tbl-profile-info-wines1" class="tbl-profile-info">
          <?php
          $wil = explode(chr(10),$u->profile_wines_i_like);
          $i = 1;
          foreach ($wil as $key=>$value) {
            ($i % 2 ? $class = 'odd' : $class = 'even');
            print '<tr class="'.$class.'"><th style="width:20px">'.$i.')</th><td>'.$value.'</td></tr>';
            $i++;
          }
          ?>
          </table>

          <table id="tbl-profile-info-wines2" class="tbl-profile-info">
          <?php
          $widl = explode(chr(10),$u->profile_wines_i_dont_like);
          $i = 1;
          foreach ($widl as $key=>$value) {
            ($i % 2 ? $class = 'odd' : $class = 'even');
            print '<tr class="'.$class.'"><th style="width:20px">'.$i.')</th><td>'.$value.'</td></tr>';
            $i++;
          }
          ?>
          </table>
        </div>
      </div><!-- /#mid_nav -->

      <div class="spacer"></div>

      <?php
      /**
       * list of blog user posts
       * views output
       */
      print views_embed_view("user_blog_posts", "default", $uid );
      ?>
    </div>

    <div class="spacer"></div>
  </div>
  <?php
} else {
  ?>
  <div class="main_blogmidtop">
    <div class="blog_content">
      <?php drupal_set_message($message,'warning'); ?>
      <p style="height:400px;">&nbsp;</p>
    </div>
  </div>
  <?php
} // view_profile
