<?php

namespace OpenTechiz\Blog\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\DataObject;
use OpenTechiz\Blog\Api\Data\NotificationInterface;
use OpenTechiz\Blog\Model\ResourceModel\Notification\Collection as NotificationCollection;
use OpenTechiz\Blog\Model\ResourceModel\Notification\CollectionFactory;

class LoadNotification extends DataObject implements SectionSourceInterface
{
    /** @var CollectionFactory */
    protected $_notificationCollectionFactory;

    /** @var Session */
    protected $_customerSession;

    /**
     * @param CollectionFactory $collectionFactory
     * @param Session $customerSession
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Session $customerSession
    ) {
        $this->_notificationCollectionFactory = $collectionFactory;
        $this->_customerSession = $customerSession;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        if ($this->_customerSession->isLoggedIn()) {
            return;
        }

        $items = [];
        $customerID = $this->_customerSession->getCustomer()->getId();
        $notifications = $this->_notificationCollectionFactory
            ->create()
            ->addFieldToFilter('customer_id', $customerID)
            ->addOrder(
                NotificationInterface::CREATED_AT,
                NotificationCollection::SORT_ORDER_DESC
            );

        foreach ($notifications as $noti) {
            $items[] = [
                'noti_id' => $noti->getNotificationID(),
                'content' => $noti->getContent(),
                'created_at' => $noti->getCreatedAt()
            ];
        }
        return [
            'items' =>  count($items) ? $items : []
        ];
    }
}
