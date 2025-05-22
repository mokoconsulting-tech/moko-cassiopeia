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
 * @var array                       $items
 * @var int                         $categoryId
 * @var MPFInput                    $input
 * @var MPFConfig                   $config
 * @var OSMembershipHelperBootstrap $bootstrapHelper
 * @var \Joomla\Registry\Registry   $params
 * @var int                         $Itemid
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$rootUri = Uri::root(true);

$subscribedPlanIds = OSMembershipHelperSubscription::getSubscribedPlans();

if (!isset($categoryId))
{
	$categoryId = 0;
}

$rowFluidClass   = $bootstrapHelper->getClassMapping('row-fluid');
$imgClass        = $bootstrapHelper->getClassMapping('img-polaroid');
$btnClass        = $bootstrapHelper->getClassMapping('btn');
$btnPrimaryClass = $bootstrapHelper->getClassMapping('btn btn-primary');
$clearfixClass   = $bootstrapHelper->getClassMapping('clearfix');

$defaultItemId = $Itemid;

if (isset($params))
{
	$showPlanInformation     = $params->get('show_plan_information', 1);
	$planInformationPosition = $params->get('plan_information_position', 0);
}
else
{
	$showPlanInformation     = 1;
	$planInformationPosition = 0;
}

if ($showPlanInformation && $planInformationPosition == 0)
{
	$leftClass  = $bootstrapHelper->getClassMapping('span7');
	$rightClass = $bootstrapHelper->getClassMapping('span5');
}
else
{
	$leftClass  = $bootstrapHelper->getClassMapping('clearfix');
	$rightClass = $bootstrapHelper->getClassMapping('clearfix');
}

for ($i = 0 , $n = count($items) ;  $i < $n ; $i++)
{
	$item   = $items[$i];
	$Itemid = OSMembershipHelperRoute::getPlanMenuId($item->id, $item->category_id, $defaultItemId);

	if ($item->thumb)
	{
		$imgSrc = $rootUri . '/media/com_osmembership/' . $item->thumb;
	}

	if ($item->category_id)
	{
		$url = Route::_('index.php?option=com_osmembership&view=plan&catid=' . $item->category_id . '&id=' . $item->id . '&Itemid=' . $Itemid);
	}
	else
	{
		$url = Route::_('index.php?option=com_osmembership&view=plan&id=' . $item->id . '&Itemid=' . $Itemid);
	}

	if ($config->use_https)
	{
		$signUpUrl = Route::_(OSMembershipHelperRoute::getSignupRoute($item->id, $Itemid), false, 1);
	}
	else
	{
		$signUpUrl = Route::_(OSMembershipHelperRoute::getSignupRoute($item->id, $Itemid));
	}
	?>
	<div class="osm-item-wrapper <?php echo $clearfixClass; ?>">
		<div class="osm-item-heading-box <?php echo $clearfixClass; ?>">
			<h2 class="osm-item-title">
				<a href="<?php echo $url; ?>" title="<?php echo $item->title; ?>">
					<?php echo $item->title; ?>
				</a>
			</h2>
		</div>
		<div class="osm-item-description <?php echo $clearfixClass; ?>">
			<div class="<?php echo $rowFluidClass; ?>">
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
				<div class="osm-description-details <?php echo $leftClass; ?>">
					<?php
					if ($item->thumb)
					{
					?>
						<img src="<?php echo $imgSrc; ?>" alt="<?php echo $item->title; ?>" class="osm-thumb-left <?php echo $imgClass; ?>"/>
					<?php
					}

					if ($item->short_description)
					{
						echo $item->short_description;
					}
					else
					{
						echo $item->description;
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
                                <a href="<?php echo $signUpUrl; ?>" class="<?php echo $btnPrimaryClass; ?>">
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
								$link = Route::_('index.php?option=com_osmembership&view=upgrademembership&to_plan_id=' . $item->id . '&Itemid=' . OSMembershipHelperRoute::findView('upgrademembership', $Itemid));
							}
							else
							{
								$upgradeOptionId = $item->upgrade_rules[0]->id;
								$link            = Route::_('index.php?option=com_osmembership&task=register.process_upgrade_membership&upgrade_option_id=' . $upgradeOptionId . '&Itemid=' . $Itemid);
							}
							?>
                            <li>
                                <a href="<?php echo $link; ?>" class="<?php echo $btnPrimaryClass; ?>">
									<?php echo Text::_($upgradeLanguageItem); ?>
                                </a>
                            </li>
							<?php
						}
					}

					if (empty($config->hide_details_button))
					{
					?>
						<li>
							<a href="<?php echo $url; ?>" class="<?php echo $btnClass; ?>">
								<?php echo Text::_('OSM_DETAILS'); ?>
							</a>
						</li>
					<?php
					}
					?>
				</ul>
			</div>
		</div>
	</div>
<?php
}
