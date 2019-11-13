<?php

namespace ParactivePart3\ObserverExample\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use ParactivePart3\ObserverExample\Manager\Logger;

class LogControllerAction implements ObserverInterface
{
    /** @var Logger */
    private $logger;

    /**
     * @param Logger $obseloggerrver
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\App\RequestInterface $requests */
        $request = $observer->getEvent()->getRequest();

        //Path : /opt/lampp/htdocs/magento2/lib/internal/Magento/Framework/App/Request/Http.php
        $this->logger->log(
            '[Module : '.$request->getModuleName() 
            . '] [Action : ' . $request->getActionName() 
            . '] [Controller:  '. $request->getControllerName() 
            . '] [Path Info: '. $request->getPathInfo()
        );
    }
}