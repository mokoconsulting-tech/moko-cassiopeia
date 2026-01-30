<?php
/* Copyright (C) 2026 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later



 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: MokoCassiopeia
 REPO: https://github.com/mokoconsulting-tech/MokoCassiopeia
 PATH: ./templates/mokocassiopeia/error.php
 VERSION: 03.06.02
 BRIEF: Error page template file for MokoCassiopeia
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\ErrorDocument|Joomla\CMS\Document\HtmlDocument $this */
$app    = Factory::getApplication();
$params = $this->params;
$wa     = $this->getWebAssetManager();

// Template params
$params_LightColorName          = (string) $params->get('colorLightName', 'colors_standard'); // colors_standard|colors_alternative|colors_custom

$params_DarkColorName          = (string) $params->get('colorDarkName', 'colors_standard'); // colors_standard|colors_alternative|colors_custom

$params_googletagmanager   = $params->get('googletagmanager', false);
$params_googletagmanagerid = $params->get('googletagmanagerid', '');
$params_googleanalytics    = $params->get('googleanalytics', false);
$params_googleanalyticsid  = $params->get('googleanalyticsid', '');
$params_custom_head_start  = $params->get('custom_head_start', '');
$params_custom_head_end    = $params->get('custom_head_end', '');
$params_developmentmode = $params->get('developmentmode', false);

// ------------------ Params ------------------
$fluidContainer  = (bool)   $params->get('fluidContainer', 0);
$wrapper         = $fluidContainer ? 'wrapper-fluid' : 'wrapper-static';
$stickyHeader    = (bool)   $params->get('stickyHeader', 0);

// Drawer icon params (escaped)
$params_leftIcon  = htmlspecialchars($params->get('drawerLeftIcon',  'fa-solid fa-chevron-left'), ENT_QUOTES, 'UTF-8');
$params_rightIcon = htmlspecialchars($params->get('drawerRightIcon', 'fa-solid fa-chevron-right'),  ENT_QUOTES, 'UTF-8');

// Template/Media path
$templatePath = 'media/templates/site/mokocassiopeia';

// ===========================
// Web Asset Manager (WAM) — matches your joomla.asset.json
// ===========================

// Core template CSS
$wa->useStyle('template.base');   // css/template.css

// Color theme (light + optional dark)
$colorLightKey  = strtolower(preg_replace('/[^a-z0-9_.-]/i', '', $params_LightColorName));
$colorDarkKey  = strtolower(preg_replace('/[^a-z0-9_.-]/i', '', $params_DarkColorName));
$lightKey  = 'template.light.' . $colorLightKey;
$darkKey   = 'template.dark.' . $colorDarkKey;
try {
	$wa->useStyle('template.light.colors_standard');
} catch (\Throwable $e) {
	$wa->registerAndUseStyle('template.light.colors_standard', $templatePath . '/css/colors/light/colors_standard.css');
}
try {
	$wa->useStyle('template.dark.colors_standard');
} catch (\Throwable $e) {
	$wa->registerAndUseStyle('template.dark.colors_standard', $templatePath . '/css/colors/dark/colors_standard.css');
}
try {
	$wa->useStyle($lightKey);
} catch (\Throwable $e) {
	$wa->registerAndUseStyle('template.light.dynamic', $templatePath . '/css/colors/light/' . $colorLightKey . '.css');
}
try {
	$wa->useStyle($darkKey);
} catch (\Throwable $e) {
	$wa->registerAndUseStyle('template.dark.dynamic', $templatePath . '/css/colors/dark/' . $colorDarkKey . '.css');
}

// Scripts
$wa->useScript('template.js');

// Load Osaka font for site title
$wa->useStyle('template.font.osaka');

// Smart Bootstrap component loading - only load what's needed
if ($this->countModules('drawer-left', true) || $this->countModules('drawer-right', true)) {
	// Load Bootstrap Offcanvas component for drawers
	HTMLHelper::_('bootstrap.offcanvas');
}

// Meta
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

