<?php

/**
 * @package        Joomla
 * @subpackage     Membership Pro
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2012 - 2025 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 *
 * @var string $redirectHeading
 * @var string $url
 * @var bool   $newWindow
 * @var array  $data
 */
?>
<div class="payment-heading"><?php echo $redirectHeading; ?></div>
<form method="post" action="<?php echo $url; ?>" name="payment_form"
      id="payment_form"<?php if ($newWindow) echo ' target="_blank"'; ?>>
	<?php
	foreach ($data as $key => $val)
	{
		echo '<input type="hidden" name="' . $key . '" value="' . $val . '" />';
		echo "\n";
	}
	?>
	<script type="text/javascript">
        document.payment_form.submit();
	</script>
</form>

