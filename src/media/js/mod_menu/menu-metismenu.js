/* Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later



 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: MokoCassiopeia
 PATH: ./media/templates/site/moko-cassiopeia/js/mod_menu/menu-metismenu.js
 VERSION: 03.05.00
 BRIEF: Modern MetisMenu script for Joomla mod_menu in MokoCassiopeia
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

