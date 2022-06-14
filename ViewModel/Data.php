<?php
declare(strict_types=1);

namespace MageSuite\Pwa\ViewModel;

class Data implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \MageSuite\Pwa\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\Pwa\Helper\Configuration $configuration,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->configuration = $configuration;
        $this->urlBuilder = $urlBuilder;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function getMediaBaseUrl()
    {
        return $this->urlBuilder->getBaseUrl([
            '_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ]);
    }

    public function getStoreBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }
}
