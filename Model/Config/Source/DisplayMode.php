<?php

namespace MageSuite\Pwa\Model\Config\Source;

class DisplayMode implements \Magento\Framework\Option\ArrayInterface
{
    const OPTION_FULLSCREEN = 'fullscreen';
    const OPTION_STANDALONE = 'standalone';
    const OPTION_MINIMAL_UI = 'minimal-ui';
    const OPTION_BROWSER = 'browser';

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => 'Fullscreen',
                'value' => self::OPTION_FULLSCREEN
            ],
            [
                'label' => 'Standalone',
                'value' => self::OPTION_STANDALONE
            ],
            [
                'label' => 'Minimal UI',
                'value' => self::OPTION_MINIMAL_UI
            ],
            [
                'label' => 'Browser',
                'value' => self::OPTION_BROWSER
            ],
        ];
    }
}
