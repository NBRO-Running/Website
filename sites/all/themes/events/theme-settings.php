<?php
/**
 * @file
 * Theme setting callbacks for the events theme.
 */
include_once(drupal_get_path('theme', 'events') . '/common.inc');

function events_reset_settings() {
  global $theme_key;
  variable_del('theme_' . $theme_key . '_settings');
  variable_del('theme_settings');
  $cache = &drupal_static('theme_get_setting', array());
  $cache[$theme_key] = NULL;
}

function events_form_system_theme_settings_alter(&$form, $form_state) {
  if (theme_get_setting('events_use_default_settings')) {
    events_reset_settings();
  }
  $form['#attached']['js'][] = array(
    'data' => drupal_get_path('theme', 'events') . '/js/weebpal.js',
    'type' => 'file',
  );
  $form['events']['events_version'] = array(
    '#type' => 'hidden',
    '#default' => '1.0',
  );
  events_settings_layout_tab($form);
  events_feedback_form($form);
  $form['#submit'][] = 'events_form_system_theme_settings_submit';
}

function events_settings_layout_tab(&$form) {
  global $theme_key;
  $skins = events_get_predefined_param('skins', array('' => t("Default skin")));
  $backgrounds = events_get_predefined_param('backgrounds', array('bg-default' => t("Default")));
  $layout = events_get_predefined_param('layout', array('layout-default' => t("Default Layout")));

  $form['events']['settings'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#title' => t('Settings'),
    '#weight' => 0,
  );

  if (count($skins) > 1) {
    $form['events']['settings']['configs'] = array(
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#title' => t('Configs'),
      '#weight' => 0,
    );
    $form['events']['settings']['configs']['skin'] = array(
      '#type' => 'select',
      '#title' => t('Skin'),
      '#default_value' => theme_get_setting('skin'),
      '#options' => $skins,
    );
  }

  $form['events']['settings']['configs']['background'] = array(
    '#type' => 'select',
    '#title' => t('Background'),
    '#default_value' => theme_get_setting('background'),
    '#options' => $backgrounds,
    '#weight' => 1,
  );

  $form['events']['settings']['configs']['layout'] = array(
    '#type' => 'select',
    '#title' => t('Layout'),
    '#default_value' => theme_get_setting('layout'),
    '#options' => $layout,
    '#weight' => -2,
  );
  $default_layout_width = (theme_get_setting('layout_width') == '') ? '1400' : theme_get_setting('layout_width');
  $form['events']['settings']['configs']['layout_width'] = array(
    '#type' => 'textfield',
    '#title' => t('Layout Width(px)'),
    '#default_value' => $default_layout_width,
    '#size' => 15,
    '#require' => TRUE,
    '#weight' => -1,
    '#states' => array(
      'visible' => array(
        'select[name="layout"]' => array(
          'value' => 'layout-boxed',
        ),
      ),
    ),
  );

  $form['theme_settings']['toggle_logo']['#default_value'] = theme_get_setting('toggle_logo');
  $form['theme_settings']['toggle_name']['#default_value'] = theme_get_setting('toggle_name');
  $form['theme_settings']['toggle_slogan']['#default_value'] = theme_get_setting('toggle_slogan');
  $form['theme_settings']['toggle_node_user_picture']['#default_value'] = theme_get_setting('toggle_node_user_picture');
  $form['theme_settings']['toggle_comment_user_picture']['#default_value'] = theme_get_setting('toggle_comment_user_picture');
  $form['theme_settings']['toggle_comment_user_verification']['#default_value'] = theme_get_setting('toggle_comment_user_verification');
  $form['theme_settings']['toggle_favicon']['#default_value'] = theme_get_setting('toggle_favicon');
  $form['theme_settings']['toggle_secondary_menu']['#default_value'] = theme_get_setting('toggle_secondary_menu');
  $form['theme_settings']['show_skins_menu'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show Skins Menu'),
    '#default_value' => theme_get_setting('show_skins_menu'),
  );
  $form['theme_settings']['loading_page'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use loading'),
    '#default_value' => theme_get_setting('loading_page'),
  );

  $form['logo']['default_logo']['#default_value'] = theme_get_setting('default_logo');
  $form['logo']['settings']['logo_path']['#default_value'] = theme_get_setting('logo_path');
  $form['favicon']['default_favicon']['#default_value'] = theme_get_setting('default_favicon');
  $form['favicon']['settings']['favicon_path']['#default_value'] = theme_get_setting('favicon_path');
  $form['theme_settings']['#collapsible'] = TRUE;
  $form['theme_settings']['#collapsed'] = FALSE;
  $form['logo']['#collapsible'] = TRUE;
  $form['logo']['#collapsed'] = FALSE;
  $form['favicon']['#collapsible'] = TRUE;
  $form['favicon']['#collapsed'] = FALSE;
  $form['events']['settings']['theme_settings'] = $form['theme_settings'];
  $form['events']['settings']['logo'] = $form['logo'];
  $form['events']['settings']['favicon'] = $form['favicon'];

  unset($form['theme_settings']);
  unset($form['logo']);
  unset($form['favicon']);

  $form['events']['events_use_default_settings'] = array(
    '#type' => 'hidden',
    '#default_value' => 0,
  );
  $form['actions']['events_use_default_settings_wrapper'] = array(
    '#markup' => '<input type="submit" value="' . t('Reset theme settings') . '" class="form-submit form-reset" onclick="return Drupal.Light.onClickResetDefaultSettings();" style="float: right;">',
  );
}

function events_feedback_form(&$form) {
  $form['events']['about_events'] = array(
    '#type' => 'fieldset',
    '#title' => t('Feedback Form'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#weight' => 40,
  );

  $form['events']['about_events']['about_events_wrapper'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('about-events-wrapper')),
  );

  $form['events']['about_events']['about_events_wrapper']['about_events_content'] = array(
    '#markup' => '<iframe width="100%" height="650" scrolling="no" class="nucleus_frame" frameborder="0" src="http://www.weebpal.com/static/feedback/"></iframe>',
  );
}

function events_form_system_theme_settings_submit($form, &$form_state) {
  if(isset($form_state['input']['skin']) && $form_state['input']['skin'] != $form_state['complete form']['events']['settings']['configs']['skin']['#default_value']) {
    setcookie('weebpal_skin', $form_state['input']['skin'], time() + 100000, base_path());
  }
  if (isset($form_state['input']['background']) && $form_state['input']['background'] !== $form_state['complete form']['events']['settings']['configs']['background']['#default_value']) {
    setcookie('weebpal_background', $form_state['input']['background'], time() + 100000, base_path());
  }
  if (isset($form_state['input']['layout']) && $form_state['input']['layout'] !== $form_state['complete form']['events']['settings']['configs']['layout']['#default_value']) {
    setcookie('weebpal_layout', $form_state['input']['layout'], time() + 100000, base_path());
  }
}
