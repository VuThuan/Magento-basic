<?php

namespace PHPTest\BannerSlider\Model\ResourceModel\Banner;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('PHPTest\BannerSlider\Model\Banner', 'PHPTest\BannerSlider\Model\ResourceModel\Banner');
    }
}