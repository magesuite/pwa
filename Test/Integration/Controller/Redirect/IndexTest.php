<?php

namespace MageSuite\Pwa\Test\Integration\Controller\Redirect;

class IndexTest extends \Magento\TestFramework\TestCase\AbstractController
{
    public function testItRedirectsToDefaultCmsPageByDefault()
    {
        $this->dispatch("magesuite-pwa/redirect/index");

        $this->assertEquals('http://localhost/index.php/?utm_source=homescreen', $this->getRedirectUrl());
        $this->assertTrue($this->getResponse()->isRedirect());
    }

    /**
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testItRedirectsToDefaultCmsPageByDefaultAndUserIsLoggedIn()
    {
        $this->loginCustomer();

        $this->dispatch("magesuite-pwa/redirect/index");

        $this->assertEquals('http://localhost/index.php/?utm_source=homescreen', $this->getRedirectUrl());
        $this->assertTrue($this->getResponse()->isRedirect());
    }

    /**
     * @magentoDataFixture Magento/Cms/_files/pages.php
     * @magentoConfigFixture current_store pwa/general/start_url page100
     */
    public function testItRedirectsToDifferentCmsPageUrlWhenDefined()
    {
        $this->dispatch("magesuite-pwa/redirect/index");

        $this->assertEquals('http://localhost/index.php/page100?utm_source=homescreen', $this->getRedirectUrl());
        $this->assertTrue($this->getResponse()->isRedirect());
    }

    /**
     * @magentoDataFixture Magento/Cms/_files/pages.php
     * @magentoConfigFixture current_store pwa/general/start_url custom
     * @magentoConfigFixture current_store pwa/general/custom_start_url customer/account/index
     */
    public function testItRedirectsToCustomUrlWhenItsDefined()
    {
        $this->dispatch("magesuite-pwa/redirect/index");

        $this->assertEquals('http://localhost/index.php/customer/account/index/?utm_source=homescreen', $this->getRedirectUrl());
        $this->assertTrue($this->getResponse()->isRedirect());
    }

    /**
     * @magentoDataFixture Magento/Cms/_files/pages.php
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoConfigFixture current_store pwa/general/start_url_when_logged_in page100
     */
    public function testItRedirectsToDifferentCmsPageUrlWhenDefinedAndUserIsLoggedIn()
    {
        $this->loginCustomer();

        $this->dispatch("magesuite-pwa/redirect/index");

        $this->assertEquals('http://localhost/index.php/page100?utm_source=homescreen', $this->getRedirectUrl());
        $this->assertTrue($this->getResponse()->isRedirect());
    }

    /**
     * @magentoDataFixture Magento/Cms/_files/pages.php
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoConfigFixture current_store pwa/general/start_url_when_logged_in custom
     * @magentoConfigFixture current_store pwa/general/custom_start_url_when_logged_in customer/account/index
     */
    public function testItRedirectsToCustomUrlWhenItsDefinedAndUserIsLoggedIn()
    {
        $this->loginCustomer();

        $this->dispatch("magesuite-pwa/redirect/index");

        $this->assertEquals('http://localhost/index.php/customer/account/index/?utm_source=homescreen', $this->getRedirectUrl());
        $this->assertTrue($this->getResponse()->isRedirect());
    }

    public function testItSetsPwaCookieCorrectly()
    {
        $this->dispatch("magesuite-pwa/redirect/index");
        $this->assertIsArray($_COOKIE); //phpcs:ignore
        $this->assertArrayHasKey('pwa', $_COOKIE); //phpcs:ignore
        $this->assertEquals('true', $_COOKIE['pwa']); //phpcs:ignore
    }

    /**
     * @return mixed
     */
    protected function getRedirectUrl()
    {
        return (string)$this->getResponse()->getHeader('Location')->uri();
    }

    /**
     * @return void
     */
    protected function loginCustomer(): void
    {
        $session = $this->_objectManager->get(\Magento\Customer\Model\Session::class);
        $session->loginById(1);
    }
}
