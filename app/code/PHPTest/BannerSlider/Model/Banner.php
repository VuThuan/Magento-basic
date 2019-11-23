<?php

namespace PHPTest\BannerSlider\Model;

use Magento\Framework\Model\AbstractModel;

class Banner extends AbstractModel
{
    /**
     * Banner cache tag
     */
    const CACHE_TAG = 'phptest_banners_slider';

    /**#@+
     * Block's statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(PHPTest\BannerSlider\Model\ResourceModel\Banner::class);
    }

    /**
     * Prepare block's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}