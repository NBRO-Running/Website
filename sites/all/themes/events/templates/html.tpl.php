<!DOCTYPE html>
<html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">
<head>
  <?php print $head; ?>
  <title><?php print (drupal_is_front_page()) ? 'NBRO - Runners of Copenhagen' : $head_title; ?></title>
  <!-- META FOR IOS & HANDHELD -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="HandheldFriendly" content="true" />
  <meta name="apple-touch-fullscreen" content="YES" />
  <meta name="apple-itunes-app" content="app-id=1084299725"/>
  <!-- //META FOR IOS & HANDHELD -->
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
<canvas class="c"></canvas>
  <?php if($loading_page): ?>
    <div class="loader">Loading...</div>
  <?php endif; ?>
  <div id="skip-link">
    <a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
  </div>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
</body>
</html>
