<?php

namespace Excellence\Hello\Controller\Hello;

class World extends \Magento\Framework\App\Action\Action
{

    protected $_registry;

    protected $request;

    protected $_catalogSession;
    protected $_customerSession;
    protected $_checkoutSession;

    protected $_cookieManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data = [],
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
        )
    {
        $this->request = $request; 
        $this->_registry = $registry;

        $this->_catalogSession = $catalogSession;
        $this->_customerSession = $customerSession;
        $this->_checkoutSession = $checkoutSession;

        $this->_cookieManager = $cookieManager;
        return parent::__construct($context);
    }

    //Hello world
    public function execute()
    {
        echo 'Hello World';
        // $this->getCatalogSession()->setMyname('Mageplaza');
        // echo  $this->getCatalogSession()->getMyName(). '</br>';
        // $cookieValue = $this->_cookieManager->getCookie(\Mageplaza\HelloWorld\Controller\Cookie\AddCookie::COOKIE_NAME);
        // echo $cookieValue;
        exit;
    }

    //Request
    public function methodName()
    {
        $this->request->getParams(); //get All request
        $this->request->getParam('id-123'); //Get one request with key='id-123'
    }

    //Registry
    public function getProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

    public function getCurrentCMSPage()
    {
        return $this->_registry->registry('current_cms_page');
    }

    //Session
    public function getCatalogSession()
    {
        return $this->_catalogSession;
    }

    public function getCustomerSession()
    {
        return $this->_customerSession;
    }

    public function getCheckoutSession()
    {
        return $this->_checkoutSession;
    }
}