<?php
namespace OpenTechiz\Blog\Block;

class Notification extends \Magento\Framework\View\Element\Template
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
    
    public function getAjaxNotificationLoadUrl()
	{
		return '/blog/notification/load';
	}

    public function isLoggedIn()
	{
		return $this->_customerSession->isLoggedIn();
	}
}