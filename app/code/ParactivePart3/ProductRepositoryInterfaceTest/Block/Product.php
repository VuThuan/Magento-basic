<?php

namespace ParactivePart3\ProductRepositoryInterfaceTest\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Model\ProductRepository;

class Product extends \Magento\Framework\View\Element\Template
{
    protected $_productRepository;

    public function __construct(Context $context, ProductRepository $productRepository, array $data = [])
    {
        $this->_productRepository = $productRepository;
        parent::_construct($context, $data);
    }

    public function getProductById($id)
	{
		return $this->_productRepository->getById($id);
	}
	
	public function getProductBySku($sku)
	{
		return $this->_productRepository->get($sku);
	}
}