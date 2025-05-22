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
			<?php echo  Text::_('OSM_SEND_FIRST_REMINDER'); ?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
            <input type="number" class="form-control input-mini d-inline-block" name="send_first_reminder" value="<?php echo $this->item->send_first_reminder; ?>" /><span><?php echo ' ' . Text::_('OSM_DAYS') . ' ' . $this->lists['send_first_reminder_time']; ?></span><?php echo Text::_('OSM_SUBSCRIPTION_EXPIRED'); ?>
        </div>
    </div>
    <div class="<?php echo $controlGroupClass; ?>">
        <div class="<?php echo $controlLabelClass; ?>">
			<?php echo  Text::_('OSM_SEND_SECOND_REMINDER'); ?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
            <input type="number" class="form-control input-mini d-inline-block" name="send_second_reminder" value="<?php echo $this->item->send_second_reminder; ?>" /><span><?php echo ' ' . Text::_('OSM_DAYS') . ' ' . $this->lists['send_second_reminder_time']; ?></span><?php echo Text::_('OSM_SUBSCRIPTION_EXPIRED'); ?>
        </div>
    </div>
    <div class="<?php echo $controlGroupClass; ?>">
        <div class="<?php echo $controlLabelClass; ?>">
			<?php echo  Text::_('OSM_SEND_THIRD_REMINDER'); ?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
            <input type="number" class="form-control input-mini d-inline-block" name="send_third_reminder" value="<?php echo $this->item->send_third_reminder; ?>" /><span><?php echo ' ' . Text::_('OSM_DAYS') . ' ' . $this->lists['send_third_reminder_time']; ?></span><?php echo Text::_('OSM_SUBSCRIPTION_EXPIRED'); ?>
        </div>
    </div>
    <?php
		if ($this->item->number_payments > 0)
		{
		?>
            <div class="<?php echo $controlGroupClass; ?>">
                <div class="<?php echo $controlLabelClass; ?>">
			        <?php echo  Text::_('OSM_SEND_SUBSCRIPTION_END'); ?>
                </div>
                <div class="<?php echo $controlsClass; ?>">
                    <input type="number" class="form-control input-mini d-inline-block" name="send_subscription_end" value="<?php echo $this->item->send_subscription_end; ?>" /><span><?php echo ' ' . Text::_('OSM_DAYS') . ' ' . $this->lists['send_subscription_end_time']; ?></span><?php echo Text::_('OSM_SUBSCRIPTION_EXPIRED'); ?>
                </div>
            </div>
        <?php
		}
	?>
</fieldset>
