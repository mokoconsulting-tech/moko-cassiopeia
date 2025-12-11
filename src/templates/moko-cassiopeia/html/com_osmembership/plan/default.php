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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$item = $this->item;

$clearfixClass = $this->bootstrapHelper->getClassMapping('clearfix');

if ($item->thumb)
{
	$imgSrc = Uri::base() . 'media/com_osmembership/' . $item->thumb;
}

if ($this->config->use_https)
{
	$signUpUrl = Route::_(OSMembershipHelperRoute::getSignupRoute($item->id, $this->Itemid), false, 1);
}
else
{
	$signUpUrl = Route::_(OSMembershipHelperRoute::getSignupRoute($item->id, $this->Itemid));
}

$subscribedPlanIds = OSMembershipHelperSubscription::getSubscribedPlans();

$showPlanInformation     = $this->params->get('show_plan_information', 1);
$planInformationPosition = $this->params->get('plan_information_position', 0);

if ($showPlanInformation && $planInformationPosition == 0)
{
	$leftClass  = $this->bootstrapHelper->getClassMapping('span7');
	$rightClass = $this->bootstrapHelper->getClassMapping('span5');
}
else
{
	$leftClass  = $this->bootstrapHelper->getClassMapping('clearfix');
	$rightClass = $this->bootstrapHelper->getClassMapping('clearfix');
}
?>
<div id="osm-plan-item" class="osm-container">
	<div class="osm-item-heading-box <?php echo $clearfixClass; ?>">
		<h1 class="osm-page-title">
			<?php echo $this->params->get('page_heading'); ?>
		</h1>
	</div>
	<div class="osm-item-description <?php echo $clearfixClass; ?>">
			<div class="<?php echo $this->bootstrapHelper->getClassMapping('row-fluid clearfix'); ?>">
				<?php
				if ($showPlanInformation && $planInformationPosition == 1)
				{
				?>
					<div class="<?php echo $rightClass; ?>">
						<?php echo OSMembershipHelperHtml::loadCommonLayout('common/tmpl/plan_information.php', ['item' => $item]); ?>
					</div>
				<?php
				}
				?>
				<div class="osm-description-details <?php echo $leftClass; ?> ">
					<?php
					if ($item->thumb)
					{
					?>
						<img src="<?php echo $imgSrc; ?>" alt="<?php echo $item->title; ?>" class="osm-thumb-left img-polaroid"/>
					<?php
					}

					if ($item->description)
					{
						echo $item->description;
					}
					else
					{
						echo $item->short_description;
					}
					?>
				</div>
                <?php
					if ($showPlanInformation && in_array($planInformationPosition, [0, 2]))
					{
					?>
                        <div class="<?php echo $rightClass; ?>">
		                    <?php echo OSMembershipHelperHtml::loadCommonLayout('common/tmpl/plan_information.php', ['item' => $item]); ?>
                        </div>
                    <?php
					}
				?>
			</div>

        <?php
			if (count($this->renewOptions) || count($this->upgradeRules))
			{
				echo $this->loadTemplate('renew_upgrade');
			}
		?>
			<div class="osm-taskbar <?php echo $clearfixClass; ?>">
				<ul>
					<?php
					$actions = OSMembershipHelperSubscription::getAllowedActions($item);

					if (count($actions))
					{
						$language = Factory::getApplication()->getLanguage();

						if (in_array('subscribe', $actions))
						{
							if ($language->hasKey('OSM_SIGNUP_PLAN_' . $item->id))
							{
								$signUpLanguageItem = 'OSM_SIGNUP_PLAN_' . $item->id;
							}
							else
							{
								$signUpLanguageItem = 'OSM_SIGNUP';
							}

							if ($language->hasKey('OSM_RENEW_PLAN_' . $item->id))
							{
								$renewLanguageItem = 'OSM_RENEW_PLAN_' . $item->id;
							}
							else
							{
								$renewLanguageItem = 'OSM_RENEW';
							}
						?>
                            <li>
                                <a href="<?php echo $signUpUrl; ?>" class="<?php echo $this->bootstrapHelper->getClassMapping('btn btn-primary'); ?>">
			                        <?php echo in_array($item->id, $subscribedPlanIds) ? Text::_($renewLanguageItem) : Text::_($signUpLanguageItem); ?>
                                </a>
                            </li>
	                    <?php
						}

						if (in_array('upgrade', $actions))
						{
							if ($language->hasKey('OSM_UPGRADE_PLAN_' . $item->id))
							{
								$upgradeLanguageItem = 'OSM_UPGRADE_PLAN_' . $item->id;
							}
							else
							{
								$upgradeLanguageItem = 'OSM_UPGRADE';
							}

							if (count($item->upgrade_rules) > 1)
							{
								$link = Route::_('index.php?option=com_osmembership&view=upgrademembership&to_plan_id=' . $item->id . '&Itemid=' . OSMembershipHelperRoute::findView('upgrademembership', $this->Itemid));
							}
							else
							{
								$upgradeOptionId = $item->upgrade_rules[0]->id;
								$link            = Route::_('index.php?option=com_osmembership&task=register.process_upgrade_membership&upgrade_option_id=' . $upgradeOptionId . '&Itemid=' . $this->Itemid);
							}
							?>
                            <li>
                                <a href="<?php echo $link; ?>" class="<?php echo $this->bootstrapHelper->getClassMapping('btn btn-primary'); ?>">
									<?php echo Text::_($upgradeLanguageItem); ?>
                                </a>
                            </li>
							<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
</div>
