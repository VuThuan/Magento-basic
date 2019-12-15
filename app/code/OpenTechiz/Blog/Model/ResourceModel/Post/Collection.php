<?php

namespace OpenTechiz\Blog\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'post_id';

    protected function __construct()
    {
        $this->_init('OpenTechiz\Blog\Model\Post', 'OpenTechiz\Blog\Model\ResourceModel\Post');
    }
}