<?php
/* Copyright (C) 2026 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later



 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: MokoCassiopeia
 REPO: https://github.com/mokoconsulting-tech/MokoCassiopeia
 PATH: ./templates/mokocassiopeia/custom.php
 VERSION: 03.06.02
 BRIEF: MokoCassiopeia with user-defined overrides
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
<!--
	Custom code included here
-->
