<?php

namespace Mageplaza\HelloWorld\Block\Catalog\Product;

use Magento\Catalog\Block\Product\View as MagentoView;
use Magento\Framework\App\ObjectManager;

class View extends MagentoView
{
    /**
     * Retrieve current product model
     * 
     * @return \Magento\Catalog\Model\Product 
     */
    public function getProduct()
    {
        //Logging to test override
        $logger =  ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        $logger->debug('Block Override Test');

        if(!$this->_coreRegistry->registry('product') && $this->getProductId()){
            $product = $this->productRepository->getById($this->getProductId());
            $this->_coreRegistry->register('product', $product);
        }
        return $this->_coreRegistry->registry('product');
    }
}