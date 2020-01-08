<?php

namespace PHPThuan\Menu\Observer;

use Magento\Framework\Event\ObserverInterface;

class TopmenuHtmlGetAfter implements ObserverInterface
{
    /**
     * Parent layout of the block
     * 
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    /**
     * @param \Magento\Framework\View\LayoutInterface $layout
     */
    public function __construct(
        \Magento\Framework\View\LayoutInterface $layout
    )
    {
        $this->layout = $layout;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $transportObject = $observer->getEvent()->getData('transportObject');
        if($transportObject){
            $textLinkHtml = $this->layout->createBlock('Magento\Framework\View\Element\Template')
            ->setTemplate('PHPThuan_Menu::html/topmenu.phtml')->toHtml();

            //Add the FAQs link to end of the default menu
            $topmenuHtml = $transportObject->getHtml().$textLinkHtml;
            //Update the html to event
            $transportObject->setHtml($topmenuHtml);
        }
        return $this;
    }
}