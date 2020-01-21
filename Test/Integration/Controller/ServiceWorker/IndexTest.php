<?php

namespace MageSuite\Pwa\Test\Integration\Controller\ServiceWorker;

class IndexTest extends \Magento\TestFramework\TestCase\AbstractBackendController
{
    public function testOpenServiceWorkerUrl()
    {
        $this->dispatch("service-worker.js");
        $this->assertEquals(200, $this->getResponse()->getHttpResponseCode());
    }
}