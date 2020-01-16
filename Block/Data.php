<?php

namespace MageSuite\Pwa\Block;

class Data extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, array $data = [])
    {
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
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
