<?php

namespace OpenTechiz\Blog\Model\Post\Source;

use OpenTechiz\Blog\Model\ResourceModel\Post\CollectionFactory;

class Status extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var \OpenTechiz\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param \OpenTechiz\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->_collectionFactory = $collectionFactory;
    }

    public function getAllOptions()
    {
        $options = [];
        $collection = $this->_collectionFactory->create();

        foreach ($collection as $post) {
            $options[] = [
                'label' => $post->getTitle(),
                'value' => $post->getId()
            ];
        }
        return $options;
    }
}