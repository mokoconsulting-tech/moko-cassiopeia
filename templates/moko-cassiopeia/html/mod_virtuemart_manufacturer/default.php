<?php

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Router\Route;

$bscol = round(12 / $manufacturers_per_row);
?>

<div class="vmgroup<?php echo $params->get( 'moduleclass_sfx' ) ?>">
	<?php if ($headerText) : ?>
		<div class="vm-header-text mb-4"><?php echo $headerText ?></div>
	<?php endif; ?>

	<?php if ($display_style =="div") : ?>
		<div class="vm-manufacturer-module<?php echo $params->get('moduleclass_sfx'); ?> row gy-4 mb-4">
			<?php foreach ($manufacturers as $manufacturer) : ?>
				<?php $link = Route::_('index.php?option=com_virtuemart&view=manufacturer&virtuemart_manufacturer_id=' . $manufacturer->virtuemart_manufacturer_id); ?>
				<div class="col-6 col-md-4 col-lg-<?php echo $bscol; ?>">
					<a href="<?php echo $link; ?>">
						<?php
						if ($manufacturer->images && ($show == 'image' or $show == 'all' ))
						{
							echo $manufacturer->images[0]->displayMediaThumb('',false);
						}
						?>
						<?php if ($show == 'text' or $show == 'all') : ?>
						<div><?php echo $manufacturer->mf_name; ?></div>
						<?php endif; ?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<ul class="vmmanufacturer<?php echo $params->get('moduleclass_sfx'); ?> row mb-4">
			<?php foreach ($manufacturers as $manufacturer) : ?>
				<?php $link = Route::_('index.php?option=com_virtuemart&view=manufacturer&virtuemart_manufacturer_id=' . $manufacturer->virtuemart_manufacturer_id); ?>
				<li class="<?php echo ($show == 'image' or $show == 'all') ? 'col-6 mb-3 text-center' : 'col-12 pb-2 mb-2 border-bottom'; ?>">
					<a href="<?php echo $link; ?>">
						<?php if ($manufacturer->images && ($show == 'image' or $show == 'all' )) : ?>
							<?php echo $manufacturer->images[0]->displayMediaThumb('class="img-thumbnail"',false);?>
						<?php endif; ?>

						<?php if ($show == 'text' or $show == 'all' ) : ?>
							<?php
								if ($show == 'all') {
									$class = "text-center";
								}
							?>

							<div>
								<?php echo $manufacturer->mf_name; ?>
							</div>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if ($footerText) : ?>
		<div class="vm-footer-text<?php echo $params->get( 'moduleclass_sfx' ) ?>">
			<?php echo $footerText ?>
		</div>
	<?php endif; ?>
</div>