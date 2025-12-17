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
    <div class="<?php echo $controlLabelClass; ?>">
		<?php echo OSMembershipHelperHtml::getFieldLabel('activate_member_card_feature', Text::_('OSM_ACTIVATE_MEMBER_CARD_FEATURE'), Text::_('OSM_ACTIVATE_MEMBER_CARD_FEATURE_EXPLAIN')); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
		<?php echo OSMembershipHelperHtml::getBooleanInput('activate_member_card_feature', $this->item->activate_member_card_feature); ?>
    </div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo Text::_('OSM_CARD_BG_IMAGE'); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <?php echo OSMembershipHelperHtml::getMediaInput($this->item->card_bg_image, 'card_bg_image'); ?>
    </div>
</div>
<div class="<?php echo $controlGroupClass; ?>">
    <div class="<?php echo $controlLabelClass; ?>">
        <?php echo Text::_('OSM_CARD_LAYOUT'); ?>
    </div>
    <div class="<?php echo $controlsClass; ?>">
        <?php echo $editor->display('card_layout', $this->item->card_layout, '100%', '550', '75', '8') ;?>
    </div>
</div>

