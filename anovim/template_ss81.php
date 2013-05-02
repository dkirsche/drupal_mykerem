<?php

if ( $_GET['q'] == 'user/register' || preg_match('/^user\/\d+\/edit\/General Information$/', $_GET['q']) ) {

  // include the script to the page

  drupal_add_js( drupal_get_path('module', 'ss81_anovim') . '/jquery.selectboxes.js' );



  // generate list of countries and fill the Countries select list

  $countries = location_get_iso3166_list();



  $tmp = array();
  $tmp[] = '"" : ""'; // set default to nothing (by dxc)
  foreach( $countries as $k => $v ) {

    $tmp[] = '"' . $k . '" : "' . $v . '"';

  }



  $script = '

  $(document).ready(function() {

    var country = $("#edit-profile-country").val();

    var state = $("#edit-profile-state").val();



    $("#edit-profile-country-wrapper").html("<label for=\"edit-profile-country\">Country: <span class=\"form-required\" title=\"This field is required.\">*</span></label><select id=\"edit-profile-country\" class=\"form-select required\" name=\"profile_country\"></select>");

    $("#edit-profile-state-wrapper").html("<label for=\"edit-profile-state\">State or Region: <span class=\"form-required\" title=\"This field is required.\">*</span></label><select id=\"edit-profile-state\" class=\"form-select required\" name=\"profile_state\"></select>");



    var myOptions = {' . implode(",\r\n", $tmp) . '};



    $("#edit-profile-country").removeOption(/./);

    $("#edit-profile-country").addOption(myOptions, false);

    $("#edit-profile-country").selectOptions(country, true);



    $("#edit-profile-country").change(function(){

      $("#edit-profile-state").removeOption(/./);

      $.getJSON("/ss81/states-list/" + $("#edit-profile-country").selectedValues()[0], function(data){

        if ( data["error"] == "empty" ) {

          $("#edit-profile-state-wrapper").html("<label for=\"edit-profile-state\">State or Region: <span class=\"form-required\" title=\"This field is required.\">*</span></label><input type=\"text\" class=\"form-text required\" value=\"\" size=\"60\" id=\"edit-profile-state\" name=\"profile_state\" maxlength=\"255\"/>");

          $("#edit-profile-state").val(state);

        }

        else {

          $("#edit-profile-state-wrapper").html("<label for=\"edit-profile-state\">State or Region: <span class=\"form-required\" title=\"This field is required.\">*</span></label><select id=\"edit-profile-state\" class=\"form-select required\" name=\"profile_state\"></select>");

          $("#edit-profile-state").addOption(data, false);



          if ( state != "" ) {

            $("#edit-profile-state").selectOptions(state, true);

            state = "";

          }

        }

      });

    });



    $("#edit-profile-country").change();

  });' . "\r\n\r\n";



  drupal_add_js($script, 'inline');

}