if ($this->params->get('faKitCode')) {
	$faKit = "https://kit.fontawesome.com/" . $this->params->get('faKitCode') . ".js";
	HTMLHelper::_('script', $faKit, ['crossorigin' => 'anonymous']);
} else {
		try {
			if ($params_developmentmode){
				$wa->useStyle('vendor.fa7free.all');
				$wa->useStyle('vendor.fa7free.brands');
				$wa->useStyle('vendor.fa7free.fontawesome');
				$wa->useStyle('vendor.fa7free.regular');
				$wa->useStyle('vendor.fa7free.solid');
			} else {
				$wa->useStyle('vendor.fa7free.all.min');
				$wa->useStyle('vendor.fa7free.brands.min');
				$wa->useStyle('vendor.fa7free.fontawesome.min');
				$wa->useStyle('vendor.fa7free.regular.min');
				$wa->useStyle('vendor.fa7free.solid.min');
			}
	} catch (\Throwable $e) {
		if ($params_developmentmode){
			$wa->registerAndUseStyle('vendor.fa7free.all.dynamic', $templatePath . '/vendor/fa7free/css/all.css');
			$wa->registerAndUseStyle('vendor.fa7free.brands.dynamic', $templatePath . '/vendor/fa7free/css/brands.css');
			$wa->registerAndUseStyle('vendor.fa7free.fontawesome.dynamic', $templatePath . '/vendor/fa7free/css/fontawesome.css');
			$wa->registerAndUseStyle('vendor.fa7free.regular.dynamic', $templatePath . '/vendor/fa7free/css/regular.css');
			$wa->registerAndUseStyle('vendor.fa7free.solid.dynamic', $templatePath . '/vendor/fa7free/css/solid.css');
		} else {
			$wa->registerAndUseStyle('vendor.fa7free.all.min.dynamic', $templatePath . '/vendor/fa7free/css/all.min.css');
			$wa->registerAndUseStyle('vendor.fa7free.brands.min.dynamic', $templatePath . '/vendor/fa7free/css/brands.min.css');
			$wa->registerAndUseStyle('vendor.fa7free.fontawesome.min.dynamic', $templatePath . '/vendor/fa7free/css/fontawesome.min.css');
			$wa->registerAndUseStyle('vendor.fa7free.regular.min.dynamic', $templatePath . '/vendor/fa7free/css/regular.min.css');
			$wa->registerAndUseStyle('vendor.fa7free.solid.min.dynamic', $templatePath . '/vendor/fa7free/css/solid.min.css');
		}

	}
}

$wa->useStyle('template.user');   // css/user.css

// ------------------ Context (logo, bootstrap needs) ------------------
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');

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
} else {
	// If no logo file, show the title (defaults to "MokoCassiopeia" if not set)
	$siteTitle = $this->params->get('siteTitle', 'MokoCassiopeia');
	$brandHtml = '<span class="site-title" title="' . $sitename . '">'
			   . htmlspecialchars($siteTitle, ENT_COMPAT, 'UTF-8')
			   . '</span>';
}

// ------------------ Error details ------------------
$errorObj  = isset($this->error) && is_object($this->error) ? $this->error : null;
$errorCode = $errorObj ? (int) $errorObj->getCode() : 500;
$errorMsg  = $errorObj ? $errorObj->getMessage() : Text::_('JERROR_AN_ERROR_HAS_OCCURRED');
$debugOn   = defined('JDEBUG') && JDEBUG;
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<?php if ($params_custom_head_start !== '') : ?><?php echo $params_custom_head_start; ?><?php endif; ?>
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

	<?php if ($params_custom_head_end !== '') : ?><?php echo $params_custom_head_end; ?><?php endif; ?>
</head>
<body data-bs-spy="scroll" data-bs-target="#toc" class="site error-page<?php
	echo ($this->direction == 'rtl' ? ' rtl' : '');
?>">
<?php if (!empty($params_googletagmanager) && !empty($params_googletagmanagerid)) : ?>
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
		})(window,document,'script','dataLayer',<?php echo json_encode($params_googletagmanagerid, JSON_HEX_TAG | JSON_HEX_AMP); ?>);
	</script>
	<!-- End Google Tag Manager -->

	<!-- Google Tag Manager (noscript) -->
	<noscript>
		<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo htmlspecialchars($params_googletagmanagerid, ENT_QUOTES, 'UTF-8'); ?>"
				height="0" width="0" style="display:none;visibility:hidden"></iframe>
	</noscript>
	<!-- End Google Tag Manager (noscript) -->
<?php endif; ?>

