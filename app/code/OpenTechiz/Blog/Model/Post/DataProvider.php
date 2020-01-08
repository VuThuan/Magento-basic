<?php

namespace OpenTechiz\Blog\Model\Post;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use OpenTechiz\Blog\Model\ResourceModel\Post\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /** @var \Magento\Cms\Model\ResourceModel\Block\Collection */
    protected $connection;

    /** @var DataPersistorInterface */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $blockCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $postCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $postCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     * 
     * @return array
     */
    public function getData()
    {
        if(isset($this->loadedData)){
            return $this->loadedData;
        }
        $item = $this->collection->getItems();
        /** @var \Magento\Cms\Model\Block $block */
        foreach($item as $block){
            $this->loadedData[$block->getId()] = $block->getData();
        }

        $data = $this->dataPersistor->get('post');
        if(!empty($data)){
            $block = $this->collection->getNewEmptyItem();
            $block->setData($data);
            $this->loadedData[$block->getId()] = $block->getData();
            $this->dataPersistor->clear('post');
        }

        return $this->loadedData;
    }
}