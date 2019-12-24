<?php

namespace OpenTechiz\Blog\Helper;

class SendEmail extends \Magento\Framework\App\Helper\AbstractHelper
{
    
    protected $_transportBuilder;
    protected $_scopeConfig;
    
    public function __construct(
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Helper\Context $context
    )
    {
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }
    
    public function approvalEmail($email, $name)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE; 
        $postObject = new \Magento\Framework\DataObject();
        $data['name'] = $name;
        $postObject->setData($data);
        $senderEmail = $this->_scopeConfig->getValue('trans_email/ident_general/email', $storeScope);
        $senderName = $this->_scopeConfig->getValue('trans_email/ident_general/name', $storeScope);
        $sender = [
            'name' => $senderName,
            'email' => $senderEmail
        ];
        
        $transport = $this->_transportBuilder
            ->setTemplateIdentifier($this->_scopeConfig->getValue('blog/general/template', $storeScope))
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($sender)
            ->addTo($email)
            ->getTransport()
            ->sendMessage();
    }
    
    public function reminderEmail($commentCount, $email, $name)
    {   
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $senderEmail = $this->_scopeConfig->getValue('trans_email/ident_general/email', $storeScope);
        $senderName = $this->_scopeConfig->getValue('trans_email/ident_general/name', $storeScope);
        $sender = [
            'name' => $senderName,
            'email' => $senderEmail
        ];
        $postObject = new \Magento\Framework\DataObject();
        $data['name'] = $name;
        $data['comment_count'] = $commentCount;
        $data['subject'] = "ADMIN: $commentCount comment(s) waiting for approval";
        $postObject->setData($data);
        $transport = $this->_transportBuilder
            ->setTemplateIdentifier($this->_scopeConfig->getValue('blog/reminder/template', $storeScope))
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($sender)
            ->addTo($email)
            ->getTransport()
            ->sendMessage();
    }
}