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
    protected \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory;
    protected \Magento\Framework\Stdlib\CookieManagerInterface $cookieManagerInterface;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \MageSuite\Pwa\Helper\Configuration $configuration,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManagerInterface,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->configuration = $configuration;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->cookieManagerInterface = $cookieManagerInterface;
    }

    public function execute()
    {
        $startUrl = $this->customerSession->isLoggedIn() ?
            $this->configuration->getStartUrlForLoggedIn() :
            $this->configuration->getStartUrlForNotLoggedIn();

        $startUrl = $this->addUtmParameterToUrl($startUrl);
        $this->addPwaCookie();
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

    protected function addPwaCookie()
    {
        $tenYearsInSeconds = 315360000;
        $cookieData = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration($tenYearsInSeconds)
            ->setPath('/')
            ->setSecure(true);

        $this->cookieManagerInterface->setPublicCookie(
            'pwa',
            'true',
            $cookieData
        );
    }
}
