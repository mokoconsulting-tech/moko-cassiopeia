<?php

namespace Tests\Unit;

use Codeception\Test\Unit;
use Tests\Support\UnitTester;

/**
 * Unit tests for AssetMinifier class
 */
class AssetMinifierTest extends Unit
{
    protected UnitTester $tester;
    
    private string $testDir;
    private string $testCssFile;
    private string $testJsFile;
    
    protected function _before()
    {
        // Create temporary test directory
        $this->testDir = sys_get_temp_dir() . '/moko-cassiopeia-test-' . uniqid();
        mkdir($this->testDir, 0777, true);
        
        $this->testCssFile = $this->testDir . '/test.css';
        $this->testJsFile = $this->testDir . '/test.js';
        
        // Load the AssetMinifier class
        require_once __DIR__ . '/../../src/templates/AssetMinifier.php';
    }
    
    protected function _after()
    {
        // Clean up test directory
        if (is_dir($this->testDir)) {
            $this->deleteDirectory($this->testDir);
        }
    }
    
    /**
     * Helper to recursively delete a directory
     */
    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
    
    /**
     * Test CSS minification
     */
    public function testMinifyCSS()
    {
        $css = "
            /* This is a comment */
            body {
                margin: 0;
                padding: 0;
                background-color: #ffffff;
            }
            
            .container {
                width: 100%;
                max-width: 1200px;
            }
        ";
        
        $minified = \AssetMinifier::minifyCSS($css);
        
        // Should remove comments
        $this->assertStringNotContainsString('/* This is a comment */', $minified);
        
        // Should remove whitespace
        $this->assertStringNotContainsString("\n", $minified);
        $this->assertStringNotContainsString("  ", $minified);
        
        // Should still contain the actual CSS
        $this->assertStringContainsString('body{', $minified);
        $this->assertStringContainsString('margin:0', $minified);
    }
    
    /**
     * Test JavaScript minification
     */
    public function testMinifyJS()
    {
        $js = "
            // This is a single-line comment
            function hello() {
                /* Multi-line
                   comment */
                console.log('Hello World');
                return true;
            }
        ";
        
        $minified = \AssetMinifier::minifyJS($js);
        
        // Should remove comments
        $this->assertStringNotContainsString('// This is a single-line comment', $minified);
        $this->assertStringNotContainsString('/* Multi-line', $minified);
        
        // Should still contain the function
        $this->assertStringContainsString('function hello()', $minified);
        $this->assertStringContainsString("console.log('Hello World')", $minified);
    }
    
    /**
     * Test minifying CSS file
     */
    public function testMinifyCSSFile()
    {
        $css = "body { margin: 0; padding: 0; }";
        file_put_contents($this->testCssFile, $css);
        
        $minFile = $this->testDir . '/test.min.css';
        $result = \AssetMinifier::minifyFile($this->testCssFile, $minFile);
        
        $this->assertTrue($result, 'Minification should succeed');
        $this->assertFileExists($minFile, 'Minified file should exist');
        
        $content = file_get_contents($minFile);
        $this->assertNotEmpty($content, 'Minified file should not be empty');
    }
    
    /**
     * Test minifying JavaScript file
     */
    public function testMinifyJSFile()
    {
        $js = "function test() { return true; }";
        file_put_contents($this->testJsFile, $js);
        
        $minFile = $this->testDir . '/test.min.js';
        $result = \AssetMinifier::minifyFile($this->testJsFile, $minFile);
        
        $this->assertTrue($result, 'Minification should succeed');
        $this->assertFileExists($minFile, 'Minified file should exist');
        
        $content = file_get_contents($minFile);
        $this->assertNotEmpty($content, 'Minified file should not be empty');
        $this->assertStringContainsString('function test()', $content);
    }
    
    /**
     * Test minifying non-existent file
     */
    public function testMinifyNonExistentFile()
    {
        $result = \AssetMinifier::minifyFile(
            $this->testDir . '/nonexistent.css',
            $this->testDir . '/output.min.css'
        );
        
        $this->assertFalse($result, 'Should return false for non-existent file');
    }
    
    /**
     * Test deleting minified files
     */
    public function testDeleteMinifiedFiles()
    {
        // Create some test files
        file_put_contents($this->testDir . '/file1.css', 'body{}');
        file_put_contents($this->testDir . '/file1.min.css', 'body{}');
        file_put_contents($this->testDir . '/file2.js', 'var x=1;');
        file_put_contents($this->testDir . '/file2.min.js', 'var x=1;');
        
        // Create subdirectory with minified files
        $subDir = $this->testDir . '/sub';
        mkdir($subDir);
        file_put_contents($subDir . '/sub.min.css', 'div{}');
        
        $deleted = \AssetMinifier::deleteMinifiedFiles($this->testDir);
        
        $this->assertGreaterThanOrEqual(3, $deleted, 'Should delete at least 3 minified files');
        $this->assertFileDoesNotExist($this->testDir . '/file1.min.css');
        $this->assertFileDoesNotExist($this->testDir . '/file2.min.js');
        $this->assertFileDoesNotExist($subDir . '/sub.min.css');
        
        // Non-minified files should still exist
        $this->assertFileExists($this->testDir . '/file1.css');
        $this->assertFileExists($this->testDir . '/file2.js');
    }
    
    /**
     * Test process assets with cache disabled (use non-minified)
     */
    public function testProcessAssetsCacheDisabled()
    {
        // Create some minified files
        file_put_contents($this->testDir . '/test.min.css', 'body{}');
        file_put_contents($this->testDir . '/test.min.js', 'var x=1;');
        
        // When cache is disabled, useNonMinified = true
        $result = \AssetMinifier::processAssets($this->testDir, true);
        
        $this->assertEquals('cache-disabled', $result['mode']);
        $this->assertGreaterThanOrEqual(2, $result['deleted'], 'Should delete minified files');
        $this->assertFileDoesNotExist($this->testDir . '/test.min.css');
        $this->assertFileDoesNotExist($this->testDir . '/test.min.js');
    }
    
    /**
     * Test process assets with cache enabled (use minified)
     */
    public function testProcessAssetsCacheEnabled()
    {
        // Create source files
        file_put_contents($this->testDir . '/test.css', 'body { margin: 0; }');
        file_put_contents($this->testDir . '/test.js', 'function test() { return true; }');
        
        // When cache is enabled, useNonMinified = false
        // This will try to minify files in the hardcoded list, which won't match our test files
        // So we just verify the mode is set correctly
        $result = \AssetMinifier::processAssets($this->testDir, false);
        
        $this->assertEquals('cache-enabled', $result['mode']);
        $this->assertEquals(0, $result['minified'], 'Should not minify test files (not in hardcoded list)');
    }
    
    /**
     * Test process assets returns error for non-existent directory
     */
    public function testProcessAssetsNonExistentDirectory()
    {
        $result = \AssetMinifier::processAssets('/nonexistent/path', false);
        
        $this->assertNotEmpty($result['errors']);
        $this->assertStringContainsString('does not exist', $result['errors'][0]);
    }
}
