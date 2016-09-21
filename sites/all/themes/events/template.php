<?php
include_once(drupal_get_path('theme', 'events') . '/common.inc');

function events_theme() {
  $items = array();
  $items['render_panel'] = array(
    "variables" => array(
      'page' => array(),
      'panels_list' => array(),
      'panel_regions_width' => array(),
      ),
    'preprocess functions' => array(
      'events_preprocess_render_panel'
      ),
    'template' => 'panel',
    'path' => drupal_get_path('theme', 'events') . '/templates',
    );

  $items['banner_output'] = array(
    'variables' => array(
      'url'             => NULL,
      'text'            => NULL,
      'link'            => NULL,
      'display_setting' => NULL,
      'display_errors'  => NULL,
      ),
    'path'      => drupal_get_path('theme', 'events') . '/templates',
    'template'  => 'banner_output_template',
    );

  return $items;
}

function events_process_html(&$vars) {
  $current_skin = theme_get_setting('skin');
  if (isset($_COOKIE['weebpal_skin'])) {
    $current_skin = $_COOKIE['weebpal_skin'];
  }
  if (!empty($current_skin) && $current_skin != 'default') {
    $vars['classes'] .= " skin-$current_skin";
  }

  $current_background = theme_get_setting('background');
  if (isset($_COOKIE['weebpal_background'])) {
    $current_background = $_COOKIE['weebpal_background'];
  }
  if (!empty($current_background)) {
    $vars['classes'] .= ' ' . $current_background;
  }

  $loading_page = theme_get_setting('loading_page');
  $vars['loading_page'] = FALSE;
  if ($loading_page) {
    $vars['classes'] .= ' loading';
    $vars['loading_page'] = TRUE;
  }
}

function events_preprocess_page(&$vars) {
  global $theme_key;
  $layout_width = (theme_get_setting('layout_width') == '') ? theme_get_setting('layout_width_default') : theme_get_setting('layout_width');
  $vars['page_css'] = '';
  $vars['regions_width'] = events_regions_width($vars['page']);
  $panel_regions = events_panel_regions();
  if (count($panel_regions)) {
    foreach ($panel_regions as $panel_name => $panels_list) {
      $panel_markup = theme("render_panel", array(
        'page' => $vars['page'],
        'panels_list' => $panels_list,
        'regions_width' => $vars['regions_width'],
        ));
      $panel_markup = trim($panel_markup);
      $vars['page'][$panel_name] = empty($panel_markup) ? FALSE : array('content' => array('#markup' => $panel_markup));
    }
  }

  if (isset($vars['node']) && $vars['node']->type != 'page' && !in_array('page__node__delete', $vars['theme_hook_suggestions'])) {
    $result = db_select('node_type', NULL, array('fetch' => PDO::FETCH_ASSOC))
    ->fields('node_type', array('name'))
    ->condition('type', $vars['node']->type)
    ->execute()->fetchField();
    $vars['title'] = $result . ' Detail';
  }

  $current_skin = theme_get_setting('skin');
  if (isset($_COOKIE['weebpal_skin'])) {
    $current_skin = $_COOKIE['weebpal_skin'];
  }

  $vars['page']['show_skins_menu'] = $show_skins_menu = theme_get_setting('show_skins_menu');
  if($show_skins_menu) {
    $current_layout = theme_get_setting('layout');
    if (isset($_COOKIE['weebpal_layout'])) {
      $current_layout = $_COOKIE['weebpal_layout'];
    }

    if ($current_layout == 'layout-boxed') {
      $vars['page_css'] = 'style="max-width:' . $layout_width . 'px;margin: 0 auto;" class="boxed"';
    }
    $data = array(
      'layout_width' => $layout_width,
      'current_layout' => $current_layout);
    $skins_menu = theme_render_template(drupal_get_path('theme', 'events') . '/templates/skins-menu.tpl.php', $data);
    $vars['page']['show_skins_menu'] = $skins_menu;
  }

  $vars['page']['weebpal_skin_classes'] = !empty($current_skin) ? ($current_skin . "-skin") : "";
  if (!empty($current_skin) && $current_skin != 'default' && theme_get_setting("default_logo") && theme_get_setting("toggle_logo")) {
    $vars['logo'] = file_create_url(drupal_get_path('theme', $theme_key)) . "/css/colors/" . $current_skin . "/images/logo.png";
  }

  global $theme_key;
  $skin = theme_get_setting('skin');
  if (isset($_COOKIE['weebpal_skin'])) {
    $skin = $_COOKIE['weebpal_skin'] == 'default' ? '' : $_COOKIE['weebpal_skin'];
  }
  if (!empty($skin) && file_exists(drupal_get_path('theme', $theme_key) . "/css/colors/" . $skin . "/style.css")) {
    $css = drupal_add_css(drupal_get_path('theme', $theme_key) . "/css/colors/" . $skin . "/style.css", array(
      'group' => CSS_THEME,
      ));
  }

  // drupal_add_css(drupal_get_path('theme', $theme_key) . "/css/screens/tablet.css", array(
  //   'media' => 'only screen and (min-width:992px) and (max-width:1199px)',
  //   'group' => CSS_THEME,
  //   ));
  // drupal_add_css(drupal_get_path('theme', $theme_key) . "/css/screens/common.css", array(
  //   'media' => 'only screen and (max-width:991px)',
  //   'group' => CSS_THEME,
  //   ));
  // drupal_add_css(drupal_get_path('theme', $theme_key) . "/css/screens/vertical_tablet.css", array(
  //   'media' => 'only screen and (min-width:768px) and (max-width:991px)',
  //   'group' => CSS_THEME,
  //   ));
  // drupal_add_css(drupal_get_path('theme', $theme_key) . "/css/screens/mobile.css", array(
  //   'media' => 'only screen and (max-width:767px)',
  //   'group' => CSS_THEME,
  //   ));

  $css = drupal_add_css('https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800', array(
    'group' => CSS_SYSTEM,
    'weight' => -5
    ));

   //Add user Facebook sesstion ID and User ID
   global $user;
   if(simple_fb_connect_initialize() && simple_fb_connect_get_session()){
	   $user = user_load($user->uid);
	   drupal_add_js(array('events' => array('facebook_auth' => simple_fb_connect_get_session()->getToken(), 'facebook_userid' => $user->field_facebook_userid[LANGUAGE_NONE][0]['value'])), 'setting');
   }else {
	   drupal_add_js(array('events' => array('facebook_auth' => false)), 'setting');
   }
}

