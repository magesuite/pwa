<?php
declare(strict_types=1);

namespace MageSuite\Pwa\ViewModel;

class Manifest implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \MageSuite\Pwa\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepo;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        \MageSuite\Pwa\Helper\Configuration $configuration,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\App\RequestInterface $request,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->configuration = $configuration;
        $this->urlBuilder = $urlBuilder;
        $this->jsonEncoder = $jsonEncoder;
        $this->assetRepo = $assetRepo;
        $this->request = $request;
        $this->logger = $logger;
    }

    public function getJsonConfig(): string
    {
        $config = $this->getConfig();

        return $this->jsonEncoder->encode($config);
    }

    public function getMediaBaseUrl(): string
    {
        return $this->urlBuilder->getBaseUrl([
            '_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ]);
    }

    public function getConfiguration(): \MageSuite\Pwa\Helper\Configuration
    {
        return $this->configuration;
    }

    protected function getConfig(): array
    {
        $config = [
            'name' => $this->configuration->getName(),
            'short_name' => $this->configuration->getShortName(),
            'start_url' => $this->configuration->getStartUrl() . '?utm_source=homescreen',
            'display' => $this->configuration->getDisplayMode(),
            'background_color' => $this->configuration->getBackgroundColor(),
            'theme_color' => $this->configuration->getThemeColor(),
            'description' => $this->configuration->getDescription(),
            'categories' => $this->configuration->getCategories(),
            'icons' => [
                [
                    'src' => $this->getViewFileUrl('Magento_Theme::android-chrome-192x192.png'),
                    'sizes' => '192x192',
                    'type' => 'image/png'
                ],
                [
                    'src' => $this->getViewFileUrl('Magento_Theme::icon-512x512.png'),
                    'sizes' => '512x512',
                    'type' => 'image/png'
                ],
                [
                    'src' => $this->getViewFileUrl('Magento_Theme::icon-512x512-maskable.png'),
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'maskable'
                ]
            ]
        ];

        return $config;
    }

    protected function getViewFileUrl($fileId, array $params = []): string
    {
        try {
            $params = array_merge(['_secure' => $this->request->isSecure()], $params);
            return $this->assetRepo->getUrlWithParams($fileId, $params);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->logger->critical($e);
            return $this->urlBuilder->getUrl('', ['_direct' => 'core/index/notFound']);
        }
    }
}
