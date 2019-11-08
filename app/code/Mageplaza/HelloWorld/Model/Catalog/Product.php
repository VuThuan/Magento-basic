<?php
 
namespace Mageplaza\HelloWorld\Model\Catalog;
 
class Product extends \Magento\Catalog\Model\Product
{    
    /**
     * Get product name
     *
     * @return string
     * @codeCoverageIgnoreStart
     */
    public function getName()
    {        
        // logging to test override    
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        $logger->debug('Model Override Test');
    
        return $this->_getData(self::NAME);
    }
}