<?php

namespace OpenTechiz\Blog\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use OpenTechiz\Blog\Api\Data\PostInterface;
use OpenTechiz\Blog\Model\ResourceModel\Post\Collection as PostCollection;

class PostList extends Template
{
    protected $_postCollectionFactory;

    public function __construct(
        Context $context, 
        \OpenTechiz\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->_postCollectionFactory = $postCollectionFactory;
    }

    public function getPosts()
    {
        if(!$this->hasData('posts')){
            $posts = $this->_postCollectionFactory
                          ->create()
                          ->addFilter('is_active', 1)
                          ->addOrder(
                            PostInterface::CREATION_TIME,
                            PostCollection::SORT_ORDER_DESC
                          );
            $this->setData('posts', $posts);
        }
        return $this->getData('posts');
    }
}