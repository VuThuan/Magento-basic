<?php

namespace OpenTechiz\Blog\Controller\View;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $_registry;

    protected $_pageFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        PageFactory $pageFactory
    ) {
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
