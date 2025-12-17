<?php

/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2025 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

/**
 * Layout variables
 *
 * @var stdClass $item
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
$config          = OSMembershipHelper::getConfig();
$symbol          = $item->currency_symbol ?: $item->currency;
?>
<table class="<?php echo $bootstrapHelper->getClassMapping('table table-striped table-bordered'); ?>">
	<?php
	if ($item->setup_fee > 0)
	{
	?>
        <tr class="osm-plan-property">
            <td class="osm-plan-property-label">
				<?php echo Text::_('OSM_SETUP_FEE'); ?>:
            </td>
            <td class="osm-plan-property-value">
	            <?php echo OSMembershipHelper::formatCurrency($item->setup_fee, $config, $symbol); ?>
            </td>
        </tr>
	<?php
	}

	if ($item->recurring_subscription && $item->trial_duration)
	{
	?>
        <tr class="osm-plan-property">
            <td class="osm-plan-property-label">
				<?php echo Text::_('OSM_TRIAL_DURATION'); ?>:
            </td>
            <td class="osm-plan-property-value">
				<?php
				if ($item->lifetime_membership)
				{
					echo Text::_('OSM_LIFETIME');
				}
				else
				{
					echo OSMembershipHelperSubscription::getDurationText($item->trial_duration, $item->trial_duration_unit);
				}
				?>
            </td>
        </tr>

        <tr class="osm-plan-property">
            <td class="osm-plan-property-label">
				<?php echo Text::_('OSM_TRIAL_PRICE'); ?>:
            </td>
            <td class="osm-plan-property-value">
				<?php
				if ($item->trial_amount > 0)
				{
					echo OSMembershipHelper::formatCurrency($item->trial_amount, $config, $symbol);
				}
				else
				{
					echo Text::_('OSM_FREE');
				}
				?>
            </td>
        </tr>
	<?php
	}

	if (!((int) $item->expired_date))
	{
	?>
        <tr class="osm-plan-property">
            <td class="osm-plan-property-label">
				<?php echo Text::_('OSM_DURATION'); ?>:
            </td>
            <td class="osm-plan-property-value">
				<?php
				if ($item->lifetime_membership)
				{
					echo Text::_('OSM_LIFETIME');
				}
				else
				{
					echo OSMembershipHelperSubscription::getDurationText($item->subscription_length, $item->subscription_length_unit);
				}
				?>
            </td>
        </tr>
	<?php
	}
	?>
    <tr class="osm-plan-property">
        <td class="osm-plan-property-label">
			<?php echo Text::_('OSM_PRICE'); ?>:
        </td>
        <td class="osm-plan-property-value">
			<?php
			if ($item->price > 0)
			{
				echo OSMembershipHelper::formatCurrency($item->price, $config, $symbol);
			}
			else
			{
				echo Text::_('OSM_FREE');
			}
			?>
        </td>
    </tr>
	<?php
	if (file_exists(JPATH_ROOT . '/components/com_osmembership/fields.xml')
		&& filesize(JPATH_ROOT . '/components/com_osmembership/fields.xml'))
	{
		echo OSMembershipHelperHtml::loadCommonLayout('common/tmpl/plan_custom_fields.php', ['item' => $item]);
	}
	?>
</table>

