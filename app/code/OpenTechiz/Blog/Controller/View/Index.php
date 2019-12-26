<?php

namespace OpenTechiz\Blog\Controller\View;

use \Magento\Framework\App\Action\Action;

class Index extends Action
{
    protected $_postHelper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \OpenTechiz\Blog\Helper\Post $postHelper
    ) {
        $this->_resultForwardFactory = $resultForwardFactory;
        $this->_postHelper = $postHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        $post_id = $this->getRequest()->getParam('post_id', $this->getRequest()->getParam('id', false));
        $result_page = $this->_postHelper->prepareResultPost($this, $post_id);
        if (!$result_page) {
            $resultForward = $this->_resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }
        return $result_page;
    }
}
