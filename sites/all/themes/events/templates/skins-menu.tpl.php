<?php

$skins = events_get_predefined_param('skins', array("default" => t("Default Style")));
$current_skin = theme_get_setting('skin');
if (isset($_COOKIE['weebpal_skin'])) {
  $current_skin = $_COOKIE['weebpal_skin'];
}

$backgrounds = events_get_predefined_param('backgrounds', array("bg-default" => t("Default")));
$current_background = theme_get_setting('background');
if (isset($_COOKIE['weebpal_background'])) {
  $current_background = $_COOKIE['weebpal_background'];
}

$layouts = events_get_predefined_param('layout', array("layout-default" => t("Default Layout")));

$languages = language_list('enabled');
$languages = $languages[1];
$direction = array(
  'rtl' => array('name' => '', 'class' => ''),
  'ltr' => array('name' => '', 'class' => '')
);
global $language;
foreach ($languages as $name => $info) {
  if ($info->direction && $direction['rtl']['name'] == '') {
    $direction['rtl']['name'] = $name;
  }
  else if (!$info->direction && $direction['ltr']['name'] == '') {
    $direction['ltr']['name'] = $name;
  }
  $direction[$language->dir]['class'] = 'active';
  if ($direction['rtl']['name'] != '' && $direction['ltr']['name'] != '') {
    break;
  }
}

$str = array();
$str[] = '<div id="change_skin_menu_wrapper" class="change-skin-menu-wrapper wrapper">';
$str[] = '<div class="container">';
$str[] = '<a id="change-skin" class="change-skin" href="javascript:void(0)"><i class="fa fa-cog"></i></a>';
//Change skin color
$str[] = '<div class="skin-color">';
$str[] = '<strong>Skin</strong>';
$str[] = '<ul class="change-skin-menu">';

foreach ($skins as $skin => $skin_title) {
  $li_class = ($skin == $current_skin ? ($skin . ' active') : $skin);
  $str[] = '<li class="' . $li_class . '"><a href="#change-skin/' . $skin . '" class="change-skin-button color-' . $skin . '">' . $skin_title . '</a></li>';
}
$str[] = '</ul></div>';
//Change Layout
$str[] = '<div class="layout">';
$str[] = '<strong>Layout Boxed</strong>';
$str[] = '<label class="switch-btn">';
foreach ($layouts as $layout => $layout_title) {
  $label_class = ($layout == $current_layout ? ' active' : '');
  $label_title = ($layout == 'layout-boxed' ? 'Yes' : 'No');
  $str[] = '<span id="' . $layout . '" class="btn btn-default change-layout-button ' . $label_class . '">' . $label_title . '</span>';
}
$str[] = '</label></div>';
//Change Background
if ($current_layout == 'layout-boxed') {
  $str[] = '<div class="background">';
}
else {
  $str[] = '<div class="background hidden">';
}
$str[] = '<strong>Background</strong>';
$str[] = '<ul class="change-background">';

foreach ($backgrounds as $background => $background_title) {
  $li_class = ($background == $current_background ? ($background . ' active') : $background);
  $str[] = '<li class="' . $li_class . '"><a href="#change-background/' . $background . '" class="change-background-button">' . $background_title . '</a></li>';
}

$str[] = '</ul></div>';

//Change Direction
$str[] = '<div class="direction">';
$str[] = '<strong>Direction</strong>';
$str[] = '<ul class="change-direction">';
$str[] = '<li class="' . $direction['ltr']['class'] . '"><a href="' . base_path() . $direction['ltr']['name'] . '" class="change-direction-button">LTR</a></li>';
$str[] = '<li class="' . $direction['rtl']['class'] . '"><a href="' . base_path() . $direction['rtl']['name'] . '" class="change-direction-button">RTL</a></li>';
$str[] = '</ul></div>';

$str[] = '</div></div>';
$result = implode("", $str);

drupal_add_js('
  (function ($) {
    Drupal.behaviors.skinMenuAction = {
      attach: function (context) {
        jQuery(".change-layout-button").on("click", function() {
          var layout_class = $(this).attr("id");
          var layout_width = ' . $layout_width . ';
          jQuery.cookie("weebpal_layout", layout_class, {path: "' . base_path() . '"});
          jQuery("#page").removeAttr("style");
          jQuery("#page").removeClass("boxed");
          jQuery(".background").addClass("hidden");
          var current_background = jQuery.cookie("weebpal_background");
          jQuery("body").removeClass(current_background);
          if (layout_class != "layout-default") {
            jQuery("#page").css("max-width", layout_width);
            jQuery("#page").css("margin", "0 auto");
            jQuery("#page").addClass("boxed");
            jQuery(".background").removeClass("hidden");
            jQuery("body").addClass(current_background);
          }
          $(".change-layout-button").removeClass("active");
          if(!$(this).hasClass("active"))
              $(this).addClass("active");
          return false;
        });
      }
    }
  })(jQuery);
', 'inline');

print $result;