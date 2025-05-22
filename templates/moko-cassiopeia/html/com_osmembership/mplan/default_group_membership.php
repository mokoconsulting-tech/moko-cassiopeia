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

$bootstrapHelper   = OSMembershipHelperBootstrap::getInstance();
$rowFluidClasss    = $bootstrapHelper->getClassMapping('row-fluid');
$controlGroupClass = $bootstrapHelper->getClassMapping('control-group');
$controlLabelClass = $bootstrapHelper->getClassMapping('control-label');
$controlsClass     = $bootstrapHelper->getClassMapping('controls');
?>
<div class="<?php echo $controlGroupClass; ?>">
	<div class="<?php echo $controlLabelClass; ?>">
		<?php echo OSMembershipHelperHtml::getFieldLabel('number_members_type', Text::_('OSM_NUMBER_MEMBER_TYPES'), Text::_('OSM_NUMBER_MEMBER_TYPES_EXPLAIN')); ?>
	</div>
	<div class="<?php echo $controlsClass; ?>">
		<?php echo $this->lists['number_members_type']; ?>
	</div>
</div>
<div class="<?php echo $controlGroupClass; ?>" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(['number_members_type' => '0']); ?>'>
	<div class="<?php echo $controlLabelClass; ?>">
		<?php echo OSMembershipHelperHtml::getFieldLabel('number_group_members', Text::_('PLG_GRM_MAX_NUMBER_MEMBERS'), Text::_('PLG_GRM_MAX_NUMBER_MEMBERS_EXPLAIN')); ?>
	</div>
	<div class="<?php echo $controlsClass; ?>">
		<input type="number" class="form-control input-small" name="number_group_members" id="number_group_members" value="<?php echo $this->item->number_group_members; ?>" />
	</div>
</div>
<div class="<?php echo $controlGroupClass; ?>" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(['number_members_type' => '1']); ?>'>
	<div class="<?php echo $controlLabelClass; ?>">
		<?php echo OSMembershipHelperHtml::getFieldLabel('number_members_field', Text::_('OSM_NUMBER_MEMBERS_FIELD'), Text::_('OSM_NUMBER_MEMBERS_FIELD_EXPLAIN')); ?>
	</div>
	<div class="<?php echo $controlsClass; ?>">
		<?php echo $this->lists['number_members_field']; ?>
	</div>
</div>
