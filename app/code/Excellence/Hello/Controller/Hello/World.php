<?php

namespace Excellence\Hello\Controller\Hello;

class World extends \Magento\Framework\App\Action\Action
{

    protected $_registry;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry
        )
    {
        $this->_registry = $registry;
        return parent::__construct($context);
    }

    public function execute()
    {
        echo 'Hello World';
        exit;        
    }

    public function getProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

    
}