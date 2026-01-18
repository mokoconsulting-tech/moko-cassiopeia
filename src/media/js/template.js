/* Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: Moko-Cassiopeia
 PATH: ./media/templates/site/moko-cassiopeia/js/template.js
 VERSION: 03.05.00
 BRIEF: Core JavaScript utilities and behaviors for Moko-Cassiopeia template
 */

(function (win, doc) {
	"use strict";

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
	 * Requires bootstrap-toc.min.js to be loaded.
	 */
	function initTOC() {
		if (typeof win.Toc === "function" && doc.querySelector("#toc")) {
			win.Toc.init({
				$nav: $("#toc"),
				$scope: $("main")
			});
		}
	}

	/**
	 * Initialize offcanvas drawer buttons for left/right drawers.
	 * Uses Bootstrap's offcanvas component.
	 */
	function initDrawers() {
		var leftBtn = doc.querySelector(".drawer-toggle-left");
		var rightBtn = doc.querySelector(".drawer-toggle-right");
		if (leftBtn) {
			leftBtn.addEventListener("click", function () {
				var target = doc.querySelector(leftBtn.getAttribute("data-bs-target"));
				if (target) new bootstrap.Offcanvas(target).show();
			});
		}
		if (rightBtn) {
			rightBtn.addEventListener("click", function () {
				var target = doc.querySelector(rightBtn.getAttribute("data-bs-target"));
				if (target) new bootstrap.Offcanvas(target).show();
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
	 * Run all template JS initializations
	 */
	function init() {
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
