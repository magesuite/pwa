<?php

namespace MageSuite\Pwa\Test\Integration\Controller\ServiceWorker;

class IndexTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    public function testOpenManifestUrl()
    {
        $this->dispatch("service-worker.js");
        $this->assertEquals(200, $this->getResponse()->getHttpResponseCode());
    }
}