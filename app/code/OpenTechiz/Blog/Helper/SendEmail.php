<?php

namespace OpenTechiz\Blog\Helper;

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;

class SendEmail extends AbstractHelper
{
    protected $_transportBuilder;
    protected $_scopeConfig;

    public function __construct(
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        Context $context
    ) {
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function approvalEmail($sendToEmail, $name)
    {
        $storeScope = ScopeInterface::SCOPE_STORE;
        $postObject = new DataObject();
        $data['name'] = $name;
        $postObject->setData($data);
        $senderEmail = $this->_scopeConfig->getValue('trans_email/ident_general/email', $storeScope);
        $senderName = $this->_scopeConfig->getValue('trans_email/ident_general/name', $storeScope);
        $sender = [
            'name' => $senderName,
            'email' => $senderEmail
        ];

        $transport = $this->_transportBuilder
            ->setTemplateIdentifier('blog_comment_notification_email_template')
            ->setTemplateOptions(
                [
                    'area' => Area::AREA_FRONTEND,
                    'store' => Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($sender)
            ->addTo($sendToEmail)
            ->setReplyTo($sender['email'])
            ->getTransport();

        $transport->sendMessage();
    }

    public function reminderEmail($commentCount, $email, $name)
    {
        $storeScope = ScopeInterface::SCOPE_STORE;
        $senderEmail = $this->_scopeConfig->getValue('trans_email/ident_general/email', $storeScope);
        $senderName = $this->_scopeConfig->getValue('trans_email/ident_general/name', $storeScope);
        $sender = [
            'name' => $senderName,
            'email' => $senderEmail
        ];
        $postObject = new DataObject();
        $data['name'] = $name;
        $data['comment_count'] = $commentCount;
        $data['subject'] = "ADMIN: $commentCount comment(s) waiting for approval";
        $postObject->setData($data);
        $transport = $this->_transportBuilder
            ->setTemplateIdentifier($this->_scopeConfig->getValue('blog/reminder/template', $storeScope))
            ->setTemplateOptions(
                [
                    'area' => Area::AREA_FRONTEND,
                    'store' => Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($sender)
            ->addTo($email)
            ->getTransport();

        $transport->sendMessage();
    }
}
