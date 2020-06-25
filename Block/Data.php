<?php

namespace MageSuite\Pwa\Block;

class Data extends \Magento\Framework\View\Element\Template
{
    public function getMediaBaseUrl() {
        return $this->_urlBuilder->getBaseUrl([
            '_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ]);
    }
}
