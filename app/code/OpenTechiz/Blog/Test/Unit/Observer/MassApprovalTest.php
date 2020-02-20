<?php

namespace OpenTechiz\Blog\Test\Unit\Observer;

use OpenTechiz\Blog\Model\Comment;
use OpenTechiz\Blog\Model\Notification;
use OpenTechiz\Blog\Model\NotificationFactory;
use OpenTechiz\Blog\Model\Post;
use OpenTechiz\Blog\Model\PostFactory;
use OpenTechiz\Blog\Model\ResourceModel\Comment\Collection;
use OpenTechiz\Blog\Model\ResourceModel\Notification\CollectionFactory as NotificationCollection;
use OpenTechiz\Blog\Observer\MassApproval;
use PHPUnit\Framework\TestCase;

class MassApprovalTest extends TestCase
{
    /**
     * @var PostFactory
     */
    private $_postFactoryMock;

    /**
     * @var NotificationFactory
     */
    private $_notificationFactoryMock;

    /**
     * @var CollectionFactory
     */
    private $_notificationCollectionMock;

    /**
     * @var Comment
     */
    private $commentMock;

    /**
     * @var MassApproval
     */
    private $massApprovalObserver;

    protected function setUp()
    {
        $this->_notificationFactoryMock = $this->getMockBuilder(NotificationFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'setContent', 'setCustomerID', 'setCommentID', 'setPostID', 'save'])
            ->getMock();
        $this->_postFactoryMock = $this->getMockBuilder(PostFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'getTitle', 'load'])
            ->getMock();

        $this->_notificationCollectionMock = $this->getMockBuilder(NotificationCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'addFieldToFilter', 'count'])
            ->getMock();

        $this->commentMock = $this->getMockBuilder(Comment::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCustomerId', 'getPostID', 'getCommentId', 'getIsActive'])
            ->getMock();


        $this->massApprovalObserver = new MassApproval(
            $this->_notificationCollectionMock,
            $this->_postFactoryMock,
            $this->_notificationFactoryMock
        );
    }

    public function testExecuteMassApproval()
    {
        $title = 'Hello Title';
        $customerID = $this->commentMock->expects($this->any())
            ->method('getCustomerId')
            ->willReturn('1');
        $postID   = $this->commentMock->expects($this->any())
            ->method('getPostID')
            ->willReturn('1');
        $commentId = $this->commentMock->expects($this->any())
            ->method('getCommentId')
            ->willReturn('1');
        $isActive = $this->commentMock->expects($this->any())
            ->method('getIsActive')
            ->willReturn('2');

        $notiCollection = $this->createMock(\OpenTechiz\Blog\Model\ResourceModel\Notification\Collection::class);
        $this->_notificationCollectionMock->expects($this->any())
            ->method('create')
            ->willReturn($notiCollection);
        $this->_notificationCollectionMock->expects($this->any())
            ->method('addFieldToFilter')
            ->with('comment_id', 1)
            ->willReturnSelf();
        $this->_notificationCollectionMock->expects($this->any())
            ->method('count')
            ->willReturn(0);

        $postMock = $this->getMockBuilder(Post::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTitle'])
            ->getMock();
        $this->_postFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($postMock);
        $this->_postFactoryMock->expects($this->any())
            ->method('load')
            ->with($postID);

        $postTitle = $postMock->expects($this->any())
            ->method('getTitle')
            ->willReturn($title);

        $notiMock = $this->createMock(Notification::class);
        $this->_notificationFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($notiMock);

        $content = "Your comment ID:1 at Post: Hello Title has been approved enable by Admin";
        $notiMock->expects($this->any())->method('setContent')->with($content);
        $notiMock->expects($this->any())->method('setCustomerID')->with($customerID);
        $notiMock->expects($this->any())->method('setCommentID')->with($commentId);
        $notiMock->expects($this->any())->method('setPostID')->with($postID);

        $notiMock->expects($this->any())->method('save');

        $commentCollection = $this->createMock(Collection::class);
        $event = new \Magento\Framework\DataObject();
        $event->setData(['comment' => $commentCollection]);
        $observerMock = new \Magento\Framework\Event\Observer();
        $observerMock->setEvent($event);
        $this->massApprovalObserver->execute($observerMock);
    }
}
