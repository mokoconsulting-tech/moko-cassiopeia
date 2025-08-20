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
use Joomla\CMS\Router\Route;

?>
<div class="<?php echo $this->bootstrapHelper->getClassMapping('row-fluid clearfix'); ?>">
	<?php
	if (count($this->renewOptions))
	{
	?>
		<form action="<?php echo Route::_('index.php?option=com_osmembership&task=register.process_renew_membership&Itemid=' . $this->Itemid, false, $ssl); ?>" method="post" name="osm_form_renew" id="osm_form_renew" autocomplete="off" class="<?php echo $this->bootstrapHelper->getClassMapping('form form-horizontal'); ?>">
			<h2 class="osm-form-heading"><?php echo Text::_('OSM_RENEW_MEMBERSHIP'); ?></h2>
			<?php echo $this->loadCommonLayout('common/tmpl/renew_options.php');?>
		</form>
	<?php
	}

	if (count($this->upgradeRules))
	{
	?>
		<form action="<?php echo Route::_('index.php?option=com_osmembership&task=register.process_upgrade_membership&Itemid=' . $this->Itemid, false, $ssl); ?>" method="post" name="osm_form_update_membership" id="osm_form_update_membership" autocomplete="off" class="<?php echo $this->bootstrapHelper->getClassMapping('form form-horizontal'); ?>">
			<h2 class="osm-form-heading"><?php echo Text::_('OSM_UPGRADE_MEMBERSHIP'); ?></h2>
			<?php
			echo $this->loadCommonLayout('common/tmpl/upgrade_options.php');
			?>
			<div class="form-actions">
				<input type="submit" class="<?php echo $this->bootstrapHelper->getClassMapping('btn btn-primary'); ?>" value="<?php echo Text::_('OSM_PROCESS_UPGRADE'); ?>"/>
			</div>
		</form>
	<?php
	}
	?>
</div>

