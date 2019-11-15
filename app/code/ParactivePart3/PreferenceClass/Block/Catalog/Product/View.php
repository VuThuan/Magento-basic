<?php

namespace ParactivePart3\PreferenceClass\Block\Catalog\Product;

class View extends \Magento\Catalog\Block\Product\View
{
    /**
     * Retrieve current product model
     * 
     * @return \Magento\Catalog\Block\Product\View
     */
    public function getProduct()
    {
        //Logging to test override
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        $logger->debug('Block Override Test');
        
        if (!$this->_coreRegistry->registry('product') && $this->getProductId()) {
            $product = $this->productRepository->getById($this->getProductId());
            $this->_coreRegistry->register('product', $product);
        }
        return $this->_coreRegistry->registry('product');
    }
}