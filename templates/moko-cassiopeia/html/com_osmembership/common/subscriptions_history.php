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
use Joomla\CMS\Router\Route;

/**
 * Layout variables
 *
 * @var bool                              $showPagination
 * @var \Joomla\CMS\Pagination\Pagination $pagination
 */

/* @var \Joomla\Database\DatabaseDriver $db */
$db    = Factory::getContainer()->get('db');
$query = $db->getQuery(true)
	->select('COUNT(*)')
	->from('#__osmembership_plugins')
	->where('published = 1')
	->where('name NOT LIKE "os_offline%"');
$db->setQuery($query);
$hasOnlinePaymentPlugin = $db->loadResult() > 0;

$makePaymentItemid      = OSMembershipHelperRoute::getViewRoute('payment', $this->Itemid);

$cols = 5;

$bootstrapHelper  = OSMembershipHelperBootstrap::getInstance();
$centerClass      = $bootstrapHelper->getClassMapping('center');
$hiddenPhoneClass = $bootstrapHelper->getClassMapping('hidden-phone');
?>
<table class="<?php echo $bootstrapHelper->getClassMapping('table table-striped table-bordered') ?>">
	<thead>
		<tr>
			<th>
				<?php echo Text::_('OSM_PLAN') ?>
			</th>
			<th class="<?php echo $centerClass; ?>">
				<?php echo Text::_('OSM_SUBSCRIPTION_DATE') ; ?>
			</th>
			<th class="<?php echo $centerClass; ?>">
				<?php echo Text::_('OSM_ACTIVATE_TIME') ; ?>
			</th>
			<th style="text-align: right;" class="<?php echo $hiddenPhoneClass; ?>">
				<?php echo Text::_('OSM_GROSS_AMOUNT') ; ?>
			</th>
			<th class="<?php echo $hiddenPhoneClass; ?>">
				<?php echo Text::_('OSM_SUBSCRIPTION_STATUS'); ?>
			</th>
			<?php
			if ($this->config->activate_invoice_feature)
			{
				$cols++ ;
			?>
                <th class="<?php echo $hiddenPhoneClass . ' ' . $centerClass; ?>">
					<?php echo Text::_('OSM_INVOICE_NUMBER') ; ?>
				</th>
			<?php
			}
			?>
		</tr>
	</thead>
	<tbody>
	<?php
	$k = 0 ;
	for ($i = 0 , $n = count($this->items) ; $i < $n ; $i++) {
		$row    = $this->items[$i];
		$k      = 1 - $k;
		$link   = Route::_('index.php?option=com_osmembership&view=subscription&id=' . $row->id . '&Itemid=' . $this->Itemid);
		$symbol = $row->currency_symbol ?: $row->currency;
		?>
		<tr>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->plan_title; ?></a>
			</td>
			<td class="<?php echo $centerClass; ?>">
				<?php echo HTMLHelper::_('date', $row->created_date, $this->config->date_format); ?>
			</td>
			<td class="<?php echo $centerClass; ?>">
				<strong><?php echo HTMLHelper::_('date', $row->from_date, $this->config->date_format); ?></strong> <?php echo Text::_('OSM_TO'); ?>
				<strong>
					<?php
					if ($row->lifetime_membership || $row->to_date == '2099-12-31 23:59:59')
					{
						echo Text::_('OSM_LIFETIME');
					}
					else
					{
						echo HTMLHelper::_('date', $row->to_date, $this->config->date_format);
					}
					?>
				</strong>
			</td>
			<td style="text-align: right;" class="<?php echo $hiddenPhoneClass; ?>">
				<?php echo OSMembershipHelper::formatCurrency($row->gross_amount, $this->config, $symbol)?>
			</td>
			<td class="<?php echo $hiddenPhoneClass; ?>">
				<?php
				switch ($row->published)
				{
					case 0 :
						echo Text::_('OSM_PENDING');

						if ($this->config->enable_subscription_payment && $row->gross_amount > 0 && $hasOnlinePaymentPlugin)
						{
						?>
                           <br /><a class="<?php echo $bootstrapHelper->getClassMapping('btn btn-primary'); ?>" href="<?php echo Route::_('index.php?option=com_osmembership&view=payment&transaction_id=' . $row->transaction_id . '&Itemid=' . $makePaymentItemid); ?>"><?php echo Text::_('OSM_MAKE_PAYMENT'); ?></a>
                        <?php
						}

						break;
					case 1 :
						echo Text::_('OSM_ACTIVE');
						break;
					case 2 :
						echo Text::_('OSM_EXPIRED');
						break;
					case 3 :
						echo Text::_('OSM_CANCELLED_PENDING');
						break;
					case 4 :
						echo Text::_('OSM_CANCELLED_REFUNDED');
						break;
				}
				?>
			</td>
			<?php
			if ($this->config->activate_invoice_feature)
			{
			?>
				<td class="<?php echo $hiddenPhoneClass . ' ' . $centerClass; ?>">
					<?php
					if ($row->invoice_number)
					{
					?>
						<a href="<?php echo Route::_('index.php?option=com_osmembership&task=download_invoice&id=' . $row->id); ?>" title="<?php echo Text::_('OSM_DOWNLOAD'); ?>"><?php echo OSMembershipHelper::formatInvoiceNumber($row, $this->config); ?></a>
					<?php
					}
					?>
				</td>
			<?php
			}
			?>
		</tr>
		<?php
	}
	?>
	</tbody>
	<?php
	if ($showPagination && ($pagination->total > $pagination->limit))
	{
	?>
		<tfoot>
			<tr>
				<td colspan="<?php echo $cols; ?>">
					<div class="pagination"><?php echo $this->pagination->getListFooter(); ?></div>
				</td>
			</tr>
		</tfoot>
	<?php
	}
	?>
</table>

