/* Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later



 # FILE INFORMATION
 DEFGROUP: Joomla Template
 FILE: media/templates/site/moko-cassiopeia/js/gtm.js
 HEADER VERSION: 1.0
 VERSION: 2.0
 BRIEF: Safe, configurable Google Tag Manager loader for MokoCassiopeia.
 PATH: ./media/templates/site/moko-cassiopeia/js/gtm.js
 NOTE: Place the <noscript> fallback iframe in your HTML template (index.php). A JS file
			 cannot provide a true no-JS fallback by definition.
 VARIABLES:
	 - window.MOKO_GTM_ID (string)            // Optional global GTM container ID (e.g., "GTM-XXXXXXX")
	 - window.MOKO_GTM_OPTIONS (object)       // Optional global options (see JSDoc below)
	 - data- attributes on the script tag or <html>/<body>:
			 data-gtm-id, data-data-layer, data-debug, data-ignore-dnt,
			 data-env-auth, data-env-preview, data-block-on-dev
 */

/* global window, document, navigator */
(() => {
	"use strict";

	/**
	 * @typedef {Object} MokoGtmOptions
	 * @property {string} [id]                 GTM container ID (e.g., "GTM-XXXXXXX")
	 * @property {string} [dataLayerName]      Custom dataLayer name (default: "dataLayer")
	 * @property {boolean} [debug]             Log debug messages to console (default: false)
	 * @property {boolean} [ignoreDNT]         Ignore Do Not Track and always load (default: false)
	 * @property {boolean} [blockOnDev]        Block loading on localhost/*.test/127.0.0.1 (default: true)
	 * @property {string} [envAuth]            GTM Environment auth string (optional)
	 * @property {string} [envPreview]         GTM Environment preview name (optional)
	 * @property {Record<string,'granted'|'denied'>} [consentDefault]
	 *   Default Consent Mode v2 map. Keys like:
	 *     analytics_storage, ad_storage, ad_user_data, ad_personalization, functionality_storage, security_storage
	 *   (default: {analytics_storage:'granted', functionality_storage:'granted', security_storage:'granted'})
	 * @property {() => (Record<string, any>|void)} [pageVars]
	 *   Function returning extra page variables to push on init (optional)
	 */

	const PKG = "moko-gtm";
	const PREFIX = `[${PKG}]`;
	const WIN = window;

	// Public API placeholder (attached to window at the end)
	/** @type {{
	 *    init: (opts?: Partial<MokoGtmOptions>) => void,
	 *    setConsent: (updates: Record<string,'granted'|'denied'>) => void,
	 *    push: (...args:any[]) => void,
	 *    isLoaded: () => boolean,
	 *    config: () => Required<MokoGtmOptions>
	 *  }} */
	const API = {};

	// ---- Utilities ---------------------------------------------------------

	const isDevHost = () => {
		const h = WIN.location && WIN.location.hostname || "";
		return (
			h === "localhost" ||
			h === "127.0.0.1" ||
			h.endsWith(".local") ||
			h.endsWith(".test")
		);
	};

	const dntEnabled = () => {
		// Different browsers expose DNT differently; treat "1" or "yes" as enabled.
		const n = navigator;
		const v = (n.doNotTrack || n.msDoNotTrack || (n.navigator && n.navigator.doNotTrack) || "").toString().toLowerCase();
		return v === "1" || v === "yes";
	};

	const getCurrentScript = () => {
		// document.currentScript is best; fallback to last <script> whose src ends with /gtm.js
		const cs = document.currentScript;
		if (cs) return cs;
		const scripts = Array.from(document.getElementsByTagName("script"));
		return scripts.reverse().find(s => (s.getAttribute("src") || "").includes("/gtm.js")) || null;
	};

	const readDatasetCascade = (name) => {
		// Check <script>, <html>, <body>, then <meta name="moko:gtm-<name>">
		const script = getCurrentScript();
		const html = document.documentElement;
		const body = document.body;
		const meta = document.querySelector(`meta[name="moko:gtm-${name}"]`);
		return (
			(script && script.dataset && script.dataset[name]) ||
			(html && html.dataset && html.dataset[name]) ||
			(body && body.dataset && body.dataset[name]) ||
			(meta && meta.getAttribute("content")) ||
			null
		);
	};

	const parseBool = (v, fallback = false) => {
		if (v == null) return fallback;
		const s = String(v).trim().toLowerCase();
		if (["1","true","yes","y","on"].includes(s)) return true;
		if (["0","false","no","n","off"].includes(s)) return false;
		return fallback;
	};

	const debugLog = (...args) => {
		if (STATE.debug) {
			try { console.info(PREFIX, ...args); } catch (_) {}
		}
	};

	// ---- Configuration & State --------------------------------------------

	/** @type {Required<MokoGtmOptions>} */
	const STATE = {
		id: "",
		dataLayerName: "dataLayer",
		debug: false,
		ignoreDNT: false,
		blockOnDev: true,
		envAuth: "",
		envPreview: "",
		consentDefault: {
			analytics_storage: "granted",
			functionality_storage: "granted",
			security_storage: "granted",
			// The following default to "denied" unless the site explicitly opts-in:
			ad_storage: "denied",
			ad_user_data: "denied",
			ad_personalization: "denied",
		},
		pageVars: () => ({})
	};

	const mergeOptions = (base, extra = {}) => {
		const out = {...base};
		for (const k in extra) {
			if (!Object.prototype.hasOwnProperty.call(extra, k)) continue;
			const v = extra[k];
			if (v && typeof v === "object" && !Array.isArray(v)) {
				out[k] = {...(out[k] || {}), ...v};
			} else if (v !== undefined) {
				out[k] = v;
			}
		}
		return out;
	};

	const detectOptions = () => {
		// 1) Global window options
		/** @type {Partial<MokoGtmOptions>} */
		const globalOpts = (WIN.MOKO_GTM_OPTIONS && typeof WIN.MOKO_GTM_OPTIONS === "object") ? WIN.MOKO_GTM_OPTIONS : {};

		// 2) Dataset / meta
		const idFromData    = readDatasetCascade("id") || WIN.MOKO_GTM_ID || "";
		const dlFromData    = readDatasetCascade("dataLayer") || "";
		const dbgFromData   = readDatasetCascade("debug");
		const dntFromData   = readDatasetCascade("ignoreDnt");
		const devFromData   = readDatasetCascade("blockOnDev");
		const authFromData  = readDatasetCascade("envAuth") || "";
		const prevFromData  = readDatasetCascade("envPreview") || "";

		// 3) Combine
		/** @type {Partial<MokoGtmOptions>} */
		const detected = {
			id: idFromData || globalOpts.id || "",
			dataLayerName: dlFromData || globalOpts.dataLayerName || undefined,
			debug: parseBool(dbgFromData, !!globalOpts.debug),
			ignoreDNT: parseBool(dntFromData, !!globalOpts.ignoreDNT),
			blockOnDev: parseBool(devFromData, (globalOpts.blockOnDev ?? true)),
			envAuth: authFromData || globalOpts.envAuth || "",
			envPreview: prevFromData || globalOpts.envPreview || "",
			consentDefault: globalOpts.consentDefault || undefined,
			pageVars: typeof globalOpts.pageVars === "function" ? globalOpts.pageVars : undefined
		};

		return detected;
	};

	// ---- dataLayer / gtag helpers -----------------------------------------

	const ensureDataLayer = () => {
		const l = STATE.dataLayerName;
		WIN[l] = WIN[l] || [];
		return WIN[l];
	};

	/** gtag wrapper backed by dataLayer. */
	const gtag = (...args) => {
		const dl = ensureDataLayer();
		dl.push(arguments.length > 1 ? args : args[0]);
		debugLog("gtag push:", args);
	};

	API.push = (...args) => gtag(...args);

	API.setConsent = (updates) => {
		gtag("consent", "update", updates || {});
	};

	API.isLoaded = () => {
		const hasScript = !!document.querySelector('script[src*="googletagmanager.com/gtm.js"]');
		return hasScript;
	};

	API.config = () => ({...STATE});

	// ---- Loader ------------------------------------------------------------

	const buildEnvQuery = () => {
		const qp = [];
		if (STATE.envAuth) qp.push(`gtm_auth=${encodeURIComponent(STATE.envAuth)}`);
		if (STATE.envPreview) qp.push(`gtm_preview=${encodeURIComponent(STATE.envPreview)}`, "gtm_cookies_win=x");
		return qp.length ? `&${qp.join("&")}` : "";
	};

	const injectScript = () => {
		if (!STATE.id) {
			debugLog("GTM ID missing; aborting load.");
			return;
		}
		if (API.isLoaded()) {
			debugLog("GTM already loaded; skipping duplicate injection.");
			return;
		}

		// Standard GTM bootstrap timing event
		const dl = ensureDataLayer();
		dl.push({ "gtm.start": new Date().getTime(), event: "gtm.js" });

		const f = document.getElementsByTagName("script")[0];
		const j = document.createElement("script");
		j.async = true;
		j.src = `https://www.googletagmanager.com/gtm.js?id=${encodeURIComponent(STATE.id)}${STATE.dataLayerName !== "dataLayer" ? `&l=${encodeURIComponent(STATE.dataLayerName)}` : ""}${buildEnvQuery()}`;
		if (f && f.parentNode) {
			f.parentNode.insertBefore(j, f);
		} else {
			(document.head || document.documentElement).appendChild(j);
		}
		debugLog("Injected GTM script:", j.src);
	};

	const applyDefaultConsent = () => {
		// Consent Mode v2 default
		gtag("consent", "default", STATE.consentDefault);
		debugLog("Applied default consent:", STATE.consentDefault);
	};

	const pushInitialVars = () => {
		// Minimal page vars; allow site to add more via pageVars()
		const vars = {
			event: "moko.page_init",
			page_title: document.title || "",
			page_language: (document.documentElement && document.documentElement.lang) || "",
			...(typeof STATE.pageVars === "function" ? (STATE.pageVars() || {}) : {})
		};
		gtag(vars);
	};

	const shouldLoad = () => {
		if (!STATE.ignoreDNT && dntEnabled()) {
			debugLog("DNT is enabled; blocking GTM load (set ignoreDNT=true to override).");
			return false;
		}
		if (STATE.blockOnDev && isDevHost()) {
			debugLog("Development host detected; blocking GTM load (set blockOnDev=false to override).");
			return false;
		}
		return true;
	};

	// ---- Public init -------------------------------------------------------

	API.init = (opts = {}) => {
		// Merge: defaults <- detected <- passed opts
		const detected = detectOptions();
		const merged = mergeOptions(STATE, mergeOptions(detected, opts));

		// Commit back to STATE
		Object.assign(STATE, merged);

		debugLog("Config:", STATE);

		// Prepare dataLayer/gtag and consent
		ensureDataLayer();
		applyDefaultConsent();
		pushInitialVars();

		// Load GTM if allowed
		if (shouldLoad()) {
			injectScript();
		} else {
			debugLog("GTM load prevented by configuration or environment.");
		}
	};

	// ---- Auto-init on DOMContentLoaded (safe even if deferred) -------------

	const autoInit = () => {
		// Only auto-init if we have some ID from globals/datasets.
		const detected = detectOptions();
		const hasId = !!(detected.id || WIN.MOKO_GTM_ID);
		if (hasId) {
			API.init(); // use detected/global defaults
		} else {
			debugLog("No GTM ID detected; awaiting manual init via window.mokoGTM.init({ id: 'GTM-XXXXXXX' }).");
		}
	};

	if (document.readyState === "complete" || document.readyState === "interactive") {
		// Defer to ensure <body> exists for any late consumers.
		setTimeout(autoInit, 0);
	} else {
		document.addEventListener("DOMContentLoaded", autoInit, { once: true });
	}

	// Expose API
	WIN.mokoGTM = API;

	// Helpful console hint (only if debug true after detection)
	try {
		const detected = detectOptions();
		if (parseBool(detected.debug, false)) {
			STATE.debug = true;
			debugLog("Ready. You can call window.mokoGTM.init({ id: 'GTM-XXXXXXX' }).");
		}
	} catch (_) {}
})();
