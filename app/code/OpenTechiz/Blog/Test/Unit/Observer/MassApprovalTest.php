<?php

namespace OpenTechiz\Blog\Test\Unit\Observer;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use OpenTechiz\Blog\Model\Comment;
use OpenTechiz\Blog\Model\NotificationFactory;
use OpenTechiz\Blog\Model\PostFactory;
use OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory;
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
     * @var ManagerInterface
     */
    private $_eventManagerMock;

    /**
     * @var CollectionFactory
     */
    private $_commentCollectionFactoryMock;

    /**
     * @var Comment
     */
    private $commentMock;

    /**
     * @var Observer
     */
    private $observerMock;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->_notificationFactoryMock = $this->getMockBuilder(NotificationFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'setContent', 'setCustomerID', 'setCommentID', 'setPostID', 'save'])
            ->getMock();

    }
}
