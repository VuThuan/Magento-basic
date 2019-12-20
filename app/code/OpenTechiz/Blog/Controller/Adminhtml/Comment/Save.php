<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OpenTechiz\Blog\Model\ResourceModel\Comment;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Save Comment Blog Posts action.
 */
class Save extends Action
{
    const ADMIN_RESOURCE = 'OpenTechiz_Blog::comment_post';   

    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \OpenTechiz\Blog\Model\CommentFactory
     */
    private $pageFactory;

    protected $_backendSession;

    /**
     * @param Action\Context $context
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \OpenTechiz\Blog\Model\CommentFactory|null $pageFactory
     */
    public function __construct(
        \OpenTechiz\Blog\Model\CommentFactory $commentFactory,
        \Magento\Backend\Model\Session $backendSession,
        Action\Context $context
    )
    {
        $this->_commentFactory = $commentFactory;
        $this->_backendSession = $backendSession;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \OpenTechiz\Blog\Model\Comment $model */
            $model = $this->_commentFactory->create();
            $id = $this->getRequest()->getParam('comment_id');
            if ($id) {
                $model->load($id);
            }
            $model->setComment($data['comment']);
            $model->setPostId($data['post_id']);
            $model->setIsActive($data['is_active']);
            $model->setCustomerId($data['customer_id']);
            $this->_eventManager->dispatch(
                'blog_comment_prepare_save',
                ['comment' => $model, 'request' => $this->getRequest()]
            );
            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this Comment.'));
                $this->_backendSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['comment_id' => $model->getCommentID(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the comment.'));
            }
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['comment_id' => $this->getRequest()->getParam('comment_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('OpenTechiz_Blog::save');
    }
}
