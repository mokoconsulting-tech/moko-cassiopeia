<?php
/* Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: Moko-Cassiopeia
 REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
 PATH: ./templates/moko-cassiopeia/AssetMinifier.php
 VERSION: 03.08.00
 BRIEF: Asset minification helper linked to Joomla cache system
 */

defined('_JEXEC') or die;

/**
 * Asset Minifier Helper
 * 
 * Handles minification and cleanup of CSS and JavaScript assets
 * based on the Joomla cache system setting.
 * 
 * IMPORTANT NOTES:
 * - This is a BASIC minifier suitable for cache-based switching
 * - For production builds, consider using professional tools like:
 *   * CSS: cssnano, clean-css
 *   * JavaScript: terser, uglify-js, closure-compiler
 * - URL preservation in JS is best-effort; complex cases may fail
 * - String content preservation is basic; edge cases may exist
 * - Does not handle complex string scenarios or regex patterns
 * 
 * The minifier is designed to be "good enough" for automatic switching
 * based on Joomla's cache setting, not for optimal compression.
 * 
 * BEHAVIOR:
 * - When Joomla cache is ENABLED: Uses minified (.min) files for performance
 * - When Joomla cache is DISABLED: Uses non-minified files for debugging
 */
class AssetMinifier
{
	/**
	 * Minify CSS content
	 * 
	 * @param string $css CSS content to minify
	 * @return string Minified CSS
	 */
	public static function minifyCSS(string $css): string
	{
		// Remove comments
		$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
		
		// Remove all whitespace and newlines
		$css = preg_replace('/\s+/', ' ', $css);
		
		// Remove spaces around selectors and properties
		$css = preg_replace('/\s*([{}|:;,])\s*/', '$1', $css);
		
		// Remove trailing semicolons
		$css = str_replace(';}', '}', $css);
		
		return trim($css);
	}

	/**
	 * Minify JavaScript content
	 * 
	 * Note: This is a basic minifier. For production use, consider using
	 * a more sophisticated minifier like terser or uglify-js.
	 * 
	 * @param string $js JavaScript content to minify
	 * @return string Minified JavaScript
	 */
	public static function minifyJS(string $js): string
	{
		// Remove single-line comments but preserve URLs (https://, http://)
		// The negative lookbehind (?<![:\'"a-zA-Z0-9]) ensures we don't match:
		// - URLs: https://example.com (preceded by :)
		// - String literals: "//comment" (preceded by quote)
		// - Protocol-relative URLs: //example.com (preceded by non-alphanumeric)
		$js = preg_replace('~(?<![:\'"a-zA-Z0-9])//[^\n]*\n~', "\n", $js);
		
		// Remove multi-line comments
		$js = preg_replace('~/\*.*?\*/~s', '', $js);
		
		// Normalize whitespace to single spaces
		$js = preg_replace('/\s+/', ' ', $js);
		
		// Remove spaces around operators and punctuation (but keep spaces in strings)
		$js = preg_replace('/\s*([\{\}\[\]\(\);,=<>!&|+\-*\/])\s*/', '$1', $js);
		
		return trim($js);
	}

	/**
	 * Create minified version of a file
	 * 
	 * @param string $sourcePath Path to source file
	 * @param string $destPath Path to minified file
	 * @return bool Success status
	 */
	public static function minifyFile(string $sourcePath, string $destPath): bool
	{
		if (!file_exists($sourcePath)) {
			return false;
		}

		$content = file_get_contents($sourcePath);
		if ($content === false) {
			return false;
		}

		$ext = pathinfo($sourcePath, PATHINFO_EXTENSION);
		
		if ($ext === 'css') {
			$minified = self::minifyCSS($content);
		} elseif ($ext === 'js') {
			$minified = self::minifyJS($content);
		} else {
			return false;
		}

		return file_put_contents($destPath, $minified) !== false;
	}

	/**
	 * Delete all minified files in a directory (recursive)
	 * Excludes vendor directory to preserve pre-minified vendor assets
	 * 
	 * @param string $dir Directory path
	 * @return int Number of files deleted
	 */
	public static function deleteMinifiedFiles(string $dir): int
	{
		$deleted = 0;
		
		if (!is_dir($dir)) {
			return 0;
		}

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
			RecursiveIteratorIterator::SELF_FIRST
		);

		foreach ($iterator as $file) {
			if ($file->isFile() && preg_match('/\.min\.(css|js)$/', $file->getFilename())) {
				// Skip vendor files as they come pre-minified from vendors
				if (strpos($file->getPathname(), '/vendor/') !== false) {
					continue;
				}
				
				if (unlink($file->getPathname())) {
					$deleted++;
				}
			}
		}

		return $deleted;
	}

	/**
	 * Process assets based on cache setting
	 * 
	 * When $useNonMinified is true (cache disabled), deletes .min files and uses source files
	 * When $useNonMinified is false (cache enabled), creates .min files and uses them
	 * 
	 * @param string $mediaPath Path to media directory
	 * @param bool $useNonMinified Whether to use non-minified files (true when cache disabled)
	 * @return array Status information
	 */
	public static function processAssets(string $mediaPath, bool $useNonMinified): array
	{
		$result = [
			'mode' => $useNonMinified ? 'cache-disabled' : 'cache-enabled',
			'minified' => 0,
			'deleted' => 0,
			'errors' => []
		];

		if (!is_dir($mediaPath)) {
			$result['errors'][] = "Media path does not exist: {$mediaPath}";
			return $result;
		}

		if ($useNonMinified) {
			// Cache disabled: Delete all .min files and use non-minified sources
			$result['deleted'] = self::deleteMinifiedFiles($mediaPath);
		} else {
			// Cache enabled: Create minified versions of CSS and JS files for performance
			// NOTE: This list is hardcoded for predictability and to ensure only
			// specific template files are minified. Vendor files are excluded as
			// they come pre-minified. If you add new template assets, add them here.
			$files = [
				'css/template.css' => 'css/template.min.css',
				'css/user.css' => 'css/user.min.css',
				'css/editor.css' => 'css/editor.min.css',
				'css/colors/light/colors_standard.css' => 'css/colors/light/colors_standard.min.css',
				'css/colors/light/colors_alternative.css' => 'css/colors/light/colors_alternative.min.css',
				'css/colors/light/colors_custom.css' => 'css/colors/light/colors_custom.min.css',
				'css/colors/dark/colors_standard.css' => 'css/colors/dark/colors_standard.min.css',
				'css/colors/dark/colors_alternative.css' => 'css/colors/dark/colors_alternative.min.css',
				'css/colors/dark/colors_custom.css' => 'css/colors/dark/colors_custom.min.css',
				'js/template.js' => 'js/template.min.js',
				'js/theme-init.js' => 'js/theme-init.min.js',
				'js/darkmode-toggle.js' => 'js/darkmode-toggle.min.js',
				'js/gtm.js' => 'js/gtm.min.js',
			];

			foreach ($files as $source => $dest) {
				$sourcePath = $mediaPath . '/' . $source;
				$destPath = $mediaPath . '/' . $dest;
				
				// Only minify if source exists and dest doesn't exist or is older
				if (file_exists($sourcePath)) {
					if (!file_exists($destPath) || filemtime($sourcePath) > filemtime($destPath)) {
						if (self::minifyFile($sourcePath, $destPath)) {
							$result['minified']++;
						} else {
							$result['errors'][] = "Failed to minify: {$source}";
						}
					}
				}
			}
		}

		return $result;
	}
}
