/* Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later



 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: MokoCassiopeia
 PATH: ./media/templates/site/moko-cassiopeia/js/template.js
 VERSION: 03.06.01
 BRIEF: Consolidated JavaScript for MokoCassiopeia template including theme and utilities
 */

(function (win, doc) {
	"use strict";

	// ========================================================================
	// THEME INITIALIZATION (Early theme application)
	// ========================================================================
	var storageKey = "theme";
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
	 * Get stored theme preference.
	 */
	function getStored() {
		try { return localStorage.getItem(storageKey); } catch (e) { return null; }
	}

	// ========================================================================
	// FLOATING THEME TOGGLE (FAB)
	// ========================================================================
	function posClassFromBody() {
		var pos = (doc.body.getAttribute('data-theme-fab-pos') || 'br').toLowerCase();
		if (!/^(br|bl|tr|tl)$/.test(pos)) pos = 'br';
		return 'pos-' + pos;
	}

	function buildThemeToggle() {
		if (doc.getElementById('mokoThemeFab')) return;

		var wrap = doc.createElement('div');
		wrap.id = 'mokoThemeFab';
		wrap.className = posClassFromBody();

		// Light label
		var lblL = doc.createElement('span');
		lblL.className = 'label';
		lblL.textContent = 'Light';

		// Switch
		var switchWrap = doc.createElement('button');
		switchWrap.id = 'mokoThemeSwitch';
		switchWrap.type = 'button';
		switchWrap.setAttribute('role', 'switch');
		switchWrap.setAttribute('aria-label', 'Toggle dark mode');
		switchWrap.setAttribute('aria-checked', 'false');

		var track = doc.createElement('span');
		track.className = 'switch';
		var knob = doc.createElement('span');
		knob.className = 'knob';
		track.appendChild(knob);
		switchWrap.appendChild(track);

		// Dark label
		var lblD = doc.createElement('span');
		lblD.className = 'label';
		lblD.textContent = 'Dark';

		// Auto button
		var auto = doc.createElement('button');
		auto.id = 'mokoThemeAuto';
		auto.type = 'button';
		auto.className = 'btn btn-sm btn-link text-decoration-none px-2';
		auto.setAttribute('aria-label', 'Follow system theme');
		auto.textContent = 'Auto';

		// Behavior
		switchWrap.addEventListener('click', function () {
			var current = (root.getAttribute('data-bs-theme') || 'light').toLowerCase();
			var next = current === 'dark' ? 'light' : 'dark';
			applyTheme(next);
			switchWrap.setAttribute('aria-checked', next === 'dark' ? 'true' : 'false');
			// Update meta theme color
			var meta = doc.querySelector('meta[name="theme-color"]');
			if (meta) {
				meta.setAttribute('content', next === 'dark' ? '#0f1115' : '#ffffff');
			}
		});

		auto.addEventListener('click', function () {
			clearStored();
			var sys = systemTheme();
			applyTheme(sys);
			switchWrap.setAttribute('aria-checked', sys === 'dark' ? 'true' : 'false');
		});

		// Respond to OS changes only when not user-forced
		var onMql = function () {
			if (!getStored()) {
				var sys = systemTheme();
				applyTheme(sys);
				switchWrap.setAttribute('aria-checked', sys === 'dark' ? 'true' : 'false');
			}
		};
		if (typeof mql.addEventListener === 'function') mql.addEventListener('change', onMql);
		else if (typeof mql.addListener === 'function') mql.addListener(onMql);

		// Initial state
		var initial = getStored() || systemTheme();
		switchWrap.setAttribute('aria-checked', initial === 'dark' ? 'true' : 'false');

		// Mount
		wrap.appendChild(lblL);
		wrap.appendChild(switchWrap);
		wrap.appendChild(lblD);
		wrap.appendChild(auto);
		doc.body.appendChild(wrap);

		// Debug helper
		win.mokoThemeFabStatus = function () {
			var el = doc.getElementById('mokoThemeFab');
			if (!el) return { mounted: false };
			var r = el.getBoundingClientRect();
			return {
				mounted: true,
				rect: { top: r.top, left: r.left, width: r.width, height: r.height },
				zIndex: win.getComputedStyle(el).zIndex,
				posClass: el.className
			};
		};

		// Outline if invisible
		setTimeout(function () {
			var r = wrap.getBoundingClientRect();
			if (r.width < 10 || r.height < 10) {
				wrap.classList.add('debug-outline');
				console.warn('[moko] Theme FAB mounted but appears too small — check CSS collisions.');
			}
		}, 50);
	}

	// ========================================================================
	// TEMPLATE UTILITIES
	// ========================================================================

	/**
	 * Utility: smooth scroll to top
	 */
	function backToTop() {
		win.scrollTo({ top: 0, behavior: "smooth" });
	}

	/**
	 * Utility: toggle body class on scroll for sticky header styling
	 */
	function handleScroll() {
		if (win.scrollY > 50) {
			doc.body.classList.add("scrolled");
		} else {
			doc.body.classList.remove("scrolled");
		}
	}

	/**
	 * Initialize offcanvas drawer buttons for left/right drawers.
	 * Bootstrap handles drawers automatically via data-bs-toggle="offcanvas"
	 * This function is kept for backwards compatibility but only runs if drawers exist.
	 */
	function initDrawers() {
		// Check if any drawer buttons exist before initializing
		var hasDrawers = doc.querySelector(".drawer-toggle-left") || doc.querySelector(".drawer-toggle-right");
		if (!hasDrawers) {
			return; // No drawers, skip initialization
		}

		// Bootstrap 5 handles offcanvas automatically via data-bs-toggle attribute
		// No manual initialization needed if Bootstrap is loaded correctly
		// The buttons already have data-bs-toggle="offcanvas" and data-bs-target="#drawer-*"
	}

	/**
	 * Initialize back-to-top link if present
	 */
	function initBackTop() {
		var backTop = doc.getElementById("back-top");
		if (backTop) {
			backTop.addEventListener("click", function (e) {
				e.preventDefault();
				backToTop();
			});
		}
	}

	/**
	 * Initialize theme based on stored preference or system setting
	 */
	function initTheme() {
		var stored = getStored();
		var theme = stored ? stored : systemTheme();
		applyTheme(theme);

		// Listen for system changes only if Auto mode (no stored)
		var onChange = function () {
			if (!getStored()) {
				applyTheme(systemTheme());
			}
		};
		if (typeof mql.addEventListener === "function") {
			mql.addEventListener("change", onChange);
		} else if (typeof mql.addListener === "function") {
			mql.addListener(onChange);
		}

		// Hook toggle UI if present (for inline switch, not FAB)
		var switchEl = doc.getElementById("themeSwitch");
		var autoBtn = doc.getElementById("themeAuto");

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

	/**
	 * Check if theme FAB should be enabled based on body data attribute
	 */
	function shouldEnableThemeFab() {
		return doc.body.getAttribute('data-theme-fab-enabled') === '1';
	}

	/**
	 * Run all template JS initializations
	 */
	function init() {
		// Initialize theme first
		initTheme();

		// Build floating theme toggle if enabled
		if (shouldEnableThemeFab()) {
			buildThemeToggle();
		}

		// Sticky header behavior
		handleScroll();
		win.addEventListener("scroll", handleScroll);

		// Init features
		initDrawers();
		initBackTop();
	}

	if (doc.readyState === "loading") {
		doc.addEventListener("DOMContentLoaded", init);
	} else {
		init();
	}
})(window, document);
