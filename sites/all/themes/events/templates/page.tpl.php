<?php
/**
 * @file
 * Events's theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
 *
 * @ingroup themeable
 */
?>
<div id="page" <?php print $page_css ?>>
  <?php if(isset($page['show_skins_menu']) && $page['show_skins_menu']):?>
    <?php print $page['show_skins_menu'];?>
  <?php endif;?>

  <?php if($headline = render($page['headline'])): ?>
    <section id="headline" class="headline">
      <div class="container">
        <?php print $headline; ?>
      </div>
    </section>
  <?php endif;?>

  <?php if(($slideshow = render($page['slideshow']))): ?>
    <section id="slideshow" class="slideshow section">
      <div class="container-fluid">
        <?php print $slideshow;?>
      </div>
    </section>
  <?php endif;?>

  <header id="header" class="header section">
    <div class="container">
      <?php if ($logo): ?>
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
          <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
        </a>
      <?php endif; ?>

      <?php if ($site_name || $site_slogan): ?>
        <div id="name-and-slogan">
          <?php if ($site_name): ?>
            <?php if ($title): ?>
              <div id="site-name"><strong>
                <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
              </strong></div>
            <?php else: /* Use h1 when the content title is empty */ ?>
              <h1 id="site-name">
                <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
              </h1>
            <?php endif; ?>
          <?php endif; ?>

          <?php if ($site_slogan): ?>
            <div id="site-slogan"><?php print $site_slogan; ?></div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php print render($page['header']); ?>

      <?php if($main_menu = render($page['main_menu'])): ?>
        <a id="menu-toggle" class="navbar-toggle" href="#menu-toggle">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <nav id="main-menu-inner">
          <?php print $main_menu; ?>
        </nav>
      <?php endif;?>
    </div>
    <div id="events-search" class="search-wrapper">
      <div class="container"></div>
    </div>
  </header>


  <?php if ($title): ?>
    <section id="title-wrapper" class="section">
      <div class="container">
        <div class="page-title">
          <?php print render($title_prefix); ?>
          <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
          <?php print render($title_suffix); ?>
        </div>
      </div>
      <?php if($banner = render($page['banner'])): ?>
        <div class="banner">
          <?php print render($page['banner']); ?>
        </div>
      <?php endif;?>
    </section>
  <?php endif;?>

  <section id="main" class="main section">
    <div class="container">
      <?php if ($messages): ?>
        <?php print $messages; ?>
      <?php endif;?>
      <div class="row">
        <div class="col-md-<?php print $regions_width['content']?>">
          <div id="content" class="column">
            <?php if ($page['highlighted']): ?><div id="highlighted"><?php print render($page['highlighted']); ?></div><?php endif; ?>
            <a id="main-content"></a>
            <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>
            <?php print render($page['help']); ?>
            <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
            <?php print render($page['content']); ?>
          </div>
        </div>
        <?php if ($regions_width['sidebar_first']): ?>
          <aside id="sidebar-first" class="sidebar col-md-<?php print $regions_width['sidebar_first']?>">
            <div class="section">
              <?php print render($page['sidebar_first']); ?>
            </div>
          </aside>
        <?php endif; ?>
        <?php if ($page['sidebar_second']): ?>
          <aside id="sidebar-second" class="sidebar col-md-<?php print $regions_width['sidebar_second']?>">
            <div class="section">
              <?php print render($page['sidebar_second']); ?>
            </div>
          </aside>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <?php if($panel_first = render($page['panel_first'])): ?>
    <section id="panel-first" class="section">
      <div class="container">
        <?php print $panel_first;?>
      </div>
    </section>
  <?php endif; ?>

  <?php if($panel_second = render($page['panel_second'])): ?>
    <section id="panel-second" class="section">
      <div class="container">
        <?php print $panel_second;?>
      </div>
    </section>
  <?php endif; ?>

  <?php if($panel_third = render($page['panel_third'])): ?>
    <section id="panel-third" class="section">
      <div class="container">
        <?php print $panel_third;?>
      </div>
    </section>
  <?php endif; ?>

  <?php if ($panel_fourth = render($page['panel_fourth'])): ?>
    <section id="panel-fourth" class="section">
      <div class="container">
        <?php print $panel_fourth; ?>
      </div>
    </section>
  <?php endif; ?>

  <?php if($panel_fifth = render($page['panel_fifth'])): ?>
    <section id="panel-fifth" class="section">
      <div class="container">
        <?php print $panel_fifth;?>
      </div>
    </section>
  <?php endif; ?>

  <?php if($panel_sixth = render($page['panel_sixth'])): ?>
    <section id="panel-sixth" class="section">
      <div class="container">
        <?php print $panel_sixth;?>
      </div>
    </section>
  <?php endif; ?>

  <?php if($panel_seventh = render($page['panel_seventh'])): ?>
    <section id="panel-seventh" class="section">
      <div class="container-fluid">
        <?php print $panel_seventh;?>
      </div>
    </section>
  <?php endif; ?>

  <?php if($panel_footer_first = render($page['panel_footer_first'])): ?>
    <section id="panel-footer-first" class="section">
      <div class="container">
        <?php print $panel_footer_first;?>
      </div>
    </section>
  <?php endif; ?>

  <?php if($panel_footer_second = render($page['panel_footer_second'])): ?>
    <section id="panel-footer-second" class="section">
      <div class="container">
        <?php print $panel_footer_second;?>
      </div>
    </section>
  <?php endif; ?>

  <?php if($footer = render($page['footer'])): ?>
    <footer id="footer" class="section text-center">
      <div class="container">
        <?php print $footer; ?>
        <!--?php print $feed_icons; ?-->
      </div>
    </footer>
  <?php endif;?>
  <a title="<?php print t('Back to Top')?>" class="btn-btt" href="#Top"></a>
</div>
