<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2025 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

use Joomla\CMS\Form\Form;

$form                      = Form::getInstance('renew_options', JPATH_ADMINISTRATOR . '/components/com_osmembership/view/plan/forms/renew_options.xml');
$formData['renew_options'] = [];

foreach ($this->prices as $renewOption)
{
	$formData['renew_options'][] = [
		'id'                       => $renewOption->id,
		'renew_option_length'      => $renewOption->renew_option_length,
		'renew_option_length_unit' => $renewOption->renew_option_length_unit,
		'price'                    => $renewOption->price,
	];
}

$form->bind($formData);

foreach ($form->getFieldset() as $field)
{
	echo $field->input;
}
