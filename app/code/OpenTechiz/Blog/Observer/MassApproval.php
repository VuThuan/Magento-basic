<?php

namespace OpenTechiz\Blog\Observer;

use Exception;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Indexer\CacheContext;
use OpenTechiz\Blog\Model\NotificationFactory;
use OpenTechiz\Blog\Model\PostFactory;
use OpenTechiz\Blog\Model\ResourceModel\Notification\CollectionFactory;

class MassApproval implements ObserverInterface
{
    /**
     * @var PostFactory
     */
    protected $_postFactory;

    /**
     * @var NotificationFactory
     */
    protected $_notiFactory;

    /**
     * @var CollectionFactory
     */
    protected $_notiCollectionFactory;

    /**
     * MassApproval constructor.
     * @param CollectionFactory $notiCollectionFactory
     * @param PostFactory $postFactory
     * @param NotificationFactory $notiFactory
     */
    public function __construct(
        CollectionFactory $notiCollectionFactory,
        PostFactory $postFactory,
        NotificationFactory $notiFactory
    ) {
        $this->_notiCollectionFactory = $notiCollectionFactory;
        $this->_postFactory = $postFactory;
        $this->_notiFactory = $notiFactory;
    }

    /**
     * @param Observer $observer
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        $comments = $observer->getData('comments');
        $postIds =  [];
        foreach ($comments as $comment) {
            $user_id = $comment->getCustomerId();
            $post_id = $comment->getPostID();
            $comment_id = $comment->getCommentId();
            $isActive = $comment->getIsActive();
            // check if status is pending
            if ($isActive != 2) {
                return;
            }
            // check if this comment approved before
            $notiCheck = $this->_notiCollectionFactory->create()
                ->addFieldToFilter('comment_id', $comment_id);
            if ($notiCheck->count()>0) {
                return;
            }

            // add post ID to array
            $postIds[] = $post_id;

            // if user_id null then return
            if (!$user_id) {
                return;
            }

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
        if (count($postIds)==0) {
            return;
        }
    }
}
