<?php
/* Copyright (C) 2026 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later



 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: MokoCassiopeia
 REPO: https://github.com/mokoconsulting-tech/MokoCassiopeia
 PATH: ./templates/mokocassiopeia/offline.php
 VERSION: 03.06.02
 BRIEF: Offline page template file for MokoCassiopeia
 */

declare(strict_types=1);

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/**
 * @var \Joomla\CMS\Document\HtmlDocument $this
 * @var \Joomla\Registry\Registry         $this->params
 * @var string                            $this->language
 * @var string                            $this->direction
 */

$app       = Factory::getApplication();
$doc       = Factory::getDocument();
$params    = $this->params ?: $app->getTemplate(true)->params;
$direction = $this->direction ?: 'ltr';

/* -----------------------
   Load ONLY template.css + colors_*.css (with min toggle)
------------------------ */
$useMin      = !((int) $params->get('development_mode', 0) === 1);
$assetSuffix = $useMin ? '.min' : '';
$base        = rtrim(Uri::root(true), '/') . '/templates/' . $this->template . '/css/';

$doc->addStyleSheet($base . 'template' . $assetSuffix . '.css', ['version' => 'auto'], ['id' => 'moko-template']);
/* If you have a template param for color variant, set it here; defaults to 'standard' */
$colorKey = (string) ($params->get('colors', 'standard') ?: 'standard');
$colorKey = preg_replace('~[^a-z0-9_-]~i', '', $colorKey);
$doc->addStyleSheet($base . 'colors_' . $colorKey . $assetSuffix . '.css', ['version' => 'auto'], ['id' => 'moko-colors']);

/* Bootstrap CSS/JS for accordion behavior; safe to keep. */
HTMLHelper::_('bootstrap.loadCss', true, $doc);
HTMLHelper::_('bootstrap.framework');

/* -----------------------
   Title + Meta (Include Site Name in Page Titles)
------------------------ */
$sitename  = (string) $app->get('sitename');
$baseTitle = Text::_('JGLOBAL_OFFLINE') ?: 'Offline';
$snSetting = (int) $app->get('sitename_pagetitles', 0); // 0=no, 1=before, 2=after

if ($snSetting === 1) {
	$doc->setTitle(Text::sprintf('JPAGETITLE', $sitename, $baseTitle));  // Site Name BEFORE
} elseif ($snSetting === 2) {
	$doc->setTitle(Text::sprintf('JPAGETITLE', $baseTitle, $sitename));  // Site Name AFTER
} else {
	$doc->setTitle($baseTitle);
}
$doc->setMetaData('robots', 'noindex, nofollow');

/* -----------------------
   Offline content from Global Config
------------------------ */
$displayOfflineMessage = (int) $app->get('display_offline_message', 1); // 0|1|2
$offlineMessage        = trim((string) $app->get('offline_message', ''));

/* -----------------------
   Brand (mutually exclusive: logoFile OR siteTitle)
------------------------ */
if ($params->get('logoFile')) {
	$logo = HTMLHelper::_(
		'image',
		Uri::root(false) . htmlspecialchars((string) $params->get('logoFile'), ENT_QUOTES, 'UTF-8'),
		$sitename,
		[
			'class'    => 'logo d-inline-block',
			'loading'  => 'eager',
			'decoding' => 'async',
			'style'    => 'max-height:64px;height:auto;width:auto;'
		],
		false,
		0
	);
} elseif ($params->get('siteTitle')) {
	$logo = '<span class="logo-text d-inline-block" title="' . htmlspecialchars($sitename, ENT_COMPAT, 'UTF-8') . '">'
	      . htmlspecialchars((string) $params->get('siteTitle'), ENT_COMPAT, 'UTF-8')
	      . '</span>';
} else {
	$logo = HTMLHelper::_(
		'image',
		'full_logo.png',
		$sitename,
		[
			'class'    => 'logo d-inline-block',
			'loading'  => 'eager',
			'decoding' => 'async',
			'style'    => 'max-height:64px;height:auto;width:auto;'
		],
		true,
		0
	);
}

$brandTagline = (string) ($params->get('brand_tagline') ?: $params->get('siteDescription') ?: '');
$showTagline  = (int) $params->get('show_brand_tagline', 0);
$showSwitcher = (int) $params->get('show_theme_switcher', 1);

/* -----------------------
   Login routes & Users
------------------------ */
$action = Route::_('index.php', true);
$return = base64_encode(Uri::base());
$allowRegistration = (bool) ComponentHelper::getParams('com_users')->get('allowUserRegistration', 0);

