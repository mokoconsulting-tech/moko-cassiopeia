<?php

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.collapse');

$doc   = Factory::getDocument();
$wa    = $doc->getWebAssetManager();
$wa->addInlineScript('jQuery(function($) {
		$(\'.vm-menu-btn\').click(function(e){
			e.stopPropagation();
			e.preventDefault();
		});
	});
');

$category_id  = vRequest::getInt ('virtuemart_category_id', 0);
$sublevel = $params->get('level', 0);
$btnIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
</svg>';
?>
<ul class="vm-menu vm-menu-current list-unstyled<?php echo $class_sfx ? ' ' . $class_sfx : ''; ?>">
	<?php foreach ($categories as $category) : ?>
		<?php
			$active_menu = '';

			if (in_array( $category->virtuemart_category_id, $parentCategories)) {
				$active_menu = ' active';
			}

			$has_children =  !empty($category->childs) ? ' has-children' : '';
			$collapsed = empty($active_menu) ? ' collapsed' : '';
			$caturl = Route::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
			$btn = '<button class="vm-menu-btn' . $collapsed . '" type="button" data-bs-toggle="collapse" href="#vm-menu-current-' . $category->virtuemart_category_id . '" role="button" aria-expanded="false" aria-controls="vm-menu-current-' . $category->virtuemart_category_id . '">' . $btnIcon . '</button>';
			$submenu_btn = !empty($category->childs) && $sublevel > 0 ? $btn : '';
			$cattext = $category->category_name . $submenu_btn;
		?>
		<li class="border-bottom<?php echo $active_menu . $has_children; ?>">
			<?php echo HTMLHelper::link($caturl, $cattext); ?>
			<?php if (!empty($category->childs) && $sublevel > 0) : ?>
				<div class="collapse<?php echo !empty($active_menu) ? ' show' : ''; ?>" id="vm-menu-current-<?php echo $category->virtuemart_category_id; ?>">
					<ul class="vm-submenu<?php echo $class_sfx; ?> list-unstyled small px-3 py-1 bg-light">
						<?php foreach ($category->childs as $child) : ?>
							<?php
								$active_menu = '';
								if ($child->virtuemart_category_id == $category_id) {
									$active_menu = ' active';
								}
								$caturl = Route::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$child->virtuemart_category_id);
								$childcattext = $child->category_name;
							?>
							<li class="border-bottom<?php echo $active_menu ?>">
								<?php echo HTMLHelper::link($caturl, $childcattext); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>