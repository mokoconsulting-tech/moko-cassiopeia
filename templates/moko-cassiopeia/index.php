<?php
/* =========================================================================
 * Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>
 *
 * This file is part of a Moko Consulting project.
 *
 * SPDX-License-Identifier: GPL-3.0-or-later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://www.gnu.org/licenses/ .
 * =========================================================================
 * FILE INFORMATION
 * DEFGROUP: Joomla
 * INGROUP: Moko-Cassiopeia
 * PATH: templates/moko-cassiopeia/index.php
 * VERSION: 02.00
 * BRIEF: Main template index file for Moko-Cassiopeia rendering site layout
 * =========================================================================
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

// Template params
$params_ColorName          = (string) $this->params->get('colorName', 'colors_standard'); // colors_standard|colors_alternative|colors_custom
$params_googletagmanager   = $this->params->get('googletagmanager', false);
$params_googletagmanagerid = $this->params->get('googletagmanagerid', null);
$params_googleanalytics    = $this->params->get('googleanalytics', false);
$params_googleanalyticsid  = $this->params->get('googleanalyticsid', null);
$params_custom_head_start  = $this->params->get('custom_head_start', null);
$params_custom_head_end    = $this->params->get('custom_head_end', null);
$params_leftIcon           = htmlspecialchars($this->params->get('drawerLeftIcon', 'fa-solid fa-chevron-left'), ENT_COMPAT, 'UTF-8');
$params_rightIcon          = htmlspecialchars($this->params->get('drawerRightIcon', 'fa-solid fa-chevron-right'), ENT_COMPAT, 'UTF-8');

// Bootstrap behaviors (assets handled via WAM)
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
$option    = $input->getCmd('option', '');
$view      = $input->getCmd('view', '');
$layout    = $input->getCmd('layout', '');
$task      = $input->getCmd('task', '');
$itemid    = $input->getCmd('Itemid', '');
$sitenameR = $app->get('sitename'); // raw for title composition
$sitename  = htmlspecialchars($sitenameR, ENT_QUOTES, 'UTF-8');
$menu      = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Respect “Site Name in Page Titles” (0:none, 1:before, 2:after)
$mode      = (int) $app->get('sitename_pagetitles', 0);
$pageTitle = trim($this->getTitle());
$final     = $pageTitle !== ''
	? ($mode === 1 ? $sitenameR . ' - ' . $pageTitle
	   : ($mode === 2 ? $pageTitle . ' - ' . $sitenameR : $pageTitle))
	: $sitenameR;
$this->setTitle($final);

// Template/Media path
$templatePath = 'media/templates/site/moko-cassiopeia';

// ===========================
// Web Asset Manager (WAM) — matches your joomla.asset.json
// ===========================

// Core template CSS
$wa->useStyle('template.base');   // css/template.css
$wa->useStyle('template.user');   // css/user.css

// Optional vendor CSS
$wa->useStyle('vendor.gable');
$wa->useStyle('vendor.bootstrap-toc');

// Color theme (light + optional dark)
$colorKey  = strtolower(preg_replace('/[^a-z0-9_.-]/i', '', $params_ColorName));
$lightKey  = 'template.light.' . $colorKey;
$darkKey   = 'template.dark.' . $colorKey;

try {
	$wa->useStyle($lightKey);
} catch (\Throwable $e) {
	$wa->registerAndUseStyle('template.light.dynamic', $templatePath . '/css/global/colors/light/' . $params_ColorName . '.css');
}
try {
	$wa->useStyle($darkKey);
} catch (\Throwable $e) {
	// optional; omit if not present
}

// Scripts
$wa->useScript('template.js');
$wa->useScript('theme-init.js');
$wa->useScript('darkmode-toggle.js');
$wa->useScript('vendor.bootstrap-toc.js');

// Font scheme (external or local) + CSS custom properties
$params_FontScheme = $this->params->get('useFontScheme', false);
$fontStyles        = '';

if ($params_FontScheme) {
	if (stripos($params_FontScheme, 'https://') === 0) {
		$this->getPreloadManager()->preconnect('https://fonts.googleapis.com/', ['crossorigin' => 'anonymous']);
		$this->getPreloadManager()->preconnect('https://fonts.gstatic.com/', ['crossorigin' => 'anonymous']);
		$this->getPreloadManager()->preload($params_FontScheme, ['as' => 'style', 'crossorigin' => 'anonymous']);
		$wa->registerAndUseStyle('fontscheme.current', $params_FontScheme, [], [
			'media' => 'print',
			'rel' => 'lazy-stylesheet',
			'onload' => 'this.media=\'all\'',
			'crossorigin' => 'anonymous'
		]);

		if (preg_match_all('/family=([^?:]*):/i', $params_FontScheme, $matches) > 0) {
			$fontStyles  = '--font-family-body: "' . str_replace('+', ' ', $matches[1][0]) . '", sans-serif;' . "\n";
			$fontStyles .= '--font-family-headings: "' . str_replace('+', ' ', isset($matches[1][1]) ? $matches[1][1] : $matches[1][0]) . '", sans-serif;' . "\n";
			$fontStyles .= '--font-weight-normal: 400;' . "\n";
			$fontStyles .= '--font-weight-headings: 700;';
		}
	} else {
		$wa->registerAndUseStyle('fontscheme.current', $params_FontScheme, ['version' => 'auto'], [
			'media' => 'print',
			'rel' => 'lazy-stylesheet',
			'onload' => 'this.media=\'all\''
		]);
		$this->getPreloadManager()->preload(
			$wa->getAsset('style', 'fontscheme.current')->getUri() . '?' . $this->getMediaVersion(),
			['as' => 'style']
		);
	}
}

// Expose CSS variables (and any computed font variables)
$wa->addInlineStyle(":root {\n    --hue: 214;\n    --template-bg-light: #f0f4fb;\n    --template-text-dark: #495057;\n    --template-text-light: #ffffff;\n    --template-link-color: #2a69b8;\n    --template-special-color: #001B4C;\n    $fontStyles\n}");

// -------------------------------------
// Brand: logo from params OR siteTitle
// -------------------------------------
$brandHtml = '';
$logoFile  = (string) $this->params->get('logoFile');

if ($logoFile !== '') {
	$brandHtml = HTMLHelper::_(
		'image',
		Uri::root(false) . htmlspecialchars($logoFile, ENT_QUOTES, 'UTF-8'),
		$sitename,
		['class' => 'logo d-inline-block', 'loading' => 'eager', 'decoding' => 'async'],
		false,
		0
	);
} elseif ($this->params->get('siteTitle')) {
	$brandHtml = '<span class="site-title" title="' . $sitename . '">'
			   . htmlspecialchars($this->params->get('siteTitle'), ENT_COMPAT, 'UTF-8')
			   . '</span>';
} else {
	// Fallback to a bundled image (relative to media paths)
	$brandHtml = HTMLHelper::_('image', 'full_logo.png', $sitename, ['class' => 'logo d-inline-block', 'loading' => 'eager', 'decoding' => 'async'], true, 0);
}

// Layout flags
$hasClass = '';
if ($this->countModules('sidebar-left', true))  { $hasClass .= ' has-sidebar-left'; }
if ($this->countModules('sidebar-right', true)) { $hasClass .= ' has-sidebar-right'; }
if ($this->countModules('drawer-left', true))   { $hasClass .= ' has-drawer-left'; }
if ($this->countModules('drawer-right', true))  { $hasClass .= ' has-drawer-right'; }

// Container
$wrapper      = $this->params->get('fluidContainer') ? 'wrapper-fluid' : 'wrapper-static';
$stickyHeader = $this->params->get('stickyHeader') ? 'position-sticky sticky-top' : '';

// Meta
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

// Optional Font Awesome Kit (not defined in JSON)
if ($this->params->get('fA6KitCode')) {
	$fa6Kit = "https://kit.fontawesome.com/" . $this->params->get('fA6KitCode') . ".js";
	HTMLHelper::_('script', $fa6Kit, ['crossorigin' => 'anonymous']);
}

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<?php if (trim($params_custom_head_start)) : ?><?php echo $params_custom_head_start; ?><?php endif; ?>
	<jdoc:include type="head" />

	<script>
	  // Early theme application to avoid FOUC
	  (function () {
		try {
		  var stored = localStorage.getItem('theme');
		  var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
		  var theme = stored ? stored : (prefersDark ? 'dark' : 'light');
		  document.documentElement.setAttribute('data-bs-theme', theme);
		} catch (e) {}
	  })();
	</script>

	<script>
	  // Facebook in-app browser warning banner
	  window.addEventListener('DOMContentLoaded', function () {
		var ua = navigator.userAgent || navigator.vendor || window.opera;
		var isFacebookBrowser = ua.indexOf('FBAN') > -1 || ua.indexOf('FBAV') > -1;
		if (isFacebookBrowser) {
		  var warning = document.createElement('div');
		  warning.textContent = '⚠️ KNOWN ISSUE: Images do not load in Facebook Web browser. Please open in external browser for full experience.';
		  warning.style.position = 'fixed';
		  warning.style.top = '0';
		  warning.style.left = '0';
		  warning.style.right = '0';
		  warning.style.zIndex = '10000';
		  warning.style.backgroundColor = '#007bff';
		  warning.style.color = '#fff';
		  warning.style.padding = '15px';
		  warning.style.textAlign = 'center';
		  warning.style.fontWeight = 'bold';
		  warning.style.fontSize = '16px';
		  warning.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
		  document.body.appendChild(warning);
		}
	  });
	</script>

	<?php if (trim($params_custom_head_end)) : ?><?php echo $params_custom_head_end; ?><?php endif; ?>
</head>
<body data-bs-spy="scroll" data-bs-target="#toc" class="site <?php
	echo $option . ' ' . $wrapper
	   . ' view-' . $view
	   . ($layout ? ' layout-' . $layout : ' no-layout')
	   . ($task ? ' task-' . $task : ' no-task')
	   . ($itemid ? ' itemid-' . $itemid : '')
	   . ($pageclass ? ' ' . $pageclass : '')
	   . $hasClass
	   . ($this->direction == 'rtl' ? ' rtl' : '');
?>">
<?php if (!empty($params_googletagmanager) && !empty($params_googletagmanagerid)) :
	$gtmID = htmlspecialchars($params_googletagmanagerid, ENT_QUOTES, 'UTF-8'); ?>
	<!-- Google Tag Manager -->
	<script>
		(function(w,d,s,l,i){
			w[l]=w[l]||[];
			w[l].push({'gtm.start': new Date().getTime(), event:'gtm.js'});
			var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),
				dl=l!='dataLayer'?'&l='+l:'';
			j.async=true;
			j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
			f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php echo $gtmID; ?>');
	</script>
	<!-- End Google Tag Manager -->

	<!-- Google Tag Manager (noscript) -->
	<noscript>
		<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtmID; ?>"
				height="0" width="0" style="display:none;visibility:hidden"></iframe>
	</noscript>
	<!-- End Google Tag Manager (noscript) -->
<?php endif; ?>

<?php if (!empty($params_googleanalytics) && !empty($params_googleanalyticsid)) :
	$gaId = htmlspecialchars($params_googleanalyticsid, ENT_QUOTES, 'UTF-8'); ?>
	<!-- Google Analytics (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $gaId; ?>"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('consent', 'default', {
			'ad_storage': 'denied',
			'analytics_storage': 'granted',
			'ad_user_data': 'denied',
			'ad_personalization': 'denied'
		});
		(function(id){
			if (/^G-/.test(id)) {
				gtag('config', id, { 'anonymize_ip': true });
			} else if (/^UA-/.test(id)) {
				gtag('config', id, { 'anonymize_ip': true });
				console.warn('Using a UA- ID. Universal Analytics is sunset; consider migrating to GA4.');
			} else {
				console.warn('Unrecognized Google Analytics ID format:', id);
			}
		})('<?php echo $gaId; ?>');
	</script>
	<!-- End Google Analytics -->
<?php endif; ?>

<header class="header container-header full-width<?php echo $stickyHeader ? ' ' . $stickyHeader : ''; ?>" role="banner">

	<?php if ($this->countModules('topbar')) : ?>
		<div class="container-topbar">
			<jdoc:include type="modules" name="topbar" style="none" />
		</div>
	<?php endif; ?>

	<div class="header-top">
		<?php if ($this->countModules('below-topbar')) : ?>
			<div class="grid-child container-below-topbar">
				<jdoc:include type="modules" name="below-topbar" style="none" />
			</div>
		<?php endif; ?>

		<?php if ($this->params->get('brand', 1)) : ?>
			<div class="grid-child">
				<div class="navbar-brand">
					<a class="brand-logo" href="<?php echo $this->baseurl; ?>/">
						<?php echo $brandHtml; ?>
					</a>
					<?php if ($this->params->get('siteDescription')) : ?>
						<div class="site-description">
						  <?php echo htmlspecialchars($this->params->get('siteDescription'), ENT_QUOTES, 'UTF-8'); ?>
						</div>
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
				<nav role="navigation" aria-label="Primary">
					<jdoc:include type="modules" name="menu" style="none" />
				</nav>
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
		<main id="maincontent" role="main">
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
