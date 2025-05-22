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
use Joomla\CMS\Uri\Uri;

$rootUri = Uri::root(true);
$bootstrapHelper   = OSMembershipHelperBootstrap::getInstance();
$rowFluidClasss    = $bootstrapHelper->getClassMapping('row-fluid');
$controlGroupClass = $bootstrapHelper->getClassMapping('control-group');
$controlLabelClass = $bootstrapHelper->getClassMapping('control-label');
$controlsClass     = $bootstrapHelper->getClassMapping('controls');

echo HTMLHelper::_('bootstrap.startTabSet', 'plan-translation', ['active' => 'translation-page-' . $this->languages[0]->sef, 'recall' => true]);

foreach ($this->languages as $language)
{
	$sef = $language->sef;
	echo HTMLHelper::_('bootstrap.addTab', 'plan-translation', 'translation-page-' . $sef, $language->title . ' <img src="' . $rootUri . '/media/mod_languages/images/' . $language->image . '.gif" />');
	?>
	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo  Text::_('OSM_TITLE'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<input class="form-control input-xlarge" type="text" name="title_<?php echo $sef; ?>" id="title_<?php echo $sef; ?>" maxlength="250" value="<?php echo $this->item->{'title_' . $sef}; ?>" />
		</div>
	</div>
	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo  Text::_('OSM_ALIAS'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<input class="form-control input-xlarge" type="text" name="alias_<?php echo $sef; ?>" id="title_<?php echo $sef; ?>" maxlength="250" value="<?php echo $this->item->{'alias_' . $sef}; ?>" />
		</div>
	</div>
	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_SHORT_DESCRIPTION'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<?php echo $editor->display('short_description_' . $sef, $this->item->{'short_description_' . $sef}, '100%', '250', '75', '10') ; ?>
		</div>
	</div>
	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_DESCRIPTION'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<?php echo $editor->display('description_' . $sef, $this->item->{'description_' . $sef}, '100%', '250', '75', '10') ; ?>
		</div>
	</div>
    <div class="<?php echo $controlGroupClass; ?>">
        <div class="<?php echo $controlLabelClass; ?>">
			<?php echo  Text::_('OSM_PAGE_TITLE'); ?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
            <input class="form-control input-xlarge" type="text" name="page_title_<?php echo $sef; ?>" id="page_title_<?php echo $sef; ?>" maxlength="250" value="<?php echo $this->item->{'page_title_' . $sef}; ?>" />
        </div>
    </div>
    <div class="<?php echo $controlGroupClass; ?>">
        <div class="<?php echo $controlLabelClass; ?>">
			<?php echo  Text::_('OSM_PAGE_HEADING'); ?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
            <input class="form-control input-xlarge" type="text" name="page_heading_<?php echo $sef; ?>" id="page_heading_<?php echo $sef; ?>" maxlength="250" value="<?php echo $this->item->{'page_heading_' . $sef}; ?>" />
        </div>
    </div>
    <div class="<?php echo $controlGroupClass; ?>">
        <div class="<?php echo $controlLabelClass; ?>">
			<?php echo  Text::_('OSM_META_DESCRIPTION'); ?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
            <textarea rows="5" cols="30" class="input-lage" name="meta_description_<?php echo $sef; ?>"><?php echo $this->item->{'meta_description_' . $sef}; ?></textarea>
        </div>
    </div>
    <div class="<?php echo $controlGroupClass; ?>">
        <div class="<?php echo $controlLabelClass; ?>">
			<?php echo  Text::_('OSM_META_KEYWORDS'); ?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
            <textarea rows="5" cols="30" class="input-lage" name="meta_keywords_<?php echo $sef; ?>"><?php echo $this->item->{'meta_keywords_' . $sef}; ?></textarea>
        </div>
    </div>
    <div class="<?php echo $controlGroupClass; ?>">
        <div class="<?php echo $controlLabelClass; ?>">
			<?php echo  Text::_('OSM_META_DESCRIPTION'); ?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
            <textarea rows="5" cols="30" class="input-lage" name="meta_description_<?php echo $sef; ?>"><?php echo $this->item->{'meta_description_' . $sef}; ?></textarea>
        </div>
    </div>
	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_PLAN_SUBSCRIPTION_FORM_MESSAGE'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<?php echo $editor->display('subscription_form_message_' . $sef, $this->item->{'subscription_form_message_' . $sef}, '100%', '250', '75', '10') ; ?>
		</div>
	</div>
	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_USER_EMAIL_SUBJECT'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<input type="text" name="user_email_subject_<?php echo $sef; ?>" class="form-control" value="<?php echo $this->item->{'user_email_subject_' . $sef}; ?>" />
		</div>
	</div>
	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_USER_EMAIL_BODY'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<?php echo $editor->display('user_email_body_' . $sef, $this->item->{'user_email_body_' . $sef}, '100%', '250', '75', '8') ;?>
		</div>
	</div>
	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_USER_EMAIL_BODY_OFFLINE_PAYMENT'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<?php echo $editor->display('user_email_body_offline_' . $sef, $this->item->{'user_email_body_offline_' . $sef}, '100%', '250', '75', '8') ;?>
		</div>
	</div>
	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_THANK_MESSAGE'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<?php echo $editor->display('thanks_message_' . $sef, $this->item->{'thanks_message_' . $sef}, '100%', '250', '75', '8') ;?>
		</div>
	</div>
	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_THANK_MESSAGE_OFFLINE'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<?php echo $editor->display('thanks_message_offline_' . $sef, $this->item->{'thanks_message_offline_' . $sef}, '100%', '250', '75', '8') ;?>
		</div>
	</div>
	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_SUBSCRIPTION_APPROVED_EMAIL_SUBJECT'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<input type="text" name="subscription_approved_email_subject_<?php echo $sef; ?>" class="form-control" value="<?php echo $this->item->{'subscription_approved_email_subject_' . $sef}; ?>" />
		</div>
	</div>
	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_SUBSCRIPTION_APPROVED_EMAIL_BODY'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<?php echo $editor->display('subscription_approved_email_body_' . $sef, $this->item->{'subscription_approved_email_body_' . $sef}, '100%', '250', '75', '8') ;?>
		</div>
	</div>

	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_RENEW_USER_EMAIL_SUBJECT'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<input type="text" name="user_renew_email_subject_<?php echo $sef; ?>" class="form-control" value="<?php echo $this->item->{'user_renew_email_subject_' . $sef}; ?>" />
		</div>
	</div>

	<div class="<?php echo $controlGroupClass; ?>">
		<div class="<?php echo $controlLabelClass; ?>">
			<?php echo Text::_('OSM_RENEW_USER_EMAIL_BODY'); ?>
		</div>
		<div class="<?php echo $controlsClass; ?>">
			<?php echo $editor->display('user_renew_email_body_' . $sef, $this->item->{'user_renew_email_body_' . $sef}, '100%', '250', '75', '8') ;?>
		</div>
	</div>
    <div class="<?php echo $controlGroupClass; ?>">
        <div class="<?php echo $controlLabelClass; ?>">
            <?php echo Text::_('OSM_RENEW_THANK_MESSAGE'); ?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
            <?php echo $editor->display('renew_thanks_message_' . $sef, $this->item->{'renew_thanks_message_' . $sef}, '100%', '250', '75', '8') ;?>
        </div>
    </div>
    <div class="<?php echo $controlGroupClass; ?>">
        <div class="<?php echo $controlLabelClass; ?>">
            <?php echo Text::_('OSM_RENEW_THANK_MESSAGE_OFFLINE'); ?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
	        <?php echo $editor->display('renew_thanks_message_offline_' . $sef, $this->item->{'renew_thanks_message_offline_' . $sef}, '100%', '250', '75', '8'); ?>
        </div>
    </div>
    <div class="<?php echo $controlGroupClass; ?>">
        <div class="<?php echo $controlLabelClass; ?>">
            <?php echo Text::_('OSM_UPGRADE_THANK_MESSAGE'); ?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
	        <?php echo $editor->display('upgrade_thanks_message_' . $sef, $this->item->{'upgrade_thanks_message_' . $sef}, '100%', '250', '75', '8'); ?>
        </div>
    </div>
    <div class="<?php echo $controlGroupClass; ?>">
        <div class="<?php echo $controlLabelClass; ?>">
            <?php echo Text::_('OSM_UPGRADE_THANK_MESSAGE_OFFLINE'); ?>
        </div>
        <div class="<?php echo $controlsClass; ?>">
	        <?php echo $editor->display('upgrade_thanks_message_offline_' . $sef, $this->item->{'upgrade_thanks_message_offline_' . $sef}, '100%', '250', '75', '8'); ?>
        </div>
    </div>

	<?php
	echo HTMLHelper::_('bootstrap.endTab');
}

echo HTMLHelper::_('bootstrap.endTabSet');
