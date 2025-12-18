<?php
/* Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 This program is free software; you can redistribute it and/or modify  it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

 You should have received a copy of the GNU General Public License along with this program. If not, see https://www.gnu.org/licenses/ .

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: Moko-Cassiopeia
 REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
 PATH: ./templates/moko-cassiopeia/error.php
 VERSION: 03.05.00
 BRIEF: Error page template file for Moko-Cassiopeia
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

// ------------------ Params ------------------
$colorLight      = (string) $params->get('colorLightName', 'colors_standard');
$colorDark       = (string) $params->get('colorDarkName',  'colors_standard');
$themeFab        = (int)    $params->get('theme_fab_enabled', 1);
$fABodyPos       = (string) $params->get('theme_fab_pos', 'br');
$gtmEnabled      = (int)    $params->get('googletagmanager', 0);
$gtmId           = (string) $params->get('googletagmanagerid', '');
$fa6KitCode      = (string) $params->get('fA6KitCode', '');
$stickyHeader    = (bool)   $params->get('stickyHeader', 0);
$brandEnabled    = (int)    $params->get('brand', 1);
$siteDescription = (string) $params->get('siteDescription', '');

// Drawer icon params (escaped)
$params_leftIcon  = htmlspecialchars($params->get('drawerLeftIcon',  'fa-solid fa-chevron-right'), ENT_QUOTES, 'UTF-8');
$params_rightIcon = htmlspecialchars($params->get('drawerRightIcon', 'fa-solid fa-chevron-left'),  ENT_QUOTES, 'UTF-8');

// ------------------ Styles ------------------
$wa->useStyle('template.base');
$wa->useStyle('template.user');

// Light/Dark variable sheets (load before consumers)
if ($wa->assetExists('style', 'template.light.' . $colorLight)) {
	$wa->useStyle('template.light.' . $colorLight);
}
if ($wa->assetExists('style', 'template.dark.' . $colorDark)) {
	$wa->useStyle('template.dark.' . $colorDark);
}

// ------------------ Scripts ------------------
$wa->useScript('theme-init.js');
if ($themeFab === 1) {
	$wa->useScript('darkmode-toggle.js');
}
if ($gtmEnabled === 1) {
	$wa->useScript('gtm.js');
}

// Optional Font Awesome 6 Kit (preferred) or FA5 fallback
if (!empty($fa6KitCode)) {
	HTMLHelper::_('script', 'https://kit.fontawesome.com/' . rawurlencode($fa6KitCode) . '.js', [
		'crossorigin' => 'anonymous'
	]);
} else {
	HTMLHelper::_('stylesheet', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', ['version' => 'auto'], [
		'crossorigin'    => 'anonymous',
		'referrerpolicy' => 'no-referrer',
	]);
}

// ------------------ Context (logo, bootstrap needs) ------------------
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');

// Build logo/title
if ($params->get('logoFile')) {
	$logo = HTMLHelper::_(
		'image',
		Uri::root(false) . htmlspecialchars($params->get('logoFile'), ENT_QUOTES),
		$sitename,
		['loading' => 'eager', 'decoding' => 'async'],
		false,
		0
	);
} elseif ($params->get('siteTitle')) {
	$logo = '<span title="' . $sitename . '">' . htmlspecialchars($params->get('siteTitle'), ENT_COMPAT, 'UTF-8') . '</span>';
} else {
	$logo = HTMLHelper::_('image', 'full_logo.png', $sitename, ['class' => 'logo d-inline-block', 'loading' => 'eager', 'decoding' => 'async'], true, 0);
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
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="theme-color" content="#ffffff" id="meta-theme-color" />
	<jdoc:include type="head" />
</head>
<body data-theme-fab-pos="<?php echo htmlspecialchars($fABodyPos, ENT_QUOTES, 'UTF-8'); ?>">
<?php if ($gtmEnabled === 1 && !empty($gtmId)) : ?>
	<!-- Google Tag Manager (noscript) -->
	<noscript>
		<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo htmlspecialchars($gtmId, ENT_QUOTES, 'UTF-8'); ?>"
				height="0" width="0" style="display:none;visibility:hidden"></iframe>
	</noscript>
	<!-- End Google Tag Manager (noscript) -->
<?php endif; ?>

<!-- ========== DUPLICATED HEADER FROM INDEX ========== -->
<header class="header container-header full-width<?php echo $stickyHeader ? ' position-sticky sticky-top' : ''; ?>">
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

		<?php if ($brandEnabled) : ?>
			<div class="grid-child">
				<div class="navbar-brand">
					<a class="brand-logo" href="<?php echo $this->baseurl; ?>/">
						<?php echo $logo; ?>
					</a>
					<?php if (!empty($siteDescription)) : ?>
						<div class="site-description"><?php echo htmlspecialchars($siteDescription); ?></div>
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
<!-- ========== END DUPLICATED HEADER ========== -->

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
			<i class="fas fa-home me-1" aria-hidden="true"></i>
			<?php echo Text::_('JERROR_LAYOUT_HOME_PAGE'); ?>
		</a>
		<button class="btn btn-outline-secondary" type="button" onclick="history.back();">
			<i class="fas fa-arrow-left me-1" aria-hidden="true"></i>
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
<footer class="container-footer footer full-width py-4">
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

<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
