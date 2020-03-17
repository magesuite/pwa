<?php

namespace MageSuite\Pwa\Model\Config\Source;

class CmsPage extends \Magento\Cms\Model\Config\Source\Page
{
    public function toOptionArray()
    {
        $options = parent::toOptionArray();

        array_unshift(
            $options,
            [
                'value' => '',
                'label' => 'Default CMS Page'
            ]
        );

        return $options;
    }
}
