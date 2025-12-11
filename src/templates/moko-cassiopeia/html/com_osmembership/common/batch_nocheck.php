<?php

/**
 * @package            Joomla
 * @subpackage         Membership Pro
 * @author             Tuan Pham Ngoc
 * @copyright          Copyright (C) 2010 - 2022 Ossolution Team
 * @license            GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 *
 * @var string $selector
 * @var string $title
 */
?>
<button type="button" data-toggle="modal" onclick="jQuery( '#<?php echo $selector; ?>' ).modal('show'); return true;" class="btn btn-small">
	<span class="icon-checkbox-partial" aria-hidden="true"></span>
	<?php echo $title; ?>
</button>

