/**
 * @package     Joomla.Site
 * @subpackage  Templates.Moko-Cassiopeia
 * @file				/media/templates/site/moko-cassiopeia/js/mod_menu/menu-metismenu-es5.js
 * @copyright 	(C) 2025 Jonathan Miler || Moko Consulting <https://mokoconsulting.tech>
 * @website: 		https://mokoconsulting.tech
 * @email: 			hello@mokoconsulting.tech
 * @phone: 			+1 (931) 279-6313
 * @license   	GNU General Public License version 2 or later; see LICENSE.txt
 * @note 				This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 */

(function () {
	'use strict';

	/**
	 * @package     Joomla.Site
	 * @subpackage  Templates.Moko-Cassiopeia
	 * @copyright   (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
	 * @license     GNU General Public License version 2 or later; see LICENSE.txt
	 * @since       4.0.0
	 */

	document.addEventListener('DOMContentLoaded', function () {
		var allMenus = document.querySelectorAll('ul.mod-menu_dropdown-metismenu');
		allMenus.forEach(function (menu) {
			// eslint-disable-next-line no-new, no-undef
			var mm = new MetisMenu(menu, {
				triggerElement: 'button.mm-toggler'
			}).on('shown.metisMenu', function (event) {
				window.addEventListener('click', function mmClick(e) {
					if (!event.target.contains(e.target)) {
						mm.hide(event.detail.shownElement);
						window.removeEventListener('click', mmClick);
					}
				});
			});
		});
	});

})();

