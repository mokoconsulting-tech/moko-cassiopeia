/**
 * darkmode-toggle.js — Floating theme switch (class-based, CSP-proof)
 * @version 2.2.1
 * Storage key: "theme" -> "light" | "dark"
 * Corner from <body data-theme-fab-pos="br|bl|tr|tl"> (default br)
 */
(function () {
  'use strict';

  var STORAGE_KEY = 'theme';
  var docEl = document.documentElement;
  var mql = window.matchMedia('(prefers-color-scheme: dark)');

  function getStored() { try { return localStorage.getItem(STORAGE_KEY); } catch (e) { return null; } }
  function setStored(v) { try { localStorage.setItem(STORAGE_KEY, v); } catch (e) {} }
  function clearStored() { try { localStorage.removeItem(STORAGE_KEY); } catch (e) {} }
  function systemTheme() { return mql.matches ? 'dark' : 'light'; }

  function applyTheme(theme) {
    docEl.setAttribute('data-bs-theme', theme);
    docEl.setAttribute('data-aria-theme', theme);
    var meta = document.querySelector('meta[name="theme-color"]');
    if (meta) {
      meta.setAttribute('content', theme === 'dark' ? '#0f1115' : '#ffffff');
    }
    var sw = document.getElementById('mokoThemeSwitch');
    if (sw) {
      sw.setAttribute('aria-checked', theme === 'dark' ? 'true' : 'false');
    }
  }

  function initTheme() {
    var stored = getStored();
    applyTheme(stored ? stored : systemTheme());
  }

  function posClassFromBody() {
    var pos = (document.body.getAttribute('data-theme-fab-pos') || 'br').toLowerCase();
    if (!/^(br|bl|tr|tl)$/.test(pos)) pos = 'br';
    return 'pos-' + pos;
  }

  function buildToggle() {
    if (document.getElementById('mokoThemeFab')) return;

    var wrap = document.createElement('div');
    wrap.id = 'mokoThemeFab';
    wrap.className = posClassFromBody();

    // Light label
    var lblL = document.createElement('span');
    lblL.className = 'label';
    lblL.textContent = 'Light';

    // Switch
    var switchWrap = document.createElement('button');
    switchWrap.id = 'mokoThemeSwitch';
    switchWrap.type = 'button';
    switchWrap.setAttribute('role', 'switch');
    switchWrap.setAttribute('aria-label', 'Toggle dark mode');
    switchWrap.setAttribute('aria-checked', 'false'); // updated after init

    var track = document.createElement('span');
    track.className = 'switch';
    var knob = document.createElement('span');
    knob.className = 'knob';
    track.appendChild(knob);
    switchWrap.appendChild(track);

    // Dark label
    var lblD = document.createElement('span');
    lblD.className = 'label';
    lblD.textContent = 'Dark';

    // Auto button
    var auto = document.createElement('button');
    auto.id = 'mokoThemeAuto';
    auto.type = 'button';
    auto.className = 'btn btn-sm btn-link text-decoration-none px-2';
    auto.setAttribute('aria-label', 'Follow system theme');
    auto.textContent = 'Auto';

    // Behavior
    switchWrap.addEventListener('click', function () {
      var current = (docEl.getAttribute('data-bs-theme') || 'light').toLowerCase();
      var next = current === 'dark' ? 'light' : 'dark';
      applyTheme(next);
      setStored(next);
    });

    auto.addEventListener('click', function () {
      clearStored();
      applyTheme(systemTheme());
    });

    // Respond to OS changes only when not user-forced
    var onMql = function () {
      if (!getStored()) applyTheme(systemTheme());
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
    document.body.appendChild(wrap);

    // Debug helper
    window.mokoThemeFabStatus = function () {
      var el = document.getElementById('mokoThemeFab');
      if (!el) return { mounted: false };
      var r = el.getBoundingClientRect();
      return {
        mounted: true,
        rect: { top: r.top, left: r.left, width: r.width, height: r.height },
        zIndex: window.getComputedStyle(el).zIndex,
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

  function init() {
    initTheme();
    buildToggle();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
