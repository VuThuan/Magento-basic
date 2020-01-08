<?php

namespace OpenTechiz\Blog\Model;

use Magento\Framework\Model\AbstractModel;
use OpenTechiz\Blog\Api\Data\CommentInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Comment extends AbstractModel implements CommentInterface,IdentityInterface
{
    const STATUS_ENABLED  = 1;
    const STATUS_DISABLED = 0;
    const STATUS_PENDING  = 2;
    
    const CACHE_POST_COMMENT_TAG = "opentechiz_blog_post_comment";

    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'opentechiz_blog_comment';

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('OpenTechiz\Blog\Model\ResourceModel\Comment');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled'), self::STATUS_PENDING => __('Pending')];
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [self::CACHE_TAG . '_' . $this->getCommentID()];
        if ($this->isObjectNew()) {
            $identities[] = self::CACHE_TAG;
        }
        return $identities;
    }

    /**
     * @{initialize}
     */
    function getCommentID(){
        return $this->getData(self::COMMENT_ID);
    }

    /**
     * @{initialize}
     */
    function getComment()
    {
        return $this->getData(self::COMMENT);
    }

    /**
     * @{initialize}
     */
    function getPostId()
    {
        return $this->getData(self::POST_ID);
    }

    /**
     * @{initialize}
     */
    function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * @{initialize}
     */
    function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * @{initialize}
     */
    function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

// SET DATA ---------------------------------------------------------------------

    /**
     * @{initialize}
     */
    public function setCommentId($commentId)
    {
        $this->setData(self::COMMENT_ID,$commentId);
        return $this;
    }

    /**
     * @{initialize}
     */
    public function setComment($comment)
    {
        $this->setData(self::COMMENT,$comment);
        return $this;
    }

    /**
     * @{initialize}
     */
    public function setPostId($postId)
    {
        $this->setData(self::POST_ID,$postId);
        return $this;
    }

    /**
     * @{initialize}
     */
    public function setIsActive($isActive)
    {
        $this->setData(self::IS_ACTIVE,$isActive);
        return $this;
    }

    /**
     * @{initialize}
     */
    public function setCustomerId($customerId)
    {
        $this->setData(self::CUSTOMER_ID,$customerId);
        return $this;
    }

    /**
     * @{initialize}
     */
    public function setCreatedAt($createdTime)
    {
        $this->setData(self::CREATED_AT,$createdTime);
        return $this;
    }
}