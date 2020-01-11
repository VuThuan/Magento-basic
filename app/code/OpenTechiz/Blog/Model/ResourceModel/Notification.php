<?php

namespace OpenTechiz\Blog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Notification extends AbstractDb
{
    /**
     * @var string
     */
    protected $_idFieldName = 'notification_id';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('opentechiz_blog_comment_approval_notification', 'notification_id');
    }
}