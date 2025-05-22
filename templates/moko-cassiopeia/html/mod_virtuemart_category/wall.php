<?php

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Router\Route;

$categoryModel->addImages($categories);
$categories_per_row = vmConfig::get('categories_per_row');
$bscol = $module->position == 'sidebar-left' || $module->position == 'sidebar-right' ? '6' : '3';
?>

<ul class="vm-categories-wall list-unstyled p-0 row <?php echo $class_sfx ?>">
	<?php foreach ($categories as $category) : ?>
		<?php
			$caturl = Route::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
			$catname = $category->category_name ;
		?>
		<li class="vm-categories-wall-catwrapper col-6 col-md-4 col-xl-<?php echo $bscol; ?>">
			<div class="vm-categories-wall-spacer text-center">
				<a href="<?php echo $caturl; ?>">
					<?php echo $category->images[0]->displayMediaThumb('class="vm-categories-wall-img img-fluid mb-3"',false) ?>
					<div class="vm-subcategory-title fw-normal pt-2 mb-2 border-top lh-sm"><?php echo $catname; ?></div>
				</a>
			</div>
		</li>
	<?php endforeach; ?>
</ul>