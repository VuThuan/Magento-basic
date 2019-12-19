<?php

namespace OpenTechiz\Blog\Model;

use Magento\Framework\Model\AbstractModel;
use OpenTechiz\Blog\Api\Data\PostInterface;
use Magento\Framework\DataObject\IdentityInterface;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;

class Post extends AbstractModel implements PostInterface,IdentityInterface
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const CACHE_TAG='opentechiz_blog_post';

    //const CACHE_POST_COMMENT_TAG = "opentechiz_blog_post_comment";

    protected function _construct()
    {
        $this->_init('OpenTechiz\Blog\Model\ResourceModel\Post');
    }
    
    public function checkUrlKey($url_key)
    {
        return $this->_getResource()->checkUrlKey($url_key);
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [self::CACHE_TAG . '_' . $this->getID()];
        if ($this->isObjectNew()) {
            $identities[] = self::CACHE_TAG;
        }
        return $identities;
    }

    /**
     * @{initialize}
     */
    function getID(){
        return $this->getData(self::POST_ID);
    }
    /**
     * @{initialize}
     */
    function getUrlKey(){
        return $this->getData(self::URL_KEY);
    }
    /**
     * @{initialize}
     */
    function getTitle(){
        return $this->getData(self::TITLE);
    }
    /**
     * @{initialize}
     */
    function getUrl(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $urlBuilder=$objectManager->get("Magento\Framework\UrlInterface");
        return $urlBuilder->getUrl("blog/".$this->getUrlKey());
    }
    /**
     * @{initialize}
     */
    function getContent(){
        return $this->getData(self::CONTENT);
    }
    /**
     * @{initialize}
     */
    function getCreationTime(){
        return $this->getData(self::CREATION_TIME);
    }
    /**
     * @{initialize}
     */
    function getUpdateTime(){
        return $this->getData(self::UPDATE_TIME);
    }
    /**
     * @{initialize}
     */
    function isActive(){
        return $this->getData(self::IS_ACTIVE);
    }
    /**
     * @{initialize}
     */
    function setID($id){
        $this->setData(self::POST_ID,$id);
        return $this;
    }
    /**
     * @{initialize}
     */
    function setUrlKey($urlKey){
        $this->setData(self::URL_KEY,$urlKey);
        return $this;
    }
    /**
     * @{initialize}
     */
    function setTitle($title){
        $this->setData(self::TITLE,$title);
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
    function setCreationTime($creatTime){
        $this->setData(self::CREATION_TIME,$creatTime);
        return $this;
    }
    /**
     * @{initialize}
     */
    function setUpdateTime($updateTime){
        $this->setData(self::UPDATE_TIME,$updateTime);
        return $this;
    }
    /**
     * @{initialize}
     */
    function setIsActive($isActive){
        $this->setData(self::IS_ACTIVE,$isActive);
        return $this;
    }
}