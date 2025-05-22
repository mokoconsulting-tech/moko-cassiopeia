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

$categoryId = $this->category ? $this->category->id : 0;
?>
<div id="osm-plans-list-columns" class="osm-container osm-container-j4 osm-pricingtable-container<?php echo $categoryId; ?>">
	<?php
		if ($this->params->get('show_page_heading', 1))
		{
			if ($this->category)
			{
				$pageHeading = $this->params->get('page_heading') ?: $this->category->title;
			}
			else
			{
				$pageHeading = $this->params->get('page_heading') ?: Text::_('OSM_SUBSCRIPTION_PLANS');
			}

			if ($this->input->getInt('hmvc_call'))
			{
				$hTag = 'h2';
			}
			else
			{
				$hTag = 'h1';
			}
		?>
			<<?php echo $hTag; ?> class="osm-page-title"><?php echo $pageHeading; ?></<?php echo $hTag; ?>>
		<?php
		}

		if (!empty($this->category->description))
		{
			$description = $this->category->description;
		}
		elseif (OSMembershipHelper::isValidMessage($this->params->get('intro_text')))
		{
			$description = $this->params->get('intro_text');
		}
		else
		{
			$description = '';
		}

		if ($description)
		{
		?>
			<div class="osm-description osm-page-intro-text <?php echo $this->bootstrapHelper->getClassMapping('clearfix'); ?>">
				<?php echo HTMLHelper::_('content.prepare', $description); ?>
			</div>
		<?php
		}

		if (count($this->categories))
		{
			echo OSMembershipHelperHtml::loadCommonLayout('common/tmpl/categories.php', ['items' => $this->categories, 'categoryId' => $this->categoryId, 'config' => $this->config, 'Itemid' => $this->Itemid]);
		}

		if (count($this->items))
		{
			echo OSMembershipHelperHtml::loadCommonLayout('common/tmpl/pricingtable_plans.php', ['items' => $this->items, 'input' => $this->input, 'config' => $this->config, 'Itemid' => $this->Itemid, 'categoryId' => $this->categoryId, 'bootstrapHelper' => $this->bootstrapHelper, 'params' => $this->params]);
		}
	?>
</div>