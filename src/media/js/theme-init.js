/* Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: Moko-Cassiopeia
 PATH: ./media/templates/site/moko-cassiopeia/js/theme-init.js
 VERSION: 03.05.00
 BRIEF: Initialization script for Moko-Cassiopeia theme features and behaviors
 */

(function (win, doc) {
	"use strict";

	var storageKey = "theme"; // localStorage key
	var mql = win.matchMedia("(prefers-color-scheme: dark)");
	var root = doc.documentElement;

	/**
	 * Apply theme to <html>, syncing both data-bs-theme and data-aria-theme.
	 * @param {"light"|"dark"} theme
	 */
	function applyTheme(theme) {
		root.setAttribute("data-bs-theme", theme);
		root.setAttribute("data-aria-theme", theme);
		try { localStorage.setItem(storageKey, theme); } catch (e) {}
	}

	/**
	 * Clear stored preference so system preference is followed.
	 */
	function clearStored() {
		try { localStorage.removeItem(storageKey); } catch (e) {}
	}

	/**
	 * Determine system theme.
	 */
	function systemTheme() {
		return mql.matches ? "dark" : "light";
	}

	/**
	 * Initialize theme on load.
	 */
	function init() {
		var stored = null;
		try { stored = localStorage.getItem(storageKey); } catch (e) {}

		var theme = stored ? stored : systemTheme();
		applyTheme(theme);

		// Listen for system changes only if Auto mode (no stored)
		var onChange = function () {
			if (!localStorage.getItem(storageKey)) {
				applyTheme(systemTheme());
			}
		};
		if (typeof mql.addEventListener === "function") {
			mql.addEventListener("change", onChange);
		} else if (typeof mql.addListener === "function") {
			mql.addListener(onChange);
		}

		// Hook toggle UI if present
		var switchEl = doc.getElementById("themeSwitch");
		var autoBtn  = doc.getElementById("themeAuto");

		if (switchEl) {
			switchEl.checked = (theme === "dark");
			switchEl.addEventListener("change", function () {
				var choice = switchEl.checked ? "dark" : "light";
				applyTheme(choice);
			});
		}

		if (autoBtn) {
			autoBtn.addEventListener("click", function () {
				clearStored();
				applyTheme(systemTheme());
			});
		}
	}

	if (doc.readyState === "loading") {
		doc.addEventListener("DOMContentLoaded", init);
	} else {
		init();
	}
})(window, document);
