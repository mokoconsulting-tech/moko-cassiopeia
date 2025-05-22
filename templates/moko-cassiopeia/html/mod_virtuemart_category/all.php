<?php

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

$category_id  = vRequest::getInt ('virtuemart_category_id', 0);
$sublevel = $params->get('level', 0);
?>
<ul class="vm-menu list-unstyled<?php echo $class_sfx ? ' ' . $class_sfx : ''; ?>">
	<?php foreach ($categories as $category) : ?>
		<?php
			$active_menu = '';
			$caturl = Route::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
			$cattext = $category->category_name;

			if (in_array( $category->virtuemart_category_id, $parentCategories)) {
				$active_menu = ' active';
			}
		?>
		<li class="border-bottom<?php echo $active_menu ?>">
			<?php echo HTMLHelper::link($caturl, $cattext); ?>
			<?php if (!empty($category->childs) && $sublevel > 0) : ?>
				<ul class="vm-submenu<?php echo $class_sfx; ?> list-unstyled small px-3 py-1 bg-light">
					<?php foreach ($category->childs as $child) : ?>
						<?php
							$active_menu = '';
							if ($child->virtuemart_category_id == $category_id) {
								$active_menu = ' active';
							}
							$caturl = Route::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$child->virtuemart_category_id);
							$cattext = vmText::_($child->category_name);
						?>
						<li class="border-bottom<?php echo $active_menu ?>">
							<?php echo HTMLHelper::link($caturl, $cattext); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>