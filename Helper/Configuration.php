<?php

namespace MageSuite\Pwa\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $config = null;

    const XML_CONFIG_PATH = 'pwa/general';

    /**
     * @var \Magento\Cms\Helper\Page
     */
    protected $pageHelper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Cms\Helper\Page $pageHelper
    ) {
        parent::__construct($context);

        $this->pageHelper = $pageHelper;
    }

    public function isEnabled()
    {
        return (bool)$this->getConfig()->getIsEnabled();
    }

    public function getName()
    {
        return (string)$this->getConfig()->getName();
    }

    public function getShortName()
    {
        return (string)$this->getConfig()->getShortName();
    }

    public function getDescription()
    {
        return (string)$this->getConfig()->getDescription();
    }

    public function getStartUrl()
    {
        $pageIdentifier = $this->getConfig()->getStartUrl();

        return $this->getPageUrl($pageIdentifier);
    }

    public function getOfflinePageUrl()
    {
        $pageIdentifier = $this->getConfig()->getOfflinePageUrl();

        return $this->getPageUrl($pageIdentifier);
    }

    public function getDisplayMode()
    {
        return (string)$this->getConfig()->getDisplayMode();
    }

    public function getBackgroundColor()
    {
        return (string)$this->getConfig()->getBackgroundColor();
    }

    public function getThemeColor()
    {
        return (string)$this->getConfig()->getThemeColor();
    }

    public function isDebugModeEnabled()
    {
        return (bool)$this->getConfig()->getDebugModeIsEnabled();
    }

    protected function getConfig()
    {
        if ($this->config === null) {
            $this->config = new \Magento\Framework\DataObject(
                $this->scopeConfig->getValue(self::XML_CONFIG_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
            );
        }

        return $this->config;
    }

    public function getPageUrl($pageIdentifier)
    {
        if (empty($pageIdentifier)) {
            return $this->_getUrl('');
        }

        $url = $this->pageHelper->getPageUrl($pageIdentifier);

        if (empty($url)) {
            return $this->_getUrl('');
        }

        return $url;
    }
}
