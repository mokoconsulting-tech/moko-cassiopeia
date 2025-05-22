<?php

/**
 * @package     Joomla.Site
 * @subpackage  Templates.vmbasic
 *
 * @copyright   (C) 2024 Spiros Petrakis
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.dropdown');

vmJsApi::cssSite();

$data = $cart->prepareAjaxData(true);
$view = vRequest::getCmd('view');
?>

<div class="vmCartModule <?php echo $params->get('moduleclass_sfx'); ?> dropdown">
	<button class="btn btn-link btn-sm p-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cart3" viewBox="0 0 16 16">
			<path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
		</svg>

		<span class="total_products ms-2">
			<?php echo  $data->totalProductTxt ?>
		</span>
	</button>

	<?php if ($show_product_list) : ?>
	<div class="hiddencontainer d-none" id="hiddencontainer">
		<div class="vmcontainer">
			<div class="product_row row align-items-center pb-2 mb-2 border-bottom">
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
			</div>
		</div>
	</div>

	<div class="dropdown-menu dropdown-menu-end">
		<div class="vmcontainer vm_cart_products container small">
			<?php foreach ($data->products as $product) : ?>
				<div class="product_row row align-items-center pb-2 mb-2 border-bottom">
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
	</div>
	<?php endif; ?>

	<?php if ($view != 'cart' and $view != 'user') : ?>
		<div class="payments-signin-button"></div>
	<?php endif; ?>

	<noscript>
		<?php echo vmText::_('MOD_VIRTUEMART_CART_AJAX_CART_PLZ_JAVASCRIPT') ?>
	</noscript>
</div>