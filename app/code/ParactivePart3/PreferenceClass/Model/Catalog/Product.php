<?php

namespace ParactivePart3\PreferenceClass\Model\Catalog;

class Product extends \Magento\Catalog\Model\Product
{
	public function getName(){
		return $this->_getData(self::NAME) . ' + Demo Text';
	}
}