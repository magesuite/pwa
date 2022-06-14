<?php

namespace MageSuite\Pwa\Model\Config\Source;

class CmsPage extends \Magento\Cms\Model\Config\Source\Page
{
    public function toOptionArray()
    {
        $options = parent::toOptionArray();

        return [
            [
                'label' => 'Other',
                'value' => [
                    ['label' => 'Custom URL', 'value' => 'custom']
                ]
            ],
            [
                'label' => 'CMS pages',
                'value' => array_merge(
                    [
                        [
                        'label' => 'Default CMS Page',
                        'value' => ''
                            ]
                    ],
                    $options
                )
            ]
        ];
    }
}
