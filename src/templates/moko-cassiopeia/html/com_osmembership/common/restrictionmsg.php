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
 * @var string   $introText
 * @var string   $msg
 * @var string   $context
 * @var stdClass $row
 */

if (isset($introText))
{
	echo '<div class="intro-text">' . $introText . '</div>';
}
?>
<div class="text-info">
	<?php echo $msg; ?>
</div>


