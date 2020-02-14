<?php

namespace OpenTechiz\Blog\Controller\Comment;

use \Magento\Framework\App\Action\Action;
use OpenTechiz\Blog\Api\Data\CommentInterface;
use OpenTechiz\Blog\Model\ResourceModel\Comment\Collection as CommentCollection;

class Load extends Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    protected $_commentCollectionFactory;

    protected $_customerSession;

    function __construct(
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory $commentCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->_resultFactory = $context->getResultFactory();
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_commentCollectionFactory = $commentCollectionFactory;
        $this->_customerSession = $customerSession;
        parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->_customerSession->isLoggedIn()) return false;

        $postData = (array) $this->getRequest()->getPostValue();

        $post_id = $postData['post_id'];
        $customer_id = $this->_customerSession->getCustomerId();
        $jsonResultResponse = $this->_resultJsonFactory->create();

        $comments = $this->_commentCollectionFactory
            ->create()
            ->addFieldToFilter('post_id', $post_id)
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('customer_id', $customer_id)
            ->addOrder(
                CommentInterface::CREATED_AT,
                CommentCollection::SORT_ORDER_DESC
            )->toArray();

        if ($comments['totalRecords'] == 0) return false;
        $jsonResultResponse->setData($comments);
        return $jsonResultResponse;
    }
}