function events_preprocess_node(&$vars) {

  $vars['events_media_field'] = false;
  foreach($vars['content'] as $key => $field) {
    if (isset($field['#field_type']) && isset($field['#weight'])) {
      if ($field['#field_type'] == 'image' || $field['#field_type'] == 'video_embed_field' || $field['#field_type'] == 'youtube') {
        drupal_add_js('https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js');
	drupal_add_js('/sites/all/themes/events/js/attend.js', array('scope' => 'footer'));
        $vars['events_media_field'] = drupal_render($field);
        $vars['classes_array'][] = 'events-media-first';
        unset($vars['content'][$key]);
        break;
      }
    }
  }

  $vars['page'] = ($vars['type'] == 'page') ? TRUE : FALSE;
  $vars['created_date'] = date('M d, Y', $vars['created']);

  if(isset($vars['content']['links']['comment'])) {
    $vars['comment_links'] = $vars['content']['links']['comment'];
    unset($vars['content']['links']['comment']);
  }
}

function events_preprocess_render_panel(&$variables) {
  $page = $variables['page'];
  $panels_list = $variables['panels_list'];
  $regions_width = $variables['regions_width'];
  $variables = array();
  $variables['page'] = array();
  $variables['panel_width'] = $regions_width;
  $variables['panel_classes'] = array();
  $variables['panels_list'] = $panels_list;
  $is_empty = TRUE;
  $panel_keys = array_keys($panels_list);

  foreach ($panels_list as $panel) {
    $variables['page'][$panel] = $page[$panel];
    $panel_width = $regions_width[$panel];
    if (render($page[$panel])) {
      $is_empty = FALSE;
    }
    $classes = array("panel-column");
    $classes[] = "col-md-$panel_width";
    $classes[] = str_replace("_", "-", $panel);
    $variables['panel_classes'][$panel] = implode(" ", $classes);
  }
  $variables['empty_panel'] = $is_empty;
}

function events_css_alter(&$css) {

}

function events_preprocess_views_view_fields(&$vars) {
  $view = $vars['view'];
  foreach ($vars['fields'] as $id => $field) {
    if(isset($field->handler->field_info) && $field->handler->field_info['type'] === 'image') {
      $prefix = $field->wrapper_prefix;
      if(strpos($prefix, "views-field ") !== false) {
        $parts = explode("views-field ", $prefix);
        $type = str_replace("_", "-", $field->handler->field_info['type']);
        $prefix = implode("views-field views-field-type-" . $type . " ", $parts);
      }
      $vars['fields'][$id]->wrapper_prefix = $prefix;
    }
  }
}

function events_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb">' . implode('<span>»</span>', $breadcrumb) . '</div>';
    return $output;
  }
}

function events_form_alter(&$form, $form_state, $form_id) {
  if (function_exists('commerce_form_callback') && commerce_form_callback($form_id, $form_state) == "commerce_cart_add_to_cart_form") {
    $form['submit']['#attributes']['title'] = $form['submit']['#attributes']['value'] = t('Buy');
  }
  if($form_id == 'contact_site_form' || $form_id == 'contact_personal_form') {
    $form['name']['#prefix'] = '<div class="contact-form-group">';
    $form['subject']['#suffix'] = '</div>';
  }
}

function events_js_alter(&$js) {
  if (!isset($js['https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js'])) {
    drupal_add_js('https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js', array(
      'group' => JS_LIBRARY,
      'weight' => -11,
      ));
  }
}

function events_status_messages($vars) {
  $display = $vars['display'];
  $output = '';

  $all_messages = drupal_get_messages($display);

  $status_type = array(
    'status' => 'success',
    'error' => 'danger',
    'warning' => 'warning'
    );

  foreach ($all_messages as $type => $messages) {
    $output .= "<div class=\"alert alert-$status_type[$type] alert-dismissible\" role=\"alert\">";
    $output .= '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
    if (count($messages) > 1) {
      $output .= " <ul>";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul>";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div>\n";
  }

  return $output;
}

function events_preprocess_views_view(&$vars) {

  $view = &$vars['view'];
  // Make sure it's the correct view
  if ($view->name == 'event') {
        drupal_add_js('https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js');
	drupal_add_js('/sites/all/themes/events/js/attend.js', array('scope' => 'footer'));
  }
}

