<?php

namespace OpenTechiz\Blog\Cron;

class Reminder 
{
    protected $_sendEmail;
    protected $_commentCollectionFactory;
    protected $_userCollection;
    public function __construct(
        \OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory $commentCollectionFactory,
        \Magento\User\Model\ResourceModel\User\CollectionFactory $userCollection,
        \OpenTechiz\Blog\Helper\SendEmail $sendEmail
    ) 
    {
        $this->_commentCollectionFactory = $commentCollectionFactory;
        $this->_userCollection = $userCollection;
        $this->_sendEmail = $sendEmail;
    }
    public function execute() {
        $to = date("Y-m-d h:i:s"); // current date
        $from = strtotime('-1 day', strtotime($to));
        $from = date('Y-m-d h:i:s', $from); // 1 days before
        $comments = $this->_commentCollectionFactory
                ->create()
                ->addFieldToFilter('is_active', 0)
                ->addFieldToFilter('created_at', ["lteq" => $from]);
        $commentCount = $comments->count();
        // get admins list
        $admins = $this->_userCollection->create();
        if($commentCount>0 && $admins->count()>0)
        {
            foreach ($admins as $admin) {
                $email = $admin->getEmail();
                $name = $admin->getUserName();
                $this->_sendEmail->reminderEmail($commentCount, $email, $name);
            }
        }
    }
}