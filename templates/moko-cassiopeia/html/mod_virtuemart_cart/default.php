<?php

defined('_JEXEC') or die('Restricted access');

\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.dropdown');

vmJsApi::cssSite();

$data = $cart->prepareAjaxData(true);
$view = vRequest::getCmd('view');
?>

<div class="vmCartModule row <?php echo $params->get('moduleclass_sfx'); ?>">
	<div class="col-12 mb-2">
		<p class="total_products px-2 py-1 bg-light">
			<?php echo  $data->totalProductTxt ?>
		</p>
	</div>

	<?php if ($show_product_list) : ?>
		<div class="hiddencontainer d-none" id="hiddencontainer">
			<div class="vmcontainer">
				<div class="product_row row align-items-center pb-2 mb-2">
					<div class="product_image col-3">
						<div class="image image img-thumbnail"></div>
					</div>
					<div class="col-6">
						<span class="quantity"></span>
						&nbsp;x&nbsp;
						<span class="product_name"></span>
						<div class="customProductData col-12 mt-1 small"></div>
					</div>
					<?php if ($show_price and $currencyDisplay->_priceConfig['salesPrice'][0]) : ?>
					<div class="col-3 text-end">
						<span class="subtotal_with_tax"></span>
					</div>
					<?php endif; ?>
					<div class="col-12 mt-3">
						<div class="border-bottom"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="vmcontainer vm_cart_products container small">
			<?php foreach ($data->products as $product) : ?>
				<div class="product_row row align-items-center pb-2 mb-2">
					<div class="product_image col-3">
						<?php if ( VmConfig::get('oncheckout_show_images')) : ?>
						<div class="image img-thumbnail"><?php echo $product['image']; ?></div>
						<?php endif; ?>
					</div>

					<div class="col-6">
						<span class="quantity">
							<?php echo  $product['quantity'] ?>
						</span>
						&nbsp;x&nbsp;
						<span class="product_name">
							<?php echo  $product['product_name'] ?>
						</span>
						<div class="customProductData col-12 mt-1 small">
							<?php echo $product['customProductData'] ?>
						</div>
					</div>

					<?php if ($show_price and $currencyDisplay->_priceConfig['salesPrice'][0]) : ?>
					<div class="col-3 text-end text-nowrap">
						<span class="subtotal_with_tax">
							<?php echo $product['subtotal_with_tax'] ?>
						</span>
					</div>
					<?php endif; ?>
					<div class="col-12 mt-3">
						<div class="border-bottom"></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="show_cart_m d-flex align-items-center container">
			<a class="btn btn-secondary btn-sm show-cart me-auto" href="<?php echo $data->cart_show_link; ?>" rel="nofollow">
				<?php echo vmText::_('COM_VIRTUEMART_CART_SHOW'); ?>
			</a>
			<span class="total small">
				<?php echo !empty($data->products) ? $data->billTotal : ''; ?>
			</span>
		</div>
	<?php endif; ?>

	<?php if ($view != 'cart' and $view != 'user') : ?>
		<div class="payments-signin-button"></div>
	<?php endif; ?>

	<noscript>
		<?php echo vmText::_('MOD_VIRTUEMART_CART_AJAX_CART_PLZ_JAVASCRIPT') ?>
	</noscript>
</div>