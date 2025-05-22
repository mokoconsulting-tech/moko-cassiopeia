<?php
/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2025 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Layout variables
 *
 * @var string $selector
 * @var string $title
 */

defined('_JEXEC') or die;

Factory::getApplication()
	->getDocument()
	->getWebAssetManager()
	->useScript('core');

Text::script('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST');
$message = "alert(Joomla.JText._('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST'));";
?>
<button type="button" data-toggle="modal" onclick="if (document.adminForm.boxchecked.value==0){<?php echo $message; ?>}else{jQuery( '#<?php echo $selector; ?>' ).modal('show'); return true;}" class="btn btn-small">
	<span class="icon-checkbox-partial" aria-hidden="true"></span>
	<?php echo $title; ?>
</button>
