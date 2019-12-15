<?php

namespace OpenTechiz\Blog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Post extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('opentechiz_blog_post', 'post_id');
    }
}