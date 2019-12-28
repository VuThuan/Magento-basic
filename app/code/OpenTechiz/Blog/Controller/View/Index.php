<?php

namespace OpenTechiz\Blog\Controller\View;

use \Magento\Framework\App\Action\Action;

class Index extends Action
{
    protected $_postHelper;
    protected $_postFactory;
    protected $_registry;
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Registry $registry,
        \OpenTechiz\Blog\Helper\Post $postHelper,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    )
    {
        $this->_resultForwardFactory = $resultForwardFactory;
        $this->_postHelper = $postHelper;
        $this->_registry = $registry;
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->_request->getParams();
        $post_id = $id['id'];
        $this->_registry->register('post_id', $post_id);
        return $this->_pageFactory->create();
    }
}
