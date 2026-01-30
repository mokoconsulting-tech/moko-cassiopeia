/* Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: Moko-Cassiopeia
 PATH: ./media/templates/site/moko-cassiopeia/js/template.js
 VERSION: 03.06.01
 BRIEF: Consolidated JavaScript for Moko-Cassiopeia template including theme, TOC, and utilities
 */

(function (win, doc) {
	"use strict";

	// ========================================================================
	// BOOTSTRAP TOC (inline minified version)
	// ========================================================================
	!function(a){"use strict";window.Toc={helpers:{findOrFilter:function(e,t){var n=e.find(t);return e.filter(t).add(n).filter(":not([data-toc-skip])")},generateUniqueIdBase:function(e){return a(e).text().trim().replace(/\'/gi,"").replace(/[& +$,:;=?@"#{}|^~[`%!'<>\]\.\/\(\)\*\\\n\t\b\v]/g,"-").replace(/-{2,}/g,"-").substring(0,64).replace(/^-+|-+$/gm,"").toLowerCase()||e.tagName.toLowerCase()},generateUniqueId:function(e){for(var t=this.generateUniqueIdBase(e),n=0;;n++){var r=t;if(0<n&&(r+="-"+n),!document.getElementById(r))return r}},generateAnchor:function(e){if(e.id)return e.id;var t=this.generateUniqueId(e);return e.id=t},createNavList:function(){return a('<ul class="nav navbar-nav"></ul>')},createChildNavList:function(e){var t=this.createNavList();return e.append(t),t},generateNavEl:function(e,t){var n=a('<a class="nav-link"></a>');n.attr("href","#"+e),n.text(t);var r=a("<li></li>");return r.append(n),r},generateNavItem:function(e){var t=this.generateAnchor(e),n=a(e),r=n.data("toc-text")||n.text();return this.generateNavEl(t,r)},getTopLevel:function(e){for(var t=1;t<=6;t++){if(1<this.findOrFilter(e,"h"+t).length)return t}return 1},getHeadings:function(e,t){var n="h"+t,r="h"+(t+1);return this.findOrFilter(e,n+","+r)},getNavLevel:function(e){return parseInt(e.tagName.charAt(1),10)},populateNav:function(r,a,e){var i,s=r,c=this;e.each(function(e,t){var n=c.generateNavItem(t);c.getNavLevel(t)===a?s=r:i&&s===r&&(s=c.createChildNavList(i)),s.append(n),i=n})},parseOps:function(e){var t;return(t=e.jquery?{$nav:e}:e).$scope=t.$scope||a(document.body),t}},init:function(e){(e=this.helpers.parseOps(e)).$nav.attr("data-toggle","toc");var t=this.helpers.createChildNavList(e.$nav),n=this.helpers.getTopLevel(e.$scope),r=this.helpers.getHeadings(e.$scope,n);this.helpers.populateNav(t,n,r)}},a(function(){a('nav[data-toggle="toc"]').each(function(e,t){var n=a(t);Toc.init(n)})})}(jQuery);

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
	 * Initialize Bootstrap TOC if #toc element exists.
	 */
	function initTOC() {
		if (typeof win.Toc !== "undefined" && doc.querySelector("#toc")) {
			win.Toc.init({
				$nav: $("#toc"),
				$scope: $("main")
			});
		}
	}

	/**
	 * Initialize offcanvas drawer buttons for left/right drawers.
	 */
	function initDrawers() {
		var leftBtn = doc.querySelector(".drawer-toggle-left");
		var rightBtn = doc.querySelector(".drawer-toggle-right");
		if (leftBtn) {
			leftBtn.addEventListener("click", function () {
				var target = doc.querySelector(leftBtn.getAttribute("data-bs-target"));
				if (target && typeof bootstrap !== 'undefined') new bootstrap.Offcanvas(target).show();
			});
		}
		if (rightBtn) {
			rightBtn.addEventListener("click", function () {
				var target = doc.querySelector(rightBtn.getAttribute("data-bs-target"));
				if (target && typeof bootstrap !== 'undefined') new bootstrap.Offcanvas(target).show();
			});
		}
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
		initTOC();
		initDrawers();
		initBackTop();
	}

	if (doc.readyState === "loading") {
		doc.addEventListener("DOMContentLoaded", init);
	} else {
		init();
	}
})(window, document);
