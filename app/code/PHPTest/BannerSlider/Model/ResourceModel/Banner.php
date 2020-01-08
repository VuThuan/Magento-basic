<?php

namespace PHPTest\BannerSlider\Model\ResourceModel;

class Banner extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
        /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('phptest_banners_slider', 'id');
    }
}