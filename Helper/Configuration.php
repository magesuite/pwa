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
        $config = $this->getConfig();

        return (bool)$config->getEnabled();
    }

    public function getName()
    {
        $config = $this->getConfig();

        return (string)$config->getName();
    }

    public function getShortName()
    {
        $config = $this->getConfig();

        return (string)$config->getShortName();
    }

    public function getDescription()
    {
        $config = $this->getConfig();

        return (string)$config->getDescription();
    }

    public function getStartUrl()
    {
        $config = $this->getConfig();

        $pageIdentifier = $config->getStartUrl();

        if (empty($pageIdentifier)) {
            return $this->_getUrl('');
        }

        $url = $this->pageHelper->getPageUrl($pageIdentifier);

        if (empty($url)) {
            return $this->_getUrl('');
        }

        return $url;
    }

    public function getDisplayMode()
    {
        $config = $this->getConfig();

        return (string)$config->getDisplayMode();
    }

    public function getBackgroundColor()
    {
        $config = $this->getConfig();

        return (string)$config->getBackgroundColor();
    }

    public function getThemeColor()
    {
        $config = $this->getConfig();

        return (string)$config->getThemeColor();
    }

    public function isDebugModeEnabled()
    {
        $config = $this->getConfig();

        return (bool)$config->getDebugModeEnabled();
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
}
