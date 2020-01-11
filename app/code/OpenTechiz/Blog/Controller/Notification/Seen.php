<?php

namespace OpenTechiz\Blog\Controller\Notification;

use Magento\Framework\App\Action\Action;

class Seen extends Action
{
     /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    protected $_notificationCollectionFactory;
    
    protected $_customerSession;
    
    function __construct(
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \OpenTechiz\Blog\Model\ResourceModel\Notification\CollectionFactory $notificationCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Action\Context $context
    )
    {
        $this->_resultFactory = $context->getResultFactory();
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_notificationCollectionFactory = $notificationCollectionFactory;
        $this->_customerSession = $customerSession;
        parent::__construct($context);
    }
    
    public function execute()
    {
        if(!$this->_customerSession->isLoggedIn()) return false;
        $customer_id = $this->_customerSession->getCustomer()->getId();
        
        $totalUnreadNotifications = $this->_notificationCollectionFactory
            ->create()
            ->addFieldToFilter('customer_id', $customer_id);
        foreach ($totalUnreadNotifications as $notification) {
            $notification->save();
        }
        $jsonResultResponse = $this->_resultJsonFactory->create();
        return $jsonResultResponse->setData('success');
    }
}