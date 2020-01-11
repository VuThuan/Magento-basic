<?php

namespace OpenTechiz\Blog\Model\Post\Source;

use OpenTechiz\Blog\Model\ResourceModel\Post\CollectionFactory;

class Status extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var \WDT\Faq\Model\ResourceModel\Post\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param \WDT\Faq\Model\ResourceModel\Post\CollectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->_collectionFactory = $collectionFactory;
    }

    public function getAllOptions()
    {
        if (empty($this->_options)) {
            $options = [];
            $collection = $this->_collectionFactory->create();

            foreach ($collection as $post) {
                $options[] = [
                    'label' => $post->getTitle(),
                    'value' => $post->getId()
                ];
            }
            $this->_options = $options;
        }
        return $this->_options;
        // return [
        //     '1' => 'dsadas',
        //     '2' => 'sadsads'
        // ];
    }
}