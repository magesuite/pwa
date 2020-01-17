<?php

namespace MageSuite\Pwa\Model\Data;

class ViewModel implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager, array $data = [])
    {
        $this->storeManager = $storeManager;
    }

    public function getName()
    {
        return $this->storeManager->getStore()->getName();
    }

    public function getShortName()
    {
        return $this->storeManager->getStore()->getName();
    }

    public function getStartUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }
}
