<?php

namespace OpenTechiz\Blog\Controller\Notification;
use \Magento\Framework\App\Action\Action;
use OpenTechiz\Blog\Api\Data\NotificationInterface;
use OpenTechiz\Blog\Model\ResourceModel\Notification\Collection as NotificationCollection;

class Load extends Action
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
        $postData = (array) $this->getRequest()->getPostValue();
        $page = 1;
        $expand = 0;
        if(isset($postData['page']))
        {
            $page = $postData['page'];
        }

        if(isset($postData['expand']))
        {
            $expand = $postData['expand'];
        }

        $customer_id = $this->_customerSession->getCustomer()->getId();

        $jsonResultResponse = $this->_resultJsonFactory->create();
        
        $totalUnreadNotifications = $this->_notificationCollectionFactory
            ->create()
            ->addFieldToFilter('customer_id', $customer_id)
            ->toArray();

        $notifications = $this->_notificationCollectionFactory
            ->create()
            ->addFieldToFilter('customer_id', $customer_id)
            ->setPageSize(5)
            ->setCurPage($page)
            ->addOrder(
                NotificationInterface::CREATED_AT,
                NotificationCollection::SORT_ORDER_DESC
            );

        $totalRecords = $notifications->toArray()['totalRecords'];
        $unreadNotification = count($totalUnreadNotifications['items']);

        if($totalRecords==0) return false;

        if(ceil($totalRecords/5)<$page) return $jsonResultResponse->setData('end');;
        
        $returnData = $notifications->toArray();
        $returnData['unreadRecords'] = $unreadNotification;
        
        $jsonResultResponse->setData($returnData);
        foreach ($notifications as $notification) {
            if(!$expand && $page == 1) break;
            if(!$notification->isViewed())
            {
                $notification->isViewed(1);
                $notification->save();
                $returnData['expand'] = $expand;
            }
        }

        return $jsonResultResponse;
    }
}