<?php

namespace MageSuite\Pwa\Controller\Redirect;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected \Magento\Customer\Model\Session $customerSession;
    protected \MageSuite\Pwa\Helper\Configuration $configuration;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MageSuite\Pwa\Helper\Configuration $configuration,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->configuration = $configuration;
    }

    public function execute()
    {
        $startUrl = $this->customerSession->isLoggedIn() ?
            $this->configuration->getStartUrlForLoggedIn() :
            $this->configuration->getStartUrlForNotLoggedIn();

        $startUrl = $this->addUtmParameterToUrl($startUrl);

        $this->_redirect($startUrl);
    }

    protected function addUtmParameterToUrl(string $url): string
    {
        $url_parts = parse_url($url); // phpcs:ignore

        if (isset($url_parts['query'])) {
            parse_str($url_parts['query'], $params); // phpcs:ignore
        } else {
            $params = [];
        }

        $params['utm_source'] = 'homescreen';

        $url_parts['query'] = http_build_query($params);

        return $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . '?' . $url_parts['query'];
    }
}
