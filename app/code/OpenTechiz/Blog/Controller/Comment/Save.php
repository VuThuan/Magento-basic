<?php

namespace OpenTechiz\Blog\Controller\Comment;

use Exception;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use OpenTechiz\Blog\Helper\SendEmail;
use OpenTechiz\Blog\Model\Comment;
use OpenTechiz\Blog\Model\CommentFactory;

/**
 * Class Save
 * @package OpenTechiz\Blog\Controller\Comment
 */
class Save extends Action
{
    /**
     * @var CommentFactory
     */
    protected $_commentFactory;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var StateInterface
     */
    protected $_inlineTranslation;

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var SendEmail
     */
    protected $_sendEmail;

    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var ResultFactory
     */
    protected $resultRedirect;

    /**
     * @var ResultFactory
     */
    private $_resultFactory;

    /**
     * Save constructor.
     * @param CommentFactory $commentFactory
     * @param JsonFactory $resultJsonFactory
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $customerSession
     * @param SendEmail $sendEmail
     * @param ResultFactory $result
     * @param Context $context
     */
    public function __construct(
        CommentFactory $commentFactory,
        JsonFactory $resultJsonFactory,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        Session $customerSession,
        SendEmail $sendEmail,
        ResultFactory $result,
        Context $context
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

    /**
     * @return ResponseInterface|Json|ResultInterface
     * @throws Exception
     */
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
        $postObject = new DataObject();
        $postObject->setData($postData);

        if (!$this->_customerSession->isLoggedIn()) {
            $error = true;
            $message = "You need log in to comment";
        }

        $jsonResultResponse = $this->_resultJsonFactory->create();

        if (!$error) {
            // save data to database
            /** @var Comment $model */
            $model = $this->_commentFactory->create();
            $model->addData([
                "comment" => $postData['comment'],
                "post_id" => $postData['post_id'],
                "customer_id" => $this->_customerSession->getCustomer()->getId(),
                "is_active" => 2
            ]);

            $this->_eventManager->dispatch(
                'blog_comment_prepare_save',
                ['comment' => $model, 'request' => $this->getRequest()]
            );

            $model->save();

            $userInfo = $this->_customerSession->getCustomerData();
            $name = $userInfo->getFirstName() . " " . $userInfo->getLastName();
            $email = $userInfo->getEmail();
            //  echo 'success';
            $jsonResultResponse->setData([
                'result' => 'success',
                'message' => 'Thank you for your submission. Our Admins will review and approve shortly'
            ]);
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
