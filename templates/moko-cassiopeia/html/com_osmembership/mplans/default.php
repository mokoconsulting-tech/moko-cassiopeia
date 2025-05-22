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
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('formbehavior.chosen', 'select');

$bootstrapHelper = OSMembershipHelperBootstrap::getInstance();
$centerClass     = $bootstrapHelper->getClassMapping('center');
$cols            = 10;
$config          = OSMembershipHelper::getConfig();
?>
<div id="osm-manage-plans" class="osm-container osm-container-j4">
	<?php
	if ($this->params->get('show_page_heading', 1))
	{
		if ($this->input->getInt('hmvc_call'))
		{
			$hTag = 'h2';
		}
		else
		{
			$hTag = 'h1';
		}
	?>
		<<?php echo $hTag; ?> class="osm-heading"><?php echo Text::_('OSM_MANAGE_PLANS'); ?></<?php echo $hTag; ?>>
	<?php
	}

	if (OSMembershipHelper::isValidMessage($this->params->get('intro_text')))
	{
	?>
		<div class="osm-description osm-page-intro-text <?php echo $this->bootstrapHelper->getClassMapping('clearfix'); ?>">
			<?php echo HTMLHelper::_('content.prepare', $this->params->get('intro_text')); ?>
		</div>
	<?php
	}
	?>
    <div class="btn-toolbar" id="btn-toolbar">
		<?php echo Toolbar::getInstance('toolbar')->render(); ?>
    </div>
	<form action="<?php echo Route::_('index.php?option=com_osmembership&view=mplans&Itemid=' . $this->Itemid, false); ?>" method="post" name="adminForm" id="adminForm">
		<div class="filters btn-toolbar clearfix mt-2 mb-2">
            <?php echo $this->loadTemplate('search_bar'); ?>
		</div>
		<table class="<?php echo $bootstrapHelper->getClassMapping('table table-striped table-bordered table-hover'); ?>">
			<thead>
				<tr>
                    <th width="20">
						<?php echo HTMLHelper::_('grid.checkall'); ?>
                    </th>
                    <th class="title">
						<?php echo HTMLHelper::_('searchtools.sort', Text::_('OSM_TITLE'), 'tbl.title', $this->state->filter_order_Dir, $this->state->filter_order); ?>
                    </th>
					<?php
					if ($this->showCategory)
					{
						$cols++;
					?>
                        <th class="title">
							<?php echo HTMLHelper::_('searchtools.sort', Text::_('OSM_CATEGORY'), 'b.title', $this->state->filter_order_Dir, $this->state->filter_order); ?>
                        </th>
					<?php
					}

					if ($this->showThumbnail)
					{
						$cols++;
					?>
                        <th class="title" width="10%">
							<?php echo Text::_('OSM_THUMB'); ?>
                        </th>
					<?php
					}
					?>
                    <th class="title" width="8%">
						<?php echo Text::_('OSM_LENGTH'); ?>
                    </th>
                    <th class="center" width="8%">
						<?php echo HTMLHelper::_('searchtools.sort', Text::_('OSM_RECURRING'), 'tbl.recurring_subscription', $this->state->filter_order_Dir, $this->state->filter_order); ?>
                    </th>
                    <th class="title" width="8%">
						<?php echo HTMLHelper::_('searchtools.sort', Text::_('OSM_PRICE'), 'tbl.price', $this->state->filter_order_Dir, $this->state->filter_order); ?>
                    </th>
                    <th class="title center" width="12%">
						<?php echo Text::_('OSM_TOTAL_SUBSCRIBERS'); ?>
                    </th>
                    <th class="title center" width="12%">
						<?php echo Text::_('OSM_ACTIVE_SUBSCRIBERS'); ?>
                    </th>
                    <th width="5%">
						<?php echo HTMLHelper::_('searchtools.sort', Text::_('JGRID_HEADING_ACCESS'), 'tbl.access', $this->state->filter_order_Dir, $this->state->filter_order); ?>
                    </th>
                    <th width="5%">
						<?php echo HTMLHelper::_('searchtools.sort', Text::_('OSM_PUBLISHED'), 'tbl.published', $this->state->filter_order_Dir, $this->state->filter_order); ?>
                    </th>
                    <th width="2%">
						<?php echo HTMLHelper::_('searchtools.sort', Text::_('OSM_ID'), 'tbl.id', $this->state->filter_order_Dir, $this->state->filter_order); ?>
                    </th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="<?php echo $cols; ?>">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->items); $i < $n; $i++)
			{
				$row       = $this->items[$i];
				$link      = Route::_('index.php?option=com_osmembership&task=mplan.edit&id=' . $row->id . '&Itemid=' . $this->Itemid, false);
				$checked   = HTMLHelper::_('grid.id', $i, $row->id);
				$published = HTMLHelper::_('jgrid.published', $row->published, $i, 'mplan.');
				$symbol    = $row->currency_symbol ?: $row->currency;
				?>
				<tr class="<?php echo "row$k"; ?>">
                    <td>
						<?php echo $checked; ?>
                    </td>
                    <td>
                        <?php
						if (OSMembershipHelperAcl::canEditPlan($row->id))
						{
						?>
                            <a href="<?php echo $link; ?>"><?php echo $row->title ; ?></a>
                        <?php
						}
						else
						{
							echo $row->title;
						}
						?>
                    </td>
					<?php
					if ($this->showCategory)
					{
					?>
                        <td><?php echo $row->category_title; ?></td>
					<?php
					}
					if ($this->showThumbnail)
					{
						?>
                        <td class="center">
							<?php
							if ($row->thumb)
							{
							?>
                                <a href="<?php echo Uri::root() . 'media/com_osmembership/' . $row->thumb ; ?>" class="modal"><img src="<?php echo Uri::root() . '/media/com_osmembership/' . $row->thumb ; ?>" /></a>
							<?php
							}
							?>
                        </td>
						<?php
					}
					?>
                    <td>
						<?php
						if ($row->lifetime_membership)
						{
							echo Text::_('OSM_LIFETIME');
						}
						else
						{
							echo OSMembershipHelperSubscription::getDurationText($row->subscription_length, $row->subscription_length_unit);
						}
						?>
                    </td>
                    <td class="center">
                        <?php echo $row->recurring_subscription ? Text::_('JYES') : Text::_('JNO'); ?>
                    </td>
                    <td>
						<?php
						if ($row->price > 0)
						{
							echo OSMembershipHelper::formatCurrency($row->price, $config, $symbol);
						}
						else
						{
							echo Text::_('OSM_FREE');
						}
						?>
                    </td>
                    <td class="center">
                        <?php echo OSMembershipHelper::countSubscribers($row->id); ?>
                    </td>
                    <td class="center">
                        <?php echo OSMembershipHelper::countSubscribers($row->id, 1); ?>
                    </td>
                    <td>
						<?php echo $row->access_level; ?>
                    </td>
                    <td class="center">
                        <?php
						if (OSMembershipHelperAcl::canChangePlanState($row->id))
						{
							echo $published;
						}
						else
						{
							echo $row->published ? Text::_('JYES') : Text::_('JNO');
						}
						?>
                    </td>
                    <td class="center">
						<?php echo $row->id; ?>
                    </td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
		</table>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $this->state->filter_order; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->state->filter_order_Dir; ?>" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</form>
</div>