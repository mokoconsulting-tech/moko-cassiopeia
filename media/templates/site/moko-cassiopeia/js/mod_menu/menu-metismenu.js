/**
 * @package     Joomla.Site
 * @subpackage  Templates.Moko-Cassiopeia
 * @file 				/media/templates/site/moko-cassiopeia/js/mod_menu/menu-metismenu.js
 * @copyright 	(C) 2025 Jonathan Miler || Moko Consulting <https://mokoconsulting.tech>
 * @website: 		https://mokoconsulting.tech
 * @email: 			hello@mokoconsulting.tech
 * @phone: 			+1 (931) 279-6313
 * @license   	GNU General Public License version 2 or later; see LICENSE.txt
 * @note 				This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 */

document.addEventListener('DOMContentLoaded', () => {
	const allMenus = document.querySelectorAll('ul.mod-menu_dropdown-metismenu');
	allMenus.forEach(menu => {
		// eslint-disable-next-line no-new, no-undef
		const mm = new MetisMenu(menu, {
			triggerElement: 'button.mm-toggler'
		}).on('shown.metisMenu', event => {
			window.addEventListener('click', function mmClick(e) {
				if (!event.target.contains(e.target)) {
					mm.hide(event.detail.shownElement);
					window.removeEventListener('click', mmClick);
				}
			});
		});
	});
});

