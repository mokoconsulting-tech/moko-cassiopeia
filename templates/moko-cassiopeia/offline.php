<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.Moko-Cassiopeia
 *
 * Offline template that explicitly loads css/template.css and css/colors_*.css.
 * Includes: site-name-in-title rules, brand (logo OR title), theme switcher,
 * module positions (offline-header, offline), and login inside Bootstrap accordion.
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
	$logo = '<sp
