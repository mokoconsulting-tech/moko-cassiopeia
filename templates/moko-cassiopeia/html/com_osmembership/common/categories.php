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
use Joomla\CMS\Router\Route;

/**
 * Layout variables
 *
 * @var array     $items
 * @var MPFConfig $config
 * @var int       $categoryId
 * @var int       $Itemid
 */

$bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
$clearfixClass   = $bootstrapHelper->getClassMapping('clearfix');

for ($i = 0 , $n = count($items) ; $i < $n ; $i++)
{
	$item = $items[$i];
	$link = Route::_(OSMembershipHelperRoute::getCategoryRoute($item->id, $Itemid));
	?>
	<div class="osm-item-wrapper clearfix">
		<div class="osm-item-heading-box">
			<h3 class="osm-item-title">
				<a href="<?php echo $link; ?>" class="osm-item-title-link">
					<?php echo $item->title;?>
				</a>
				<span class="<?php echo $bootstrapHelper->getClassMapping('badge badge-info'); ?>"><?php echo $item->total_plans ;?> <?php echo $item->total_plans > 1 ? Text::_('OSM_PLANS') :  Text::_('OSM_PLAN') ; ?></span>
			</h3>
		</div>
		<?php
		if($item->description)
		{
		?>
			<div class="osm-item-description <?php echo $clearfixClass; ?>">
				<?php echo HTMLHelper::_('content.prepare', $item->description);?>
			</div>
		<?php
		}
		?>
	</div>
<?php
}

