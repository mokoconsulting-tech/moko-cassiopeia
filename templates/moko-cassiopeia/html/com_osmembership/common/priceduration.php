<?php

/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2025 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Layout variables
 *
 * @var stdClass $item
 */

$config = OSMembershipHelper::getConfig();

$dec_point     = $config->dec_point ?? '.';
$thousands_sep = $config->thousands_sep ?? ',';

if ($item->lifetime_membership)
{
	$subscriptionLengthText = Text::_('OSM_LIFETIME');
}
else
{
	$subscriptionLengthText = OSMembershipHelperSubscription::getDurationText($item->subscription_length, $item->subscription_length_unit, false);
}

if ($item->price > 0)
{
	$priceParts = explode('.', $item->price);

	if ($priceParts[1] == '00' || $config->decimals === '0')
	{
		$numberDecimals = 0;
	}
	else
	{
		$numberDecimals = 2;
	}

	$symbol = $item->currency_symbol ?: $item->currency;

	if (!$symbol)
	{
		$symbol = $config->currency_symbol;
	}

	if ($config->currency_position == 0)
	{
		echo $symbol . number_format($item->price, $numberDecimals, $dec_point, $thousands_sep) . ($subscriptionLengthText ? "<sub>/$subscriptionLengthText</sub>" : '');
	}
	else
	{
		echo number_format($item->price, $numberDecimals, $dec_point, $thousands_sep) . $symbol . ($subscriptionLengthText ? "<sub>/$subscriptionLengthText</sub>" : '');
	}
}
else
{
	echo Text::_('OSM_FREE') . ($subscriptionLengthText ? "<sub> /$subscriptionLengthText</sub>" : '');
}



