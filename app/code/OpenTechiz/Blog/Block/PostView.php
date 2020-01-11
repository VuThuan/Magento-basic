<?php

namespace OpenTechiz\Blog\Block;

use Magento\Framework\View\Element\Template;

class PostView extends Template
{
    /** @var \OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory */
    protected $_commentCollectionFactory;

    /** @var \Magento\Framework\Registrys */
    protected $_registry;

    /**
     * Constructor
     * 
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \OpenTechiz\Blog\Model\Post $post
     * @param \OpenTechiz\Blog\Model\PostFactory $postFactory
     * @param \OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory $commentCollectionFactory
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \OpenTechiz\Blog\Model\Post $post,
        \OpenTechiz\Blog\Model\PostFactory $postFactory,
        \OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory $commentCollectionFactory,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this->_post = $post;
        $this->_commentCollectionFactory = $commentCollectionFactory;
        parent::__construct($context, $data);
        $this->_postFactory = $postFactory;
    }

    public function getPost()
    {
        $post_id = $this->_registry->registry('post_id');

        return $this->_postFactory->create()->load($post_id);
    }

    public function getIdentities()
    {
        $identities = $this->getPost()->getIdentities();
        $comments = $this->_commentCollectionFactory
            ->create()
            ->addFieldToFilter('comment_id', $this->getID())
            ->addFieldToFilter('status_id', '1');
        foreach ($comments as $comment) {
            $identities = array_merge($identities,
                [\OpenTechiz\Blog\Model\Comment::CACHE_POST_COMMENT_TAG."_".$comment->getID()]);
        }
        return ($identities);
    }
}