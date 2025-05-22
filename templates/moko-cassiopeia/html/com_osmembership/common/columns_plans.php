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

Factory::getApplication()
	->getDocument()
	->getWebAssetManager()
	->useScript('core');

$rootUri = Uri::root(true);
$minHeight = 130;

if (isset($params))
{
	$minHeight = (int) $params->get('min_height', 130) ?: 130;
}

OSMembershipHelperJquery::responsiveEqualHeight('.osm-item-description-text', $minHeight);

$subscribedPlanIds = OSMembershipHelperSubscription::getSubscribedPlans();

if (isset($input) && $input->getInt('number_columns'))
{
	$numberColumns = $input->getInt('number_columns');
}
elseif (!empty($config->number_columns))
{
	$numberColumns = $config->number_columns;
}
else
{
	$numberColumns = 3;
}

if (!isset($categoryId))
{
	$categoryId = 0;
}

$span = intval(12 / $numberColumns);

$btnClass              = $bootstrapHelper->getClassMapping('btn');
$btnPrimaryClass       = $bootstrapHelper->getClassMapping('btn btn-primary');
$imgClass              = $bootstrapHelper->getClassMapping('img-polaroid');
$spanClass             = $bootstrapHelper->getClassMapping('span' . $span);
$rowFluidClearfixClass = $bootstrapHelper->getClassMapping('row-fluid clearfix');
$clearFixClass         = $bootstrapHelper->getClassMapping('clearfix');
?>
<div class="<?php echo $rowFluidClearfixClass; ?>">
<?php
$i = 0;
$numberPlans = count($items);
$defaultItemId = $Itemid;

foreach ($items as $item)
{
	$i++;

	$Itemid = OSMembershipHelperRoute::getPlanMenuId($item->id, $item->category_id, $defaultItemId);

	if ($item->thumb)
	{
		$imgSrc = $rootUri . '/media/com_osmembership/' . $item->thumb;
	}

	$url = Route::_('index.php?option=com_osmembership&view=plan&catid=' . $item->category_id . '&id=' . $item->id . '&Itemid=' . $Itemid);

	if ($config->use_https)
	{
		$signUpUrl = Route::_(OSMembershipHelperRoute::getSignupRoute($item->id, $Itemid), false, 1);
	}
	else
	{
		$signUpUrl = Route::_(OSMembershipHelperRoute::getSignupRoute($item->id, $Itemid));
	}
	?>
	<div class="osm-item-wrapper <?php echo $spanClass; ?>">
		<div class="osm-item-heading-box <?php echo $clearFixClass; ?>">
			<h2 class="osm-item-title">
				<a href="<?php echo $url; ?>" title="<?php echo $item->title; ?>">
					<?php echo $item->title; ?>
				</a>
			</h2>
		</div>
		<div class="osm-item-description <?php echo $clearFixClass; ?>">
				<?php
				if ($item->thumb)
				{
				?>
				<a href="<?php echo $url; ?>" title="<?php echo $item->title; ?>">	
					<img src="<?php echo $imgSrc; ?>" class="osm-thumb-left <?php echo $imgClass; ?>" />
				</a>	
				<?php
				}

				if (!$item->short_description)
				{
					$item->short_description = $item->description;
				}
				?>
				<div class="osm-item-description-text"><?php echo $item->short_description; ?></div>
				 <div class="osm-taskbar <?php echo $clearFixClass; ?>">
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
	if ($i % $numberColumns == 0 && $i < $numberPlans)
	{
	?>
		</div>
        <div class="<?php echo $rowFluidClearfixClass; ?>">
	<?php
	}
}
?>
</div>