if (class_exists('\Joomla\Component\Users\Site\Helper\RouteHelper')) {
	$resetUrl        = \Joomla\Component\Users\Site\Helper\RouteHelper::getResetRoute();
	$remindUrl       = \Joomla\Component\Users\Site\Helper\RouteHelper::getRemindRoute();
	$registrationUrl = \Joomla\Component\Users\Site\Helper\RouteHelper::getRegistrationRoute();
} else {
	$resetUrl        = Route::_('index.php?option=com_users&view=reset');
	$remindUrl       = Route::_('index.php?option=com_users&view=remind');
	$registrationUrl = Route::_('index.php?option=com_users&view=registration');
}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($this->language ?? 'en', ENT_QUOTES, 'UTF-8'); ?>" dir="<?php echo htmlspecialchars($direction, ENT_QUOTES, 'UTF-8'); ?>">
<head>
	<jdoc:include type="head" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<!-- Three-mode theme bootstrapper: system | light | dark -->
	<script>
		(function () {
			try {
				var saved = localStorage.getItem('moko-theme'); // '', 'light', or 'dark'
				var root  = document.documentElement;

				function apply(mode) {
					root.removeAttribute('data-theme');
					root.classList.remove('theme-dark');
					if (mode === 'light') {
						root.setAttribute('data-theme', 'light');
					} else if (mode === 'dark') {
						root.setAttribute('data-theme', 'dark');
						root.classList.add('theme-dark');
					}
				}

				var initial = (saved === 'light' || saved === 'dark') ? saved : 'system';
				apply(initial);

				if (initial === 'system' && window.matchMedia) {
					var mq = window.matchMedia('(prefers-color-scheme: dark)');
					mq.addEventListener && mq.addEventListener('change', function () { apply('system'); });
				}

				window.__mokoSetTheme = function (mode) {
					if (mode === 'light' || mode === 'dark') {
						localStorage.setItem('moko-theme', mode);
					} else {
						localStorage.removeItem('moko-theme'); mode = 'system';
					}
					apply(mode);
					try {
						document.querySelectorAll('[data-theme-select]').forEach(function (el) {
							el.classList.toggle('active', el.getAttribute('data-theme-select') === mode);
							el.setAttribute('aria-current', el.classList.contains('active') ? 'true' : 'false');
						});
					} catch(e){}
				};
			} catch (e) {}
		}());
	</script>

	<style>
		.moko-offline-wrap { min-height: 100vh; display: grid; grid-template-rows: auto 1fr auto; }
		.moko-offline-main { display: grid; place-items: center; padding: 2rem 1rem; }
		.moko-card { max-width: 720px; width: 100%; }
		.moko-brand { display:flex; align-items:center; gap:.75rem; text-decoration:none; }
		.moko-brand .brand-tagline { display:block; opacity:.75; font-size:.875rem; line-height:1.2; }
		.theme-switcher .dropdown-item.active { font-weight:600; }
		.skip-link { position:absolute; left:-9999px; top:auto; width:1px; height:1px; overflow:hidden; }
		.skip-link:focus { position:static; width:auto; height:auto; padding:.5rem 1rem; }
	</style>
