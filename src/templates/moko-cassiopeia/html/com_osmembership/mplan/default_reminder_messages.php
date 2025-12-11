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

$bootstrapHelper   = OSMembershipHelperBootstrap::getInstance();
$rowFluidClasss    = $bootstrapHelper->getClassMapping('row-fluid');
$controlGroupClass = $bootstrapHelper->getClassMapping('control-group');
$controlLabelClass = $bootstrapHelper->getClassMapping('control-label');
$controlsClass     = $bootstrapHelper->getClassMapping('controls');
?>
<div class="<?php echo $controlGroupClass; ?>">
	<p class="text-error" style="font-size:16px;"><?php echo Text::_('OSM_PLAN_MESSAGES_EXPLAIN'); ?></p>
</div>
<div class="<?php echo $controlGroupClass; ?>">
	<div class="<?php echo $controlLabelClass; ?>">
		<?php echo Text::_('OSM_FIRST_REMINDER_EMAIL_SUBJECT'); ?>
	</div>
	<div class="<?php echo $controlsClass; ?>">
		<input type="text" name="first_reminder_email_subject" class="input-xxlarge form-control"
		       value="<?php echo $this->item->first_reminder_email_subject; ?>" />
	</div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
	<div class="<?php echo $controlLabelClass; ?>">
		<?php echo Text::_('OSM_FIRST_REMINDER_EMAIL_BODY'); ?>
	</div>
	<div class="<?php echo $controlsClass; ?>">
		<?php echo $editor->display('first_reminder_email_body', $this->item->first_reminder_email_body, '100%', '250', '75', '8'); ?>
	</div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
	<div class="<?php echo $controlLabelClass; ?>">
		<?php echo Text::_('OSM_SECOND_REMINDER_EMAIL_SUBJECT'); ?>
	</div>
	<div class="<?php echo $controlsClass; ?>">
		<input type="text" name="second_reminder_email_subject" class="input-xxlarge form-control"
		       value="<?php echo $this->item->second_reminder_email_subject; ?>" />
	</div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
	<div class="<?php echo $controlLabelClass; ?>">
		<?php echo Text::_('OSM_SECOND_REMINDER_EMAIL_BODY'); ?>
	</div>
	<div class="<?php echo $controlsClass; ?>">
		<?php echo $editor->display('second_reminder_email_body', $this->item->second_reminder_email_body, '100%', '250', '75', '8'); ?>
	</div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
	<div class="<?php echo $controlLabelClass; ?>">
		<?php echo Text::_('OSM_THIRD_REMINDER_EMAIL_SUBJECT'); ?>
	</div>
	<div class="<?php echo $controlsClass; ?>">
		<input type="text" name="third_reminder_email_subject" class="input-xxlarge form-control"
		       value="<?php echo $this->item->third_reminder_email_subject; ?>" />
	</div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
	<div class="<?php echo $controlLabelClass; ?>">
		<?php echo Text::_('OSM_THIRD_REMINDER_EMAIL_BODY'); ?>
	</div>
	<div class="<?php echo $controlsClass; ?>">
		<?php echo $editor->display('third_reminder_email_body', $this->item->third_reminder_email_body, '100%', '250', '75', '8'); ?>
	</div>
</div>

