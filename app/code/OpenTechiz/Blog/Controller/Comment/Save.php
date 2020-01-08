<?php

namespace OpenTechiz\Blog\Controller\Comment;

use \Magento\Framework\App\Action\Action;

class Save extends Action
{
    protected $_commentFactory;

    protected $_resultJsonFactory;

    protected $_inlineTranslation;

    protected $_transportBuilder;

    protected $_scopeConfig;

    protected $_sendEmail;

    protected $_customerSession;

    protected $resultRedirect;

    function __construct(
        \OpenTechiz\Blog\Model\CommentFactory $commentFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Model\Session $customerSession,
        \OpenTechiz\Blog\Helper\SendEmail $sendEmail,
        \Magento\Framework\Controller\ResultFactory $result,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->_commentFactory = $commentFactory;
        $this->_resultFactory = $context->getResultFactory();
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
        $this->_sendEmail = $sendEmail;
        $this->_customerSession = $customerSession;
        $this->resultRedirect = $result;
        parent::__construct($context);
    }

    public function execute()
    {
        $error = false;
        $message = '';
        $postData = (array) $this->getRequest()->getPostValue();

        if (!$postData) {
            $error = true;
            $message = "Your submission is not valid. Please try again!";
        }

        $this->_inlineTranslation->suspend();
        $postObject = new \Magento\Framework\DataObject();
        $postObject->setData($postData);

        if (!$this->_customerSession->isLoggedIn()) {
            $error = true;
            $message = "You need log in to comment";
        }

        $jsonResultResponse = $this->_resultJsonFactory->create();

        if (!$error) {
            // save data to database
            /** @var \OpenTechiz\Blog\Model\Comment $model */
            $model = $this->_commentFactory->create();
            $model->addData([
                "comment" => $postData['comment'],
                "post_id" => $postData['post_id'],
                "customer_id" => $postData['customer_id'],
                "is_active" => 2
            ]);
            $model->save();
            //  echo 'success';
            $jsonResultResponse->setData([
                'result' => 'success',
                'message' => 'Thank you for your submission. Our Admins will review and approve shortly'
            ]);
            $userInfo = $this->_customerSession->getCustomerData();
            $name = $userInfo->getFirstName() . " " . $userInfo->getLastName();
            $email = $userInfo->getEmail();
            // send email to user
            $this->_sendEmail->approvalEmail($email, $name);
        } else {
            $jsonResultResponse->setData([
                'result' => 'error',
                'message' => $message
            ]);
        }

        return $jsonResultResponse;
    }
}
