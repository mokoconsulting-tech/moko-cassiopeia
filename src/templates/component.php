<?php
/* Copyright (C) 2026 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later



 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: MokoCassiopeia
 REPO: https://github.com/mokoconsulting-tech/MokoCassiopeia
 PATH: ./templates/mokocassiopeia/component.php
 VERSION: 03.06.02
 BRIEF: Main template index file for MokoCassiopeia rendering site layout
 */


defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Component\ComponentHelper;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app   = Factory::getApplication();
$input = $app->getInput();
$document = $app->getDocument();
$wa    = $document->getWebAssetManager();

// Template params
$params_LightColorName          = (string) $this->params->get('colorLightName', 'colors_standard'); // colors_standard|colors_alternative|colors_custom

$params_DarkColorName          = (string) $this->params->get('colorDarkName', 'colors_standard'); // colors_standard|colors_alternative|colors_custom

$params_googletagmanager   = $this->params->get('googletagmanager', false);
$params_googletagmanagerid = $this->params->get('googletagmanagerid', null);
$params_googleanalytics    = $this->params->get('googleanalytics', false);
$params_googleanalyticsid  = $this->params->get('googleanalyticsid', null);
$params_custom_head_start  = $this->params->get('custom_head_start', null);
$params_custom_head_end    = $this->params->get('custom_head_end', null);
$params_developmentmode = $this->params->get('developmentmode', false);

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
$templatePath = 'media/templates/site/mokocassiopeia';

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

/**
 * VirtueMart detection:
 * - Component must exist and be enabled
 */
$isVirtueMartActive = ComponentHelper::isEnabled('com_virtuemart', true);

if ($isVirtueMartActive) {
    /**
     * Load a VirtueMart-specific stylesheet defined in your template manifest.
     * This assumes you defined an asset named "template.virtuemart".
     */
    $wa->useStyle('vendor.vm');
}

// Font scheme (external or local) + CSS custom properties
$params_FontScheme = $this->params->get('useFontScheme', false);
$fontStyles = '';

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

// Meta
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

if ($this->params->get('faKitCode')) {
	$faKit = "https://kit.fontawesome.com/" . $this->params->get('faKitCode') . ".js";
	HTMLHelper::_('script', $faKit, ['crossorigin' => 'anonymous']);
} else {
		try {
			if($params_developmentmode){
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
		if($params_developmentmode){
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
$params_leftIcon           = htmlspecialchars($this->params->get('drawerLeftIcon', 'fa-solid fa-chevron-left'), ENT_COMPAT, 'UTF-8');
$params_rightIcon          = htmlspecialchars($this->params->get('drawerRightIcon', 'fa-solid fa-chevron-right'), ENT_COMPAT, 'UTF-8');

$wa->useStyle('template.user');   // css/user.css
?>
<!DOCTYPE html>
<html class="component" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
</head>
<body class="<?php echo $this->direction === 'rtl' ? 'rtl' : ''; ?>">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>