<?php if (!empty($params_googleanalytics) && !empty($params_googleanalyticsid)) : ?>
	<!-- Google Analytics (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo htmlspecialchars($params_googleanalyticsid, ENT_QUOTES, 'UTF-8'); ?>"></script>
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
		})(<?php echo json_encode($params_googleanalyticsid, JSON_HEX_TAG | JSON_HEX_AMP); ?>);
	</script>
	<!-- End Google Analytics -->
<?php endif; ?>

<!-- ========== HEADER FROM INDEX ========== -->
<header class="header container-header full-width<?php echo $stickyHeader ? ' position-sticky sticky-top' : ''; ?>" role="banner">

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
<!-- ========== END HEADER ========== -->

<main class="container my-4">
	<div class="card border-0 shadow-sm mb-4">
		<div class="card-body">
			<h1 class="h3">
				<span class="text-muted"><?php echo Text::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED'); ?>:</span>
				<strong><?php echo (int) $errorCode; ?></strong>
			</h1>
			<p class="lead mb-1">
				<?php echo htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8'); ?>
			</p>
			<p class="text-muted mb-0">
				<?php echo Text::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES'); ?>
			</p>
		</div>
	</div>

	<div class="d-flex gap-2 flex-wrap">
		<a class="btn btn-primary" href="<?php echo htmlspecialchars(Uri::base(), ENT_QUOTES, 'UTF-8'); ?>">
			<i class="fa-solid fa-home me-1" aria-hidden="true"></i>
			<?php echo Text::_('JERROR_LAYOUT_HOME_PAGE'); ?>
		</a>
		<button class="btn btn-outline-secondary" type="button" onclick="history.back();">
			<i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i>
			<?php echo Text::_('JPREV'); ?>
		</button>
	</div>

<?php if ($debugOn && $errorObj) : ?>
	<section class="mt-4" role="region" aria-label="Debug Details">
		<div class="alert alert-warning"><strong>Debug mode is ON</strong> — detailed error information is shown below.</div>

		<div class="card mb-3">
			<div class="card-header fw-bold">Exception</div>
			<div class="card-body small">
				<dl class="row mb-0">
					<dt class="col-sm-3">Class</dt>
					<dd class="col-sm-9"><?php echo htmlspecialchars(get_class($errorObj), ENT_QUOTES, 'UTF-8'); ?></dd>

					<dt class="col-sm-3">Code</dt>
					<dd class="col-sm-9"><?php echo (int) $errorObj->getCode(); ?></dd>

					<dt class="col-sm-3">Message</dt>
					<dd class="col-sm-9 text-break"><?php echo htmlspecialchars($errorObj->getMessage(), ENT_QUOTES, 'UTF-8'); ?></dd>

					<dt class="col-sm-3">File</dt>
					<dd class="col-sm-9 text-break"><?php echo htmlspecialchars($errorObj->getFile(), ENT_QUOTES, 'UTF-8'); ?> : <?php echo (int) $errorObj->getLine(); ?></dd>
				</dl>
			</div>
		</div>

		<?php $trace = method_exists($errorObj, 'getTrace') ? $errorObj->getTrace() : []; ?>
		<div class="card mb-3">
			<div class="card-header fw-bold">Stack Trace (<?php echo count($trace); ?> frames)</div>
			<div class="card-body small">
				<?php if ($trace) : ?>
					<ol class="mb-0 ps-3">
						<?php foreach ($trace as $i => $frame) :
							$file = $frame['file'] ?? '[internal]';
							$line = isset($frame['line']) ? (int) $frame['line'] : 0;
							$func = $frame['function'] ?? '';
							$class= $frame['class'] ?? '';
							$type = $frame['type'] ?? '';
						?>
						<li class="mb-2">
							<div class="text-break"><code>#<?php echo $i; ?></code> <?php echo htmlspecialchars($class . $type . $func, ENT_QUOTES, 'UTF-8'); ?>()</div>
							<div class="text-muted"><?php echo htmlspecialchars($file, ENT_QUOTES, 'UTF-8'); ?><?php echo $line ? ':' . $line : ''; ?></div>
						</li>
						<?php endforeach; ?>
					</ol>
				<?php else : ?>
					<em>No stack trace available.</em>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>
</main>
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
	<a href="#top" id="back-top" class="back-to-top-link" aria-label="<?php echo Text::_('TPL_MOKOCASSIOPEIA_BACKTOTOP'); ?>">
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
