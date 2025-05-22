<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2025 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Layout variables
 *
 * @var array $rows
 * @var array $fields
 */

$config = OSMembershipHelper::getConfig();
$i = 1;
?>
<p style="padding-bottom: 20px; text-align: center;">
<h1><?php echo Text::_('OSM_SUBSCRIPTIONS_LIST'); ?></h1>
</p>
<table border="1" width="100%" cellspacing="0" cellpadding="2" style="margin-top: 100px;">
	<thead>
	<tr>
		<th width="3%" height="20" style="text-align: center;">
			No
		</th>
		<th height="20" width="8%">
			<?php echo Text::_('OSM_FIRSTNAME'); ?>
		</th height="20">
		<th height="20" width="10%">
			<?php echo Text::_('OSM_LASTNAME'); ?>
		</th height="20">
		<th height="20" width="20%">
			<?php echo Text::_('OSM_PLAN'); ?>
		</th>
		<th height="20" width="17%" style="text-align: center">
			<?php echo Text::_('OSM_START_DATE') . ' / ' . Text::_('OSM_END_DATE'); ?>
		</th>
		<th height="20" width="16%">
			<?php echo Text::_('OSM_EMAIL'); ?>
		</th>
		<th height="20" width="9%" style="text-align: center;">
			<?php echo Text::_('OSM_CREATED_DATE'); ?>
		</th>
		<th width="6%" height="20" style="text-align: right;">
			<?php echo Text::_('OSM_GROSS_AMOUNT'); ?>
		</th>
        <th width="8%" height="20">
			<?php echo Text::_('OSM_SUBSCRIPTION_STATUS'); ?>
        </th>
		<th width="3%" height="20" style="text-align: center;">
			<?php echo Text::_('OSM_ID'); ?>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ($rows as $row)
	{
		?>
		<tr>
			<td width="3%" style="text-align: center;"><?php echo $i++; ?></td>
			<td width="8%"><?php echo $row->first_name; ?></td>
			<td width="10%"><?php echo $row->last_name; ?></td>
			<td width="20%;"><?php echo $row->plan; ?></td>
			<td width="17%" style="text-align: center"><?php echo $row->from_date . ' / ' . $row->to_date; ?></td>
			<td width="16%"><?php echo $row->email; ?></td>
			<td width="9%" style="text-align: center;"><?php echo $row->created_date; ?></td>
			<td width="6%" style="text-align: right;"><?php echo $row->amount; ?></td>
            <th width="8%" height="20">
                <?php
					switch ($row->published)
					{
						case 0:
							echo Text::_('OSM_PENDING');
							break;
						case 1:
							echo Text::_('OSM_ACTIVE');
							break;
						case 2:
							echo Text::_('OSM_EXPIRED');
							break;
						case 3 :
							echo Text::_('OSM_CANCELLED_PENDING');
							break ;
						case 4 :
							echo Text::_('OSM_CANCELLED_REFUNDED');
							break ;
					}
				?>
            </th>
			<td width="3%" style="text-align: center;"><?php echo $row->id; ?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>