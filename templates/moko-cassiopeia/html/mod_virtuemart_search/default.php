<?php

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

vmJsApi::cssSite();
?>

<form action="<?php echo Route::_('index.php?option=com_virtuemart&view=category&search=true&limitstart=0&virtuemart_category_id='.$category_id ); ?>" method="get">
	<div class="<?php echo $button && $button_text == vmText::_ ('MOD_VIRTUEMART_SEARCH_GO') ? 'vmbasic-search ' : ''; ?>input-group mod-vm-search<?php echo $params->get('moduleclass_sfx') ? ' ' . $params->get('moduleclass_sfx') : ''; ?>">
		<?php
		$output = '<input name="keyword" id="mod_virtuemart_search" maxlength="'.$maxlength.'" placeholder="'.$text.'" class="form-control'. $moduleclass_sfx .'" type="text" size="'.$width.'" />';
		$image = Uri::base() . $imagepath;
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
				<path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
				</svg>';

		if ($button) :
			if ($imagebutton && $imagepath) :
				$button = '<button type="submit" class="btn ' . $moduleclass_sfx . '"><img src="' . $image . '" alt="' . $button_text . '" /></button>';
			elseif ($button_text != vmText::_ ('MOD_VIRTUEMART_SEARCH_GO')) :
				$button = '<button type="submit" class="btn btn-primary ' . $moduleclass_sfx . '">' . $button_text . '</button>';
			else :
				$button = '<button type="submit" class="btn btn-svg '.$moduleclass_sfx.'">' . $svg . '</button>';
			endif;

			switch ($button_pos) :
				case 'right' :
					$output = $output.$button;
					break;
				case 'left' :
					$output = $button.$output;
					break;
				default :
					$output = $output.$button;
				break;
			endswitch;
		endif;

		echo $output;
	?>
	</div>
	<input type="hidden" name="limitstart" value="0" />
	<input type="hidden" name="option" value="com_virtuemart" />
	<input type="hidden" name="view" value="category" />
	<input type="hidden" name="virtuemart_category_id" value="<?php echo $category_id; ?>"/>
	<?php
	if (!empty($set_Itemid))
	{
		echo '<input type="hidden" name="Itemid" value="'.$set_Itemid.'" />';
	}
	?>
</form>