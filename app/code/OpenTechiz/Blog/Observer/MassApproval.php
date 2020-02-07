<?php

namespace OpenTechiz\Blog\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Indexer\CacheContext;
use Magento\Framework\Event\ManagerInterface as EventManager;

class MassApproval implements ObserverInterface
{
    protected $_postFactory;

    protected $_notiFactory;

    protected $_notiCollectionFactory;
 
    public function __construct(
        \OpenTechiz\Blog\Model\ResourceModel\Notification\CollectionFactory $notiCollectionFactory,
        \OpenTechiz\Blog\Model\PostFactory $postFactory,
        \OpenTechiz\Blog\Model\NotificationFactory $notiFactory,
        CacheContext $cacheContext,
        EventManager $eventManager
    )
    {
        $this->_notiCollectionFactory = $notiCollectionFactory;
        $this->_postFactory = $postFactory;
        $this->_notiFactory = $notiFactory;
        $this->_cacheContext = $cacheContext;
        $this->_eventManager = $eventManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        $comments = $observer->getData('comments');
        $postIds =  [];
        foreach ($comments as $comment) {
            $user_id = $comment->getCustomerId();
            $post_id = $comment->getPostID();
            $comment_id = $comment->getCommentId();
            $isActive = $comment->getIsActive();
            // check if status is pending
            if($isActive != 2) return;
            // check if this comment approved before
            $notiCheck = $this->_notiCollectionFactory->create()
                ->addFieldToFilter('comment_id', $comment_id);
            if($notiCheck->count()>0) return;

            // add post ID to array
            $postIds[] = $post_id;
            
            // if user_id null then return
            if(!$user_id) return;

            // get post info
            $post = $this->_postFactory->create()->load($post_id);
            $postTitle = $post->getTitle();
            
            $noti = $this->_notiFactory->create();
            $content = "Your comment ID: $comment_id at Post: $postTitle has been approved enable by Admin";
            $noti->setContent($content);
            $noti->setCustomerID($user_id);
            $noti->setCommentID($comment_id);
            $noti->setPostID($post_id);
            $noti->save();
        }
        if (count($postIds)==0) return;
        // clean cache
        $this->_cacheContext->registerEntities(\OpenTechiz\Blog\Model\Comment::CACHE_POST_COMMENT_TAG, array_unique($postIds));
        $this->_eventManager->dispatch('clean_cache_by_tags', ['object' => $this->_cacheContext]);
    }
}