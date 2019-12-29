<?php
namespace OpenTechiz\Blog\Block;

class SaveComment extends \Magento\Framework\View\Element\Template
{
    protected $_request;
    
    protected $_customerSession;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\RequestInterface $request,
        array $data = []
    )
    {
        $this->_request = $request;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }
    
    public function getFormAction()
    {
        return '/magento2/blog/comment/save';
    }
    
    public function getAjaxUrl()
    {
        return '/magento2/blog/comment/load';
    }
    
    public function getPostId()
    {
        return $this->_request->getParam('id', false);
    }

    public function isCustomerLoggedIn()
    {
        return $this->_customerSession->isLoggedIn();
    } 

    public function getCustomerId()
    {
        return $this->_customerSession->getCustomer()->getId();
    }
}