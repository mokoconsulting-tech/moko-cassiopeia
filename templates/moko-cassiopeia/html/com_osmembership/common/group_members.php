<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2025 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 *
 * @var array $rowMembers
 */

$names = [];

foreach ($rowMembers as $rowMember)
{
	$names[] = trim($rowMember->first_name . ' ' . $rowMember->last_name);
}

echo implode("\r\n", $names);
