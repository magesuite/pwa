<?php

namespace MageSuite\Pwa\Controller\Manifest;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create(true);
        $resultPage->addHandle('pwa_manifest_index');
        $resultPage->setHeader('Content-Type', 'application/json');
        return $resultPage;
    }
}
