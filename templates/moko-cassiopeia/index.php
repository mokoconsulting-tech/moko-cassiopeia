<?php

/**
 * @package     Joomla.Site
 * @subpackage  Templates.Moko-Cassiopeia
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app   = Factory::getApplication();
$input = $app->getInput();
$wa    = $this->getWebAssetManager();
$params_ColorName = $this->params->get('colorName', 'colors_standard');
$params_GoogleTagManager = $this->params->get('googletagmanager', false);
$params_GoogleTagManagerID = $this->params->get('googletagmanagerid', null);
$params_custom_head_start = $this->params->get('custom_head_start', null);
$params_custom_head_end = $this->params->get('custom_head_end', null);
$params_leftIcon  = htmlspecialchars(
    $this->params->get('drawerLeftIcon', 'fa-solid fa-chevron-left'),
    ENT_COMPAT, 'UTF-8'
);
$params_rightIcon = htmlspecialchars(
    $this->params->get('drawerRightIcon', 'fa-solid fa-chevron-right'),
    ENT_COMPAT, 'UTF-8'
);

if ($params_GoogleTagManager && $params_GoogleTagManagerID) {
    ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?php echo $params_GoogleTagManagerID;?>');</script>
    <!-- End Google Tag Manager -->
    <?php
}

// Browsers support SVG favicons
$this->addHeadLink(HTMLHelper::_('image', '../media/templates/site/moko-cassiopeia/images/favicon/favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', '../media/templates/site/moko-cassiopeia/images/favicon/favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', '../media/templates/site/moko-cassiopeia/images/favicon/favicon.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#fff']);
?>
<link rel="apple-touch-icon" sizes="180x180" href="../media/templates/site/moko-cassiopeia/images/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../media/templates/site/moko-cassiopeia/images/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../media/templates/site/moko-cassiopeia/images/favicon/favicon-16x16.png">
<link rel="manifest" href="../media/templates/site/moko-cassiopeia/images/favicon/site.webmanifest">

<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<?php
// Add Bootstrap 5 Support
HTMLHelper::_('bootstrap.framework');
HTMLHelper::_('bootstrap.loadCss', true);
HTMLHelper::_('bootstrap.alert');
HTMLHelper::_('bootstrap.button');
HTMLHelper::_('bootstrap.carousel');
HTMLHelper::_('bootstrap.collapse');
HTMLHelper::_('bootstrap.dropdown');
HTMLHelper::_('bootstrap.modal');
HTMLHelper::_('bootstrap.offcanvas');
HTMLHelper::_('bootstrap.popover');
HTMLHelper::_('bootstrap.scrollspy');
HTMLHelper::_('bootstrap.tab');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('bootstrap.toast');

// Detecting Active Variables
$option   = $input->getCmd('option', '');
$view     = $input->getCmd('view', '');
$layout   = $input->getCmd('layout', '');
$task     = $input->getCmd('task', '');
$itemid   = $input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu     = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Template path
$templatePath = 'media/templates/site/moko-cassiopeia';

// Color Theme
$assetColorName  = 'theme.' . $params_ColorName;
$wa->registerAndUseStyle($assetColorName, $templatePath . '/css/global/' . $params_ColorName . '.css');

// Use a font scheme if set in the template style options
$params_FontScheme = $this->params->get('useFontScheme', false);
$fontStyles       = '';

if ($params_FontScheme) {
    if (stripos($params_FontScheme, 'https://') === 0) {
        $this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', ['crossorigin' => 'anonymous']);
        $this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', ['crossorigin' => 'anonymous']);
        $this->getPreloadManager()->preload($params_FontScheme, ['as' => 'style', 'crossorigin' => 'anonymous']);
        $wa->registerAndUseStyle('fontscheme.current', $params_FontScheme, [], ['media' => 'print', 'rel' => 'lazy-stylesheet', 'onload' => 'this.media=\'all\'', 'crossorigin' => 'anonymous']);

        if (preg_match_all('/family=([^?:]*):/i', $params_FontScheme, $matches) > 0) {
            $fontStyles = '--moko-cassiopeia-font-family-body: "' . str_replace('+', ' ', $matches[1][0]) . '", sans-serif;\n';
            $fontStyles .= '--moko-cassiopeia-font-family-headings: "' . str_replace('+', ' ', isset($matches[1][1]) ? $matches[1][1] : $matches[1][0]) . '", sans-serif;\n';
            $fontStyles .= '--moko-cassiopeia-font-weight-normal: 400;\n';
            $fontStyles .= '--moko-cassiopeia-font-weight-headings: 700;';
        }
    } else {
        $wa->registerAndUseStyle('fontscheme.current', $params_FontScheme, ['version' => 'auto'], ['media' => 'print', 'rel' => 'lazy-stylesheet', 'onload' => 'this.media=\'all\'']);
        $this->getPreloadManager()->preload($wa->getAsset('style', 'fontscheme.current')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
    }
}

// Enable assets
$wa->usePreset('template.MOKO-CASSIOPEIA.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
    ->useStyle('template.active.language')
    ->useStyle('template.user')
    ->useScript('template.user')
    ->addInlineStyle(":root {\n        --hue: 214;\n        --template-bg-light: #f0f4fb;\n        --template-text-dark: #495057;\n        --template-text-light: #ffffff;\n        --template-link-color: #2a69b8;\n        --template-special-color: #001B4C;\n        $fontStyles\n    }");

// Override 'template.active' asset for correct dependency
$wa->registerStyle('template.active', '', [], [], ['template.MOKO-CASSIOPEIA.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

// Logo file or site title
if ($this->params->get('logoFile')) {
    $logo = HTMLHelper::_('image', Uri::root(false) . htmlspecialchars($this->params->get('logoFile'), ENT_QUOTES), $sitename, ['loading' => 'eager', 'decoding' => 'async'], false, 0);
} elseif ($this->params->get('siteTitle')) {
    $logo = '<span title="' . $sitename . '">' . htmlspecialchars($this->params->get('siteTitle'), ENT_COMPAT, 'UTF-8') . '</span>';
} else {
    $logo = HTMLHelper::_('image', 'full_logo.png', $sitename, ['class' => 'logo d-inline-block', 'loading' => 'eager', 'decoding' => 'async'], true, 0);
}

$hasClass = '';
if ($this->countModules('sidebar-left', true))  { $hasClass .= ' has-sidebar-left'; }
if ($this->countModules('sidebar-right', true)) { $hasClass .= ' has-sidebar-right'; }
if ($this->countModules('drawer-left', true))  { $hasClass .= ' has-drawer-left'; }
if ($this->countModules('drawer-right', true))  { $hasClass .= ' has-drawer-right'; }

$params_DrawerIconLeft  = $this->params->get('drawerIconLeft',  'fas fa-chevron-right');
$params_DrawerIconRight = $this->params->get('drawerIconRight', 'fas fa-chevron-left');

// Container
$wrapper = $this->params->get('fluidContainer') ? 'wrapper-fluid' : 'wrapper-static';

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');
$stickyHeader = $this->params->get('stickyHeader') ? 'position-sticky sticky-top' : '';

if ($this->params->get('fA6KitCode')) {
    $fa6Kit = "https://kit.fontawesome.com/" . $this->params->get('fA6KitCode') . ".js";
    JHtml::_('script', $fa6Kit, ['crossorigin' => 'anonymous']);
} else {
    $wa->getAsset('style', 'fontawesome')->setAttribute('rel', 'lazy-stylesheet');
}
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <?php if (trim($params_custom_head_start)) : ?><?php echo $params_custom_head_start; ?><?php endif; ?>
    <jdoc:include type="head" />
    <?php if (trim($params_custom_head_end)) : ?><?php echo $params_custom_head_end; ?><?php endif; ?>
</head>
<body class="site <?php echo $option . ' ' . $wrapper . ' view-' . $view . ($layout ? ' layout-' . $layout : ' no-layout') . ($task ? ' task-' . $task : ' no-task') . ($itemid ? ' itemid-' . $itemid : '') . ($pageclass ? ' ' . $pageclass : '') . $hasClass . ($this->direction == 'rtl' ? ' rtl' : ''); ?>">
<?php if ($params_GoogleTagManager && $params_GoogleTagManagerID) : ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $params_GoogleTagManagerID; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
<?php endif; ?>

<header class="header container-header full-width<?php echo $stickyHeader ? ' ' . $stickyHeader : ''; ?>">

    <?php if ($this->countModules('topbar')) : ?>
        <div class="container-topbar">
            <jdoc:include type="modules" name="topbar" style="none" />
        </div>
    <?php endif; ?>

    <div class="header-top">
        <?php if ($this->countModules('below-top')) : ?>
            <div class="grid-child container-below-top">
                <jdoc:include type="modules" name="below-top" style="none" />
            </div>
        <?php endif; ?>

        <?php if ($this->params->get('brand', 1)) : ?>
            <div class="grid-child">
                <div class="navbar-brand">
                    <a class="brand-logo" href="<?php echo $this->baseurl; ?>/">
                        <?php echo $logo; ?>
                    </a>
                    <?php if ($this->params->get('siteDescription')) : ?>
                        <div class="site-description"><?php echo htmlspecialchars($this->params->get('siteDescription')); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('below-logo')) : ?>
            <div class="grid container-below-logo">
                <jdoc:include type="modules" name="below-logo" style="none" />
            </div>
        <?php endif; ?>
    </div>

    <!-- Drawer Toggle Buttons -->
<?php if ($this->countModules('drawer-left')) : ?>
  <button class="drawer-toggle-left btn btn-outline-secondary me-2"
          type="button"
          data-bs-toggle="offcanvas"
          data-bs-target="#drawer-left"
          aria-controls="drawer-left">
    <span class="<?php echo $params_leftIcon; ?>"></span>
  </button>
<?php endif; ?>

<?php if ($this->countModules('drawer-right')) : ?>
  <button class="drawer-toggle-right btn btn-outline-secondary"
          type="button"
          data-bs-toggle="offcanvas"
          data-bs-target="#drawer-right"
          aria-controls="drawer-right">
    <span class="<?php echo $params_rightIcon; ?>"></span>
  </button>
<?php endif; ?>

    <?php if ($this->countModules('menu', true) || $this->countModules('search', true)) : ?>
        <div class="grid-child container-nav">
            <?php if ($this->countModules('menu', true)) : ?>
                <jdoc:include type="modules" name="menu" style="none" />
            <?php endif; ?>
            <?php if ($this->countModules('search', true)) : ?>
                <div class="container-search">
                    <jdoc:include type="modules" name="search" style="none" />
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</header>

<div class="site-grid">
    <?php if ($this->countModules('banner', true)) : ?>
        <div class="container-banner full-width">
            <jdoc:include type="modules" name="banner" style="none" />
        </div>
    <?php endif; ?>

    <?php if ($this->countModules('top-a', true)) : ?>
        <div class="grid-child container-top-a">
            <jdoc:include type="modules" name="top-a" style="card" />
        </div>
    <?php endif; ?>

    <?php if ($this->countModules('top-b', true)) : ?>
        <div class="grid-child container-top-b">
            <jdoc:include type="modules" name="top-b" style="card" />
        </div>
    <?php endif; ?>

    <?php if ($this->countModules('sidebar-left', true)) : ?>
        <div class="grid-child container-sidebar-left">
            <jdoc:include type="modules" name="sidebar-left" style="card" />
        </div>
    <?php endif; ?>

    <div class="grid-child container-component">
        <jdoc:include type="modules" name="breadcrumbs" style="none" />
        <jdoc:include type="modules" name="main-top" style="card" />
        <jdoc:include type="message" />
        <main>
            <jdoc:include type="component" />
        </main>
        <jdoc:include type="modules" name="main-bottom" style="card" />
    </div>

    <?php if ($this->countModules('sidebar-right', true)) : ?>
        <div class="grid-child container-sidebar-right">
            <jdoc:include type="modules" name="sidebar-right" style="card" />
        </div>
    <?php endif; ?>

    <?php if ($this->countModules('bottom-a', true)) : ?>
        <div class="grid-child container-bottom-a">
            <jdoc:include type="modules" name="bottom-a" style="card" />
        </div>
    <?php endif; ?>

    <?php if ($this->countModules('bottom-b', true)) : ?>
        <div class="grid-child container-bottom-b">
            <jdoc:include type="modules" name="bottom-b" style="card" />
        </div>
    <?php endif; ?>
</div>

<footer class="container-footer footer full-width">
    <?php if ($this->countModules('footer-menu', true)) : ?>
        <div class="grid-child footer-menu">
            <jdoc:include type="modules" name="footer-menu" />
        </div>
    <?php endif; ?>
    <?php if ($this->countModules('footer', true)) : ?>
        <div class="grid-child">
            <jdoc:include type="modules" name="footer" style="none" />
        </div>
    <?php endif; ?>
</footer>

<?php if ($this->params->get('backTop') == 1) : ?>
    <a href="#top" id="back-top" class="back-to-top-link" aria-label="<?php echo Text::_('TPL_MOKO-CASSIOPEIA_BACKTOTOP'); ?>">
        <span class="icon-arrow-up icon-fw" aria-hidden="true"></span>
    </a>
<?php endif; ?>

<?php if ($this->countModules('drawer-left', true)) : ?>
<!-- Left Offcanvas Drawer -->
<aside class="offcanvas offcanvas-start" tabindex="-1" id="drawer-left">
  <div class="offcanvas-header">

    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="<?php echo Text::_('JLIB_HTML_BEHAVIOR_CLOSE'); ?>"></button>
  </div>
  <div class="offcanvas-body">
    <jdoc:include type="modules" name="drawer-left" style="none" />
  </div>
</aside>
<?php endif; ?>

<?php if ($this->countModules('drawer-right', true)) : ?>
<!-- Right Offcanvas Drawer -->
<aside class="offcanvas offcanvas-end" tabindex="-1" id="drawer-right">
  <div class="offcanvas-header">

    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="<?php echo Text::_('JLIB_HTML_BEHAVIOR_CLOSE'); ?>"></button>
  </div>
  <div class="offcanvas-body">
    <jdoc:include type="modules" name="drawer-right" style="none" />
  </div>
</aside>
<?php endif; ?>

<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
