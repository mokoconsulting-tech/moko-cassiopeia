<?php
/* Copyright (C) 2025 Moko Consulting <hello@mokoconsulting.tech>

 This file is part of a Moko Consulting project.

 SPDX-License-Identifier: GPL-3.0-or-later

 # FILE INFORMATION
 DEFGROUP: Joomla.Template.Site
 INGROUP: Moko-Cassiopeia
 REPO: https://github.com/mokoconsulting-tech/moko-cassiopeia
 PATH: ./templates/moko-cassiopeia/AssetMinifier.php
 VERSION: 03.05.00
 BRIEF: Asset minification helper for development mode toggle
 */

defined('_JEXEC') or die;

/**
 * Asset Minifier Helper
 * 
 * Handles minification and cleanup of CSS and JavaScript assets
 * based on the development mode setting.
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
		
		// Remove whitespace
		$css = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $css);
		
		// Remove spaces around selectors and properties
		$css = preg_replace('/\s*([{}|:;,])\s*/', '$1', $css);
		
		// Remove trailing semicolons
		$css = str_replace(';}', '}', $css);
		
		return trim($css);
	}

	/**
	 * Minify JavaScript content
	 * 
	 * @param string $js JavaScript content to minify
	 * @return string Minified JavaScript
	 */
	public static function minifyJS(string $js): string
	{
		// Remove single-line comments (but preserve URLs)
		$js = preg_replace('~//[^\n]*\n~', "\n", $js);
		
		// Remove multi-line comments
		$js = preg_replace('~/\*.*?\*/~s', '', $js);
		
		// Remove whitespace
		$js = preg_replace('/\s+/', ' ', $js);
		
		// Remove spaces around operators and punctuation
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
				if (unlink($file->getPathname())) {
					$deleted++;
				}
			}
		}

		return $deleted;
	}

	/**
	 * Process assets based on development mode
	 * 
	 * @param string $mediaPath Path to media directory
	 * @param bool $developmentMode Development mode flag
	 * @return array Status information
	 */
	public static function processAssets(string $mediaPath, bool $developmentMode): array
	{
		$result = [
			'mode' => $developmentMode ? 'development' : 'production',
			'minified' => 0,
			'deleted' => 0,
			'errors' => []
		];

		if (!is_dir($mediaPath)) {
			$result['errors'][] = "Media path does not exist: {$mediaPath}";
			return $result;
		}

		if ($developmentMode) {
			// Delete all .min files
			$result['deleted'] = self::deleteMinifiedFiles($mediaPath);
		} else {
			// Create minified versions of CSS and JS files
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
