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

use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;

try
{
	$form = Form::getInstance('plan_fields', JPATH_ROOT . '/components/com_osmembership/fields.xml', [], false, '//config');
}
catch (Exception $e)
{
	return;
}

foreach ($form->getFieldset('basic') as $field)
{
	if ($field->getAttribute('hide'))
	{
		continue;
	}
?>
	<tr class="osm-plan-property">
		<td class="osm-plan-property-label">
			<?php echo Text::_($field->getAttribute('label')); ?>:
		</td>
		<td class="osm-plan-property-value">
			<?php echo $item->fieldsData->get($field->getAttribute('name')); ?>
		</td>
	</tr>
<?php
}
