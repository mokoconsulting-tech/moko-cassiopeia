<?php

/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2025 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

?>
<ul class="osm-renew-options">
	<?php
	$userId           = Factory::getApplication()->getIdentity()->id;
	$renewOptionCount = 0;
	$fieldSuffix      = OSMembershipHelper::getFieldSuffix();

	foreach ($this->planIds as $planId)
	{
		$plan    = $this->plans[$planId];
		$taxRate = 0;

		if ($this->config->show_price_including_tax && !$this->config->setup_price_including_tax)
		{
			$taxRate = OSMembershipHelper::calculateMaxTaxRate($planId);
		}

		$symbol = $plan->currency_symbol ?: $plan->currency;
		$renewOptions = $this->renewOptions[$planId] ?? [];

		if (count($renewOptions))
		{
			foreach ($renewOptions as $renewOption)
			{
				$checked = '';

				if ($renewOptionCount == 0)
				{
					$checked = ' checked="checked" ';
				}

				$renewOptionCount++;
				$renewOptionLengthText = OSMembershipHelperSubscription::getDurationText($renewOption->renew_option_length, $renewOption->renew_option_length_unit);

				$renewOptionText = Text::sprintf('OSM_RENEW_OPTION_TEXT', $plan->title, $renewOptionLengthText, OSMembershipHelper::formatCurrency($renewOption->price * (1 + $taxRate / 100), $this->config, $symbol));

				if (strpos($renewOptionText, '[EXPIRED_DATE]'))
				{
					$expiredDate = OSMembershipHelperSubscription::getPlanExpiredDate($planId);

					if ($expiredDate)
					{
						$expiredDate = HTMLHelper::_('date', $expiredDate, $this->config->date_format);
					}

					$renewOptionText = str_replace('[EXPIRED_DATE]', $expiredDate, $renewOptionText);
				}
				?>
				<li class="osm-renew-option">
					<input type="radio" class="validate[required]<?php echo $this->bootstrapHelper->getFrameworkClass('uk-radio', 1); ?>" id="renew_option_id_<?php echo $renewOptionCount; ?>" name="renew_option_id" value="<?php echo $planId . '|' . $renewOption->id; ?>" <?php echo $checked; ?> />
					<label for="renew_option_id_<?php echo $renewOptionCount; ?>"><?php echo $renewOptionText; ?></label>
				</li>
				<?php
			}
		}
		else
		{
			$checked = '';

			if ($renewOptionCount == 0)
			{
				$checked = ' checked="checked" ';
			}

			$renewOptionCount++;
			$subscriptionLengthText = OSMembershipHelperSubscription::getDurationText($plan->subscription_length, $plan->subscription_length_unit);

			$renewalDiscountRule = OSMembershipHelperSubscription::getRenewalDiscount($userId, $planId);

			if ($renewalDiscountRule)
			{
				if ($renewalDiscountRule->discount_type == 0)
				{
					$plan->price = round($plan->price * (1 - $renewalDiscountRule->discount_amount / 100), 2);
				}
				else
				{
					$plan->price = $plan->price - $renewalDiscountRule->discount_amount;
				}

				if ($plan->price < 0)
				{
					$plan->price = 0;
				}
			}

			$renewOptionText = Text::sprintf('OSM_RENEW_OPTION_TEXT', $plan->title, $subscriptionLengthText, OSMembershipHelper::formatCurrency($plan->price * (1 + $taxRate / 100), $this->config, $symbol));

			if (strpos($renewOptionText, '[EXPIRED_DATE]'))
			{
				$expiredDate = OSMembershipHelperSubscription::getPlanExpiredDate($plan->id);

				if ($expiredDate)
				{
					$expiredDate = HTMLHelper::_('date', $expiredDate, $this->config->date_format);
				}

				$renewOptionText = str_replace('[EXPIRED_DATE]', $expiredDate, $renewOptionText);
			}
			?>
			<li class="osm-renew-option">
				<input type="radio" class="validate[required]<?php echo $this->bootstrapHelper->getFrameworkClass('uk-radio', 1); ?>" id="renew_option_id_<?php echo $renewOptionCount; ?>" name="renew_option_id" value="<?php echo $planId;?>" <?php echo $checked; ?>/>
				<label for="renew_option_id_<?php echo $renewOptionCount; ?>"><?php echo $renewOptionText; ?></label>
			</li>
			<?php
		}
	}
	?>
</ul>
<div class="form-actions">
	<input type="submit" class="<?php echo $this->bootstrapHelper->getClassMapping('btn btn-primary'); ?>" value="<?php echo Text::_('OSM_PROCESS_RENEW'); ?>"/>
</div>

