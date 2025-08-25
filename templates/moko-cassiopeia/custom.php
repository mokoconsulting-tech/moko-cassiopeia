<?php
/**
 *
 * @package     Joomla.Site
 * @subpackage  Templates.Moko-Cassiopeia
 * @file        /templates/moko-cassiopeia/custom.php
 * @version     02.00
 * @copyright   © 2025 Moko Consulting
 * @author      Jonathan Miller
 * @website     https://mokoconsulting.tech
 * @email       hello@mokoconsulting.tech
 * @phone       +1 (931) 279-6313
 *
 * SPDX-License-Identifier: GPL-3.0-or-later
 *
 * This file is part of a Moko Consulting project released under the
 * GNU General Public License v3 or (at your option) any later version.
 * It is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
	function console_log($output, $with_script_tags = true) {
		$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
	');';
		if ($with_script_tags) {
			$js_code = '<script>' . $js_code . '</script>';
		}
		echo $js_code;
	}
?>
Custom code included here
