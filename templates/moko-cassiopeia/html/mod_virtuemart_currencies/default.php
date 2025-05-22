<?php

defined('_JEXEC') or die('Restricted access');

vmJsApi::cssSite();

$selectedCurrency = $currencyModel->getCurrency($virtuemart_currency_id);
?>

<?php if ($text_before) : ?>
<p class="small"><?php echo $text_before; ?></p>
<?php endif; ?>

<form class="virtuemart-currency-form d-none" action="<?php echo vmURI::getCurrentUrlBy('get',true) ?>" method="post">
	<input type="hidden" name="virtuemart_currency_id" value="" />
</form>

<div class="vm-currencies-dropdown dropdown">
	<button class="btn btn-link btn-sm dropdown-toggle p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo $selectedCurrency->currency_code_3 . ' ' . $selectedCurrency->currency_symbol; ?></button>
	<ul class="dropdown-menu dropdown-menu-end">
		<?php foreach ($currencies as $currency) : ?>
		<li><button class="dropdown-item" data-cur-id="<?php echo $currency->virtuemart_currency_id;?>"><?php echo $currency->currency_txt; ?></button></li>
		<?php endforeach; ?>
	</ul>
</div>

<?php
$j = 'jQuery(document).ready(function($) {
		$(\'.dropdown-item\').click(function(e) {
			var currencyId = $(this).attr(\'data-cur-id\');
			$(\'input[name="virtuemart_currency_id"]\').val(currencyId);
			$(\'.virtuemart-currency-form\').submit();
		});
})';

vmJsApi::addJScript('sendFormChange',$j);
echo vmJsApi::writeJS();