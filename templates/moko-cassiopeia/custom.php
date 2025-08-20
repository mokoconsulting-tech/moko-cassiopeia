<?php

/**
 * @package     Joomla.Site
 * @subpackage  Templates.moko-cassiopeia
 * @file        /templates/moko-cassiopeia/custom.php
 *
 * @copyright   © 2025 Moko Consulting — All Rights Reserved
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * Website: https://mokoconsulting.tech
 * Email: hello@mokoconsulting.tech
 * Phone: +1 (931) 279-6313
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
