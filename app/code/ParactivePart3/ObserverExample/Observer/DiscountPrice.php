<?php

namespace ParactivePart3\ObserverExample\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Session;

class DiscountPrice implements ObserverInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @param \Magento\Customer\Model\Session $session
     */
    public function __construct(Session $session)
    {
        $this->_customerSession = $session;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote_item = $observer->getEvent()->getData('quote_item');
        $quote_item = ($quote_item->getParentItem() ? $quote_item->getParentItem() : $quote_item);
        //final price
        $finalPrice = $quote_item->getProduct()->getFinalPrice();

        //Check if customer login
        if($this->_customerSession->isLoggedIn()){
            //discount the price by 50% 
            $finalPrice = $finalPrice - ($finalPrice * 50 / 100);
        }

        $quote_item->setCustomPrice($finalPrice);
        $quote_item->setOriginalCustomPrice($finalPrice);
        
        $quote_item->getProduct()->setIsSuperMode(true);

        return $this;
    }
}
