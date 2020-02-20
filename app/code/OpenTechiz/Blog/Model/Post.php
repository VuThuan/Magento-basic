<?php

namespace OpenTechiz\Blog\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use OpenTechiz\Blog\Api\Data\PostInterface;

class Post extends AbstractModel implements PostInterface, IdentityInterface
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    const CACHE_POST_COMMENT_TAG = "opentechiz_blog_post_comment";

    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'opentechiz_blog_post';

    /**
     * @var string
     */
    protected $_cacheTag = 'opentechiz_blog_post';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'opentechiz_blog_post';

    /**
     * Construct.
     *
     * @return void
     */
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
    public function getID()
    {
        return $this->getData(self::POST_ID);
    }
    /**
     * @{initialize}
     */
    public function getUrlKey()
    {
        return $this->getData(self::URL_KEY);
    }
    /**
     * @{initialize}
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }
    /**
     * @{initialize}
     */
    public function getUrl()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $urlBuilder=$objectManager->get("Magento\Framework\UrlInterface");
        return $urlBuilder->getUrl("blog/" . $this->getID());
    }
    /**
     * @{initialize}
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }
    /**
     * @{initialize}
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }
    /**
     * @{initialize}
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }
    /**
     * @{initialize}
     */
    public function isActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * @{initialize}
     * @param $id
     * @return Post
     */
    public function setID($id)
    {
        $this->setData(self::POST_ID, $id);
        return $this;
    }

    /**
     * @{initialize}
     * @param $urlKey
     * @return Post
     */
    public function setUrlKey($urlKey)
    {
        $this->setData(self::URL_KEY, $urlKey);
        return $this;
    }

    /**
     * @{initialize}
     * @param $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->setData(self::TITLE, $title);
        return $this;
    }

    /**
     * @{initialize}
     * @param $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->setData(self::CONTENT, $content);
        return $this;
    }

    /**
     * @{initialize}
     * @param $creatTime
     * @return Post
     */
    public function setCreationTime($creatTime)
    {
        $this->setData(self::CREATION_TIME, $creatTime);
        return $this;
    }

    /**
     * @{initialize}
     * @param $updateTime
     * @return Post
     */
    public function setUpdateTime($updateTime)
    {
        $this->setData(self::UPDATE_TIME, $updateTime);
        return $this;
    }

    /**
     * @{initialize}
     * @param $isActive
     * @return Post
     */
    public function setIsActive($isActive)
    {
        $this->setData(self::IS_ACTIVE, $isActive);
        return $this;
    }
}
