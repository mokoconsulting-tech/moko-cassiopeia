<?php

namespace Tests\Unit;

use Codeception\Test\Unit;
use Tests\Support\UnitTester;

/**
 * Sample unit test for template functionality
 */
class TemplateConfigurationTest extends Unit
{
    protected UnitTester $tester;

    /**
     * Test that template has valid configuration structure
     */
    public function testTemplateManifestExists()
    {
        $manifestPath = __DIR__ . '/../../src/templates/templateDetails.xml';
        
        // Check if manifest file exists
        $this->assertFileExists(
            $manifestPath,
            'Template manifest file should exist'
        );
    }

    /**
     * Test that manifest is valid XML
     */
    public function testManifestIsValidXml()
    {
        $manifestPath = __DIR__ . '/../../src/templates/templateDetails.xml';
        
        if (!file_exists($manifestPath)) {
            $this->markTestSkipped('Manifest file not found');
        }

        $xml = @simplexml_load_file($manifestPath);
        
        $this->assertNotFalse(
            $xml,
            'Template manifest should be valid XML'
        );
    }

    /**
     * Test that template has required fields in manifest
     */
    public function testManifestHasRequiredFields()
    {
        $manifestPath = __DIR__ . '/../../src/templates/templateDetails.xml';
        
        if (!file_exists($manifestPath)) {
            $this->markTestSkipped('Manifest file not found');
        }

        $xml = simplexml_load_file($manifestPath);
        
        // Check for required elements
        $this->assertNotEmpty((string)$xml->name, 'Template should have a name');
        $this->assertNotEmpty((string)$xml->version, 'Template should have a version');
        $this->assertNotEmpty((string)$xml->author, 'Template should have an author');
    }
}
