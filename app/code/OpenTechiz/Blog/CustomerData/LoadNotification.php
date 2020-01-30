<?php

namespace OpenTechiz\Blog\Customer;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Framework\DataObject;
use OpenTechiz\Blog\Api\Data\NotificationInterface;
use OpenTechiz\Blog\Model\ResourceModel\Notification\Collection as NotificationCollection;

class LoadNotification extends DataObject implements SectionSourceInterface
{
    /** @var \OpenTechiz\Blog\Model\ResourceModel\Notification\CollectionFactory */
    protected $_notificationCollectionFactory;

    /** @var \Magento\Customer\Model\Session */
    protected $_customerSession;

    /**
     * @param \OpenTechiz\Blog\Model\ResourceModel\Notification\CollectionFactory $collectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \OpenTechiz\Blog\Model\ResourceModel\Notification\CollectionFactory $collectionFactory,
        \Magento\Customer\Model\Session $customerSession
    )
    {
        $this->_notificationCollectionFactory = $collectionFactory;
        $this->_customerSession = $customerSession;        
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        if($this->_customerSession->isLoggedIn()) return;
        
        $items = [];
        $customerID = $this->_customerSession->getCustomer()->getId();
        $notifications = $this->_notificationCollectionFactory
                              ->create()
                              ->addFieldToFilter('customer_id', $customerID)
                              ->addOrder(
                                  NotificationInterface::CREATED_AT,
                                  NotificationCollection::SORT_ORDER_DESC
                              );
        
        foreach($notifications as $noti){
            $items[] = [
                'noti_id' => $noti->getNotificationID(),
                'content' => $noti->getContent(),
                'created_at' => $noti->getCreatedAt()
            ];
        }
        return [
            'items' => count($items) ? $items : []
        ];
    }
}