</head>
<body class="site moko-offline-wrap <?php echo htmlspecialchars($direction, ENT_QUOTES, 'UTF-8'); ?>">
	<a class="skip-link" href="#maincontent"><?php echo Text::_('JSKIP_TO_CONTENT') ?: 'Skip to content'; ?></a>

	<header class="container-header header py-3">
		<div class="grid-child container-nav d-flex align-items-center gap-3">

			<!-- Brand (mutually exclusive image/text) -->
			<a class="moko-brand me-auto" href="<?php echo htmlspecialchars(Uri::base(), ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($sitename, ENT_COMPAT, 'UTF-8'); ?>">
				<?php echo $logo; ?>
				<?php if ($showTagline && $brandTagline): ?>
					<small class="brand-tagline"><?php echo htmlspecialchars($brandTagline, ENT_COMPAT, 'UTF-8'); ?></small>
				<?php endif; ?>
			</a>

			<!-- Theme switcher (System / Light / Dark) -->
			<?php if ($showSwitcher): ?>
			<div class="theme-switcher dropdown me-2">
				<button class="btn btn-outline-secondary dropdown-toggle" type="button" id="themeDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="<?php echo Text::_('JTOGGLE_DARK_MODE'); ?>">
					<span class="me-1">Theme</span> <span aria-hidden="true">🌓</span>
				</button>
				<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="themeDropdown">
					<li><button type="button" class="dropdown-item" data-theme-select="system" onclick="window.__mokoSetTheme('system')">System</button></li>
					<li><button type="button" class="dropdown-item" data-theme-select="light"  onclick="window.__mokoSetTheme('light')">Light</button></li>
					<li><button type="button" class="dropdown-item" data-theme-select="dark"   onclick="window.__mokoSetTheme('dark')">Dark</button></li>
				</ul>
			</div>
			<script>
				(function(){
					try {
						var saved = localStorage.getItem('moko-theme');
						var mode  = (saved === 'light' || saved === 'dark') ? saved : 'system';
						document.querySelectorAll('[data-theme-select]').forEach(function (el) {
							el.classList.toggle('active', el.getAttribute('data-theme-select') === mode);
							el.setAttribute('aria-current', el.classList.contains('active') ? 'true' : 'false');
						});
					} catch(e){}
				}());
			</script>
			<?php endif; ?>

			<!-- Header module position: offline-header -->
			<?php if ($this->countModules('offline-header')) : ?>
				<div class="ms-2">
					<jdoc:include type="modules" name="offline-header" style="none" />
				</div>
			<?php endif; ?>

		</div>
	</header>

	<main id="maincontent" class="moko-offline-main">
		<div class="container">
			<jdoc:include type="message" />

			<div class="moko-card card shadow-sm rounded-3 p-4 p-md-5">
				<?php if ($displayOfflineMessage === 1 && $offlineMessage !== '') : ?>
					<div class="mb-4">
						<h1 class="h3 mb-2"><?php echo Text::_('JOFFLINE_MESSAGE') ?: 'Site Offline'; ?></h1>
						<p class="lead mb-0"><?php echo $offlineMessage; ?></p>
					</div>
				<?php elseif ($displayOfflineMessage === 2) : ?>
					<div class="mb-4">
						<h1 class="h3 mb-2"><?php echo Text::_('JOFFLINE_MESSAGE') ?: 'Site Offline'; ?></h1>
						<p class="lead mb-0">
							<?php echo Text::_('JOFFLINE_MESSAGE_DEFAULT') ?: 'This site is down for maintenance. Please check back soon.'; ?>
						</p>
					</div>
				<?php endif; ?>

				<!-- Main offline module position -->
				<?php if ($this->countModules('offline')) : ?>
					<section class="mb-4" aria-label="Offline modules">
						<jdoc:include type="modules" name="offline" style="none" />
					</section>
				<?php endif; ?>

				<!-- Login UNDER an accordion (collapsed by default) -->
				<div class="accordion" id="offlineAccordion">
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingLogin">
							<button class="accordion-button collapsed" type="button"
								data-bs-toggle="collapse" data-bs-target="#collapseLogin"
								aria-expanded="false" aria-controls="collapseLogin">
								<?php echo Text::_('JLOGIN'); ?>
							</button>
						</h2>
						<div id="collapseLogin" class="accordion-collapse collapse" aria-labelledby="headingLogin" data-bs-parent="#offlineAccordion">
							<div class="accordion-body">
								<form action="<?php echo $action; ?>" method="post" class="form-validate">
									<fieldset>
										<legend class="visually-hidden"><?php echo Text::_('JLOGIN'); ?></legend>

										<div class="mb-3">
											<label class="form-label" for="username"><?php echo Text::_('JGLOBAL_USERNAME'); ?></label>
											<input class="form-control" type="text" name="username" id="username" autocomplete="username" required aria-required="true">
										</div>

										<div class="mb-3">
											<label class="form-label" for="password"><?php echo Text::_('JGLOBAL_PASSWORD'); ?></label>
											<input class="form-control" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true">
										</div>

										<div class="mb-3">
											<label class="form-label" for="secretkey"><?php echo Text::_('JGLOBAL_SECRETKEY'); ?></label>
											<input class="form-control" type="text" name="secretkey" id="secretkey" autocomplete="one-time-code" placeholder="<?php echo Text::_('JGLOBAL_SECRETKEY'); ?>">
										</div>

										<div class="form-check mb-4">
											<input class="form-check-input" type="checkbox" name="remember" id="remember">
											<label class="form-check-label" for="remember"><?php echo Text::_('JGLOBAL_REMEMBER_ME'); ?></label>
										</div>

										<div class="d-grid">
											<button type="submit" class="btn btn-primary"><?php echo Text::_('JLOGIN'); ?></button>
										</div>

										<input type="hidden" name="option" value="com_users">
										<input type="hidden" name="task" value="user.login">
										<input type="hidden" name="return" value="<?php echo $return; ?>">
										<?php echo HTMLHelper::_('form.token'); ?>
									</fieldset>

									<nav class="mt-3 small" aria-label="<?php echo Text::_('COM_USERS'); ?>">
										<ul class="list-inline m-0">
											<li class="list-inline-item">
												<a href="<?php echo $resetUrl; ?>"><?php echo Text::_('COM_USERS_LOGIN_RESET'); ?></a>
											</li>
											<li class="list-inline-item">
												<a href="<?php echo $remindUrl; ?>"><?php echo Text::_('COM_USERS_LOGIN_REMIND'); ?></a>
											</li>
											<?php if ($allowRegistration) : ?>
												<li class="list-inline-item">
													<a href="<?php echo $registrationUrl; ?>"><?php echo Text::_('COM_USERS_REGISTER'); ?></a>
												</li>
											<?php endif; ?>
										</ul>
									</nav>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /accordion -->
			</div>
		</div>
	</main>

	<!-- No footer modules on offline page -->
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
