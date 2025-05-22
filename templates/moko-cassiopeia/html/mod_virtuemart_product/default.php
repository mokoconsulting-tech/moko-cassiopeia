<?php

defined ('_JEXEC') or die('Restricted access');

use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

// Load Bootstrap tooltip
\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.tooltip');

vmJsApi::jPrice();
vmJsApi::cssSite();

$ratingModel = VmModel::getModel('ratings');
$showRating = $ratingModel->showRating();

$emptyStar = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
<path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
</svg>';

$star = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
<path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>';

$bscol = ' col-xl-' . floor (12 / $products_per_row);
?>

<div class="vm-products-module<?php echo $params->get ('moduleclass_sfx') ?>">
	<?php if ($headerText) : ?>
	<div class="vm-header-text mb-4"><?php echo $headerText ?></div>
	<?php endif; ?>

	<?php if ($display_style == "div") : ?>
		<div class="vm-product-grid<?php echo $params->get ('moduleclass_sfx'); ?> row gy-4 g-xl-5">
			<?php foreach ($products as $product) : ?>
				<div class="product-container d-flex flex-column col-6 col-md-6 col-lg-4<?php echo $bscol; ?> pb-4">
					<div class="vm-product-media-container text-center d-flex flex-column justify-content-center"<?php echo VmConfig::get('img_height', 0) ? ' style="min-height:' . VmConfig::get('img_height', 0) . 'px"' : ''?>>
						<?php
						$image = !empty($product->images[0]) ? $product->images[0]->displayMediaThumb ('class="vm-products-module-img img-fluid"', FALSE) : '';
						echo HTMLHelper::_ ('link', Route::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
						?>
					</div>
					<div class="vm-product-rating-container d-flex justify-content-between pb-2 my-3 border-bottom">
						<?php if ($showRating) : ?>
							<?php
								$productRating = $ratingModel->getRatingByProduct($product->virtuemart_product_id, true);
								$maxrating = VmConfig::get('vm_maximum_rating_scale', 5);
							?>
							<?php if (empty($productRating->rating)) : ?>
								<div class="vm-ratingbox-unrated d-inline-block" title="<?php echo vmText::_('COM_VIRTUEMART_UNRATED'); ?>" data-bs-toggle="tooltip">
									<?php
									for ($i=0; $i<5; $i++)
									{
									  echo $emptyStar;
									}
									?>
								</div>
							<?php else : ?>
								<?php $ratingwidth = $productRating->rating * 16; ?>
								<div class="vm-ratingbox-container d-inline-block position-relative">
									<div class="vm-ratingbox-unrated d-inline-block">
										<?php
										for ($i=0; $i<5; $i++)
										{
											echo $emptyStar;
										}
										?>
									</div>
									<div class="vm-ratingbox-rated d-inline-block" title="<?php echo (vmText::_("COM_VIRTUEMART_RATING_TITLE") . ' ' . round($productRating->rating, 2) . '/' . $maxrating) ?>" data-bs-toggle="tooltip">
										<div class="vm-ratingbox-bar overflow-x-hidden text-nowrap" style="width:<?php echo $ratingwidth.'px'; ?>">
								 			<?php
											for ($i=0; $i<5; $i++)
											{
												echo $star;
											}
											?>
										</div>
									</div>
								</div>
							 <?php endif; ?>
						<?php endif; ?>
						<?php echo shopFunctionsF::renderVmSubLayout('displaystock', array('product'=>$product)); ?>
					</div>

					<?php $url = Route::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .$product->virtuemart_category_id); ?>

					<h3 class="vm-product-title text-center mb-2">
						<a href="<?php echo $url ?>">
							<?php echo $product->product_name; ?>
						</a>
					</h3>

					<p class="vm-product-s-desc text-center text-secondary">
						<?php echo shopFunctionsF::limitStringByWord ($product->product_s_desc, 60, ' ...') ?>
					</p>

					<div class="product-price vm-simple-price-display text-center d-flex justify-content-center align-items-center mb-auto<?php echo $product->prices['discountAmount'] ? ' vm-has-discount' : ''?>">
						<?php
							if ($show_price) {
								if (!empty($product->prices['salesPrice'])) {
									echo $currency->createPriceDiv ('salesPrice', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
								}

								if ($product->prices['discountAmount']) {
									echo $currency->createPriceDiv ('basePriceWithTax', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
								}
							}
						?>
					</div>

					<?php if ($show_addtocart) : ?>
						<?php echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product)); ?>
					<?php else : ?>
						<a class="btn btn-secondary w-100 mt-3" href="<?php echo $url; ?>"><?php echo vmText::_ ( 'COM_VIRTUEMART_PRODUCT_DETAILS' ); ?></a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<ul class="vm-product-list p-0 <?php echo $params->get ('moduleclass_sfx'); ?> productdetails">
			<?php foreach ($products as $product) : ?>
				<li class="product-container list-unstyled p-0 d-flex flex-column col-12 mb-4">
					<div class="row align-items-center">
						<div class="col-5">
							<?php
							$image = !empty($product->images[0]) ? $product->images[0]->displayMediaThumb ('class="vm-products-module-img img-fluid"', FALSE) : '';
							echo HTMLHelper::_ ('link', Route::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
							?>
						</div>
						<div class="col-7">
							<?php $url = Route::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .$product->virtuemart_category_id); ?>

							<h3 class="vm-product-title mb-2">
								<a href="<?php echo $url ?>">
									<?php echo $product->product_name; ?>
								</a>
							</h3>

							<div class="product-price vm-simple-price-display d-flex justify-content-start align-items-center mb-2<?php echo $product->prices['discountAmount'] ? ' vm-has-discount' : ''?>">
								<?php
									if ($show_price) {
										if (!empty($product->prices['salesPrice'])) {
											echo $currency->createPriceDiv ('salesPrice', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
										}

										if ($product->prices['discountAmount']) {
											echo $currency->createPriceDiv ('basePriceWithTax', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
										}
									}
								?>
							</div>

							<a class="btn btn-sm btn-secondary w-100" href="<?php echo $url; ?>"><?php echo vmText::_ ( 'COM_VIRTUEMART_PRODUCT_DETAILS' ); ?></a>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if ($footerText) : ?>
		<div class="vm-footer-text<?php echo $params->get ('moduleclass_sfx') ?>">
			<?php echo $footerText ?>
		</div>
	<?php endif; ?>
</div>