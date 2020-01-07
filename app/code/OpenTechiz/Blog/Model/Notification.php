<?php

namespace OpenTechiz\Blog\Model;

use Magento\Framework\Model\AbstractModel;
use OpenTechiz\Blog\Api\Data\NotificationInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Notification extends AbstractModel implements NotificationInterface, IdentityInterface
{
    const CACHE_TAG = 'opentechiz_blog_comment_approval_notification';

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('OpenTechiz\Blog\Model\ResourceModel\Notification');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [self::CACHE_TAG . '_' . $this->getNotificationID()];
        if ($this->isObjectNew()) {
            $identities[] = self::CACHE_TAG;
        }
        return $identities;
    }

    /**
     * @{initialize}
     */
    function getNotificationID(){
        return $this->getData(self::NOTIFICATION_ID);
    }

    function getContent(){
        return $this->getData(self::CONTENT);
    }

    function getPostId(){
        return $this->getData(self::POST_ID);
    }

    function getCustomerID(){
        return $this->getData(self::CUSTOMER_ID);
    }

    function getCommentId(){
        return $this->getData(self::COMMENT_ID);
    }

    function getCreatedAt(){
        return $this->getData(self::CREATED_AT);
    }

    /** SET DATA FOR NOTIFICATION */

    /**
     * @{initialize}
     */
    function setNotificationID($id){
        $this->setData(self::NOTIFICATION_ID,$id);
        return $this;
    }

    /**
     * @{initialize}
     */
    function setCustomerID($customerID){
        $this->setData(self::CUSTOMER_ID,$customerID);
        return $this;
    }
    
    /**
     * @{initialize}
     */
    function setContent($content){
        $this->setData(self::CONTENT,$content);
        return $this;
    }
    
    /**
     * @{initialize}
     */
    function setPostId($postId){
        $this->setData(self::POST_ID,$postId);
        return $this;
    }

    /**
     * @{initialize}
     */
    function setCommentId($commentID){
        $this->setData(self::COMMENT_ID, $commentID);
        return $this;
    }
    
    /**
     * @{initialize}
     */
    function setCreatedAt($creationTime){
        $this->setData(self::CREATED_AT,$creationTime);
        return $this;
    }
}