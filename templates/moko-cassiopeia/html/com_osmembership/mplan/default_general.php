<?php

/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2025 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$bootstrapHelper   = OSMembershipHelperBootstrap::getInstance();
$rowFluidClasss    = $bootstrapHelper->getClassMapping('row-fluid');
$controlGroupClass = $bootstrapHelper->getClassMapping('control-group');
$controlLabelClass = $bootstrapHelper->getClassMapping('control-label');
$controlsClass     = $bootstrapHelper->getClassMapping('controls');
?>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo  Text::_('OSM_TITLE'); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <input class="form-control input-xxlarge" type="text" name="title" id="title" maxlength="250" value="<?php echo $this->item->title;?>" />
    </div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo  Text::_('OSM_ALIAS'); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <input class="form-control input-xxlarge" type="text" name="alias" id="alias" maxlength="250" value="<?php echo $this->item->alias;?>" />
    </div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo  Text::_('OSM_CATEGORY'); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <?php echo $this->lists['category_id']; ?>
    </div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo  Text::_('OSM_PRICE'); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <input class="form-control" type="number" name="price" id="price" maxlength="250" value="<?php echo $this->item->price;?>" step="0.01" />
    </div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo  Text::_('OSM_SUBSCRIPTION_LENGTH'); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <input class="form-control input-small d-inline-block" type="number" min="1" name="subscription_length" id="subscription_length" maxlength="250" value="<?php echo $this->item->subscription_length;?>" /><?php echo $this->lists['subscription_length_unit']; ?>
    </div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo Text::_('OSM_EXPIRED_DATE'); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <?php echo HTMLHelper::_('calendar', $this->item->expired_date, 'expired_date', 'expired_date', $this->datePickerFormat) ; ?>
    </div>
</div>
<?php
if ((int)$this->item->expired_date)
{
?>
    <div class="<?php echo $controlGroupClass; ?>">
        <div class="<?php echo $controlLabelClass; ?>">
            <?php echo Text::_('OSM_PRORATED_SIGNUP_COST');?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
            <?php echo $this->lists['prorated_signup_cost'];?>
        </div>
    </div>
    <div class="<?php echo $controlGroupClass; ?>">
        <div class="<?php echo $controlLabelClass; ?>">
            <?php echo OSMembershipHelperHtml::getFieldLabel('grace_period', Text::_('OSM_OVERLAP_PERIOD'), Text::_('OSM_OVERLAP_PERIOD_EXPLAIN')); ?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
            <input class="input-small form-control" type="number" name="grace_period" id="grace_period" maxlength="250" value="<?php echo $this->item->grace_period;?>" /><?php echo ' ' . Text::_('OSM_DAYS'); ?>
        </div>
    </div>
<?php
}
?>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo Text::_('OSM_LIFETIME_MEMBERSHIP');?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <?php echo $this->lists['lifetime_membership'];?>
    </div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo  Text::_('OSM_THUMB'); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <input type="file" class="form-control" name="thumb_image" size="60" />
        <?php
		if ($this->item->thumb)
		{
		?>
            <img src="<?php echo Uri::root() . 'media/com_osmembership/' . $this->item->thumb; ?>" class="img_preview" />
            <input type="checkbox" name="del_thumb" value="1" /><?php echo Text::_('OSM_DELETE_CURRENT_THUMB'); ?>
        <?php
		}
		?>
    </div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo  Text::_('OSM_ENABLE_RENEWAL'); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <?php echo $this->lists['enable_renewal']; ?>
    </div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo Text::_('OSM_ACCESS'); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <?php echo $this->lists['access']; ?>
    </div>
</div>
<?php
	if (isset($this->lists['published']))
	{
	?>
        <div class="<?php echo $controlGroupClass; ?>">
            <div class="<?php echo $controlLabelClass; ?>">
			    <?php echo Text::_('OSM_PUBLISHED'); ?>
            </div>
            <div class="<?php echo $controlsClass; ?>">
			    <?php echo $this->lists['published']; ?>
            </div>
        </div>
    <?php
	}
?>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo Text::_('OSM_SHORT_DESCRIPTION'); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <?php echo $editor->display('short_description', $this->item->short_description, '100%', '250', '75', '10') ; ?>
    </div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo Text::_('OSM_DESCRIPTION'); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <?php echo $editor->display('description', $this->item->description, '100%', '250', '75', '10') ; ?>
    </div>
</div>

