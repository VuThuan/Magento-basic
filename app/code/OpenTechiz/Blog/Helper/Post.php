<?php

namespace OpenTechiz\Blog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\View\Result\PageFactory;
use OpenTechiz\Blog\Model\Post as ModelPost;

class Post extends AbstractHelper
{
    protected $_resultPageFactory;

    public function __construct(Context $context, ModelPost $post, PageFactory $resultPageFactory)
    {
        $this->_post = $post;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function prepareResultPost(Action $action, $postId = null)
    {
        if ($postId !== null && $postId !== $this->_post->getId()) {
            $delimiterPosition = strrpos($postId, '|');
            if ($delimiterPosition) {
                $postId = substr($postId, 0, $delimiterPosition);
            }
            if (!$this->_post->load($postId)) {
                return false;
            }
        }
        if (!$this->_post->getId()) {
            return false;
        }
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->addHandle('blog_post_view');
        
        $resultPage->addPageLayoutHandles(['id' => $this->_post->getId()]);
        $this->_eventManager->dispatch(
            'opentechiz_blog_post_render',
            ['post' => $this->_post, 'controller_action' => $action]
        );
        return $resultPage;
    }
}