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
<fieldset class="adminform">
	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_IS_RECURRING_SUBSCRIPTION'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<?php echo $this->lists['recurring_subscription']; ?>
		</div>
	</div>
	<div class="<?php echo $controlGroupClass; ?>" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(['recurring_subscription' => '1']); ?>'>
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_TRIAL_AMOUNT'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<input type="text" class="form-control" name="trial_amount" value="<?php echo $this->item->trial_amount; ?>" />
		</div>
	</div>
	<div class="<?php echo $controlGroupClass; ?>" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(['recurring_subscription' => '1']); ?>'>
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_TRIAL_DURATION'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<input type="text" class="form-control input-mini d-inline-block" name="trial_duration" value="<?php echo $this->item->trial_duration > 0 ? $this->item->trial_duration : ''; ?>"/>
			<?php echo $this->lists['trial_duration_unit']; ?>
		</div>
	</div>
	<div class="<?php echo $controlGroupClass; ?>" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(['recurring_subscription' => '1']); ?>'>
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_NUMBER_PAYMENTS'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<input type="text" class="form-control" name="number_payments" value="<?php echo $this->item->number_payments; ?>" />
		</div>
	</div>

    <?php
		if ($this->item->number_payments > 0)
		{
		?>
            <div class="<?php echo $controlGroupClass; ?>">
                <div class="<?php echo $controlLabelClass; ?>">
			        <?php echo OSMembershipHelperHtml::getFieldLabel('last_payment_action', Text::_('OSM_AFTER_LAST_PAYMENT_ACTION'), Text::_('OSM_AFTER_LAST_PAYMENT_ACTION_EXPLAIN')); ?>
                </div>
                <div class="<?php echo $controlsClass; ?>">
			        <?php echo $this->lists['last_payment_action']; ?>
                </div>
            </div>
            <div class="<?php echo $controlGroupClass; ?>" data-showon='<?php echo OSMembershipHelperHtml::renderShowon(['last_payment_action' => '2']); ?>'>
                <div class="<?php echo $controlLabelClass; ?>">
			        <?php echo Text::_('OSM_EXTEND_DURATION'); ?>
                </div>
                <div class="<?php echo $controlsClass; ?>">
                    <input type="text" class="input-mini" name="extend_duration" value="<?php echo $this->item->extend_duration > 0 ? $this->item->extend_duration : ''; ?>"/>
			        <?php echo $this->lists['extend_duration_unit']; ?>
                </div>
            </div>
        <?php
		}
	?>
</fieldset>

