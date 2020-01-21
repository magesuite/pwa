<?php

namespace MageSuite\Pwa\Test\Integration\Controller\Manifest;

class IndexTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    public function testOpenManifestUrl()
    {
        $this->dispatch("manifest.json");
        $this->assertEquals(200, $this->getResponse()->getHttpResponseCode());
    }
}