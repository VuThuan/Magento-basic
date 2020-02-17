<?php

namespace OpenTechiz\Blog\Block;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use OpenTechiz\Blog\Api\Data\CommentInterface;
use OpenTechiz\Blog\Model\ResourceModel\Comment\Collection as CommentCollection;

class CommentList extends \Magento\Framework\View\Element\Template implements IdentityInterface
{
    protected  $_commentFactory;
    
    protected  $_commentCollectionFactory;
    
    protected $_registry;
    
    protected $_customerRepository;

    public function __construct(
        Template\Context $context,
        array $data = [],
        \OpenTechiz\Blog\Model\CommentFactory $commentFactory,
        \OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory $commentCollectionFactory,
        \Magento\Customer\Api\CustomerRepositoryInterfaceFactory $customerRepositoryFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestInterface $request
    )
    {
        parent::__construct($context, $data);
        $this->_commentFactory = $commentFactory;
        $this->_customerRepository = $customerRepositoryFactory;
        $this->_commentCollectionFactory = $commentCollectionFactory;
        $this->_registry = $registry;
        $this->_request = $request;
    }

    public function getNameUser($id) {
       $userInfo = $this->_customerRepository->create()->getById($id);
       return $userInfo->getFirstName()." ".$userInfo->getLastName();
    }

    public function getPostID(){
        return $this->_request->getParam('id', false);
    }

    public function getComments()
    {
        $post_id = $this->getPostId();
        if(!$this->hasData("cmt")) {
            $comments = $this->_commentCollectionFactory
                ->create()
                ->addFilter('post_id', $post_id)
                ->addFieldToFilter('is_active', 1)
                ->addOrder(
                        CommentInterface::CREATED_AT,
                        CommentCollection::SORT_ORDER_DESC
                    );
			$this->setData("cmt",$comments);
		}
		return $this->getData("cmt");
    }

    public function getAjaxUrl()
    {
        return '/blog/comment/load';
    }

    public function getIdentities()
    {
        return [\OpenTechiz\Blog\Model\Comment::CACHE_POST_COMMENT_TAG.'_'.$this->getPostID()];
    }
}