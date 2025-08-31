/* =========================================================================
 * Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>
 *
 * This file is part of a Moko Consulting project.
 *
 * SPDX-License-Identifier: GPL-3.0-or-later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://www.gnu.org/licenses/ .
 * =========================================================================
 * FILE INFORMATION
 * DEFGROUP: Joomla
 * INGROUP: Moko-Cassiopeia
 * PATH: media/templates/site/moko-cassiopeia/js/mod_menu/menu-metismenu.js
 * VERSION: 02.01.05-dev
 * BRIEF: Modern MetisMenu script for Joomla mod_menu in Moko-Cassiopeia
 * =========================================================================
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

