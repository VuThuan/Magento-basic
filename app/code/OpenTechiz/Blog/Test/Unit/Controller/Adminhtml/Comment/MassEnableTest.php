<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Comment;

use \OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\AbstractMassActionTest;

class MassEnableTest extends AbstractMassActionTest
{
    /**
     * @var \OpenTechiz\Blog\Controller\Adminhtml\Comment\MassEnable
     */
    protected $massEnableController;

    /**
     * @var \OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $collectionFactoryMock;

    /**
     * @var \OpenTechiz\Blog\Model\ResourceModel\Comment\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $commentCollectionMock;

    /** @var \Magento\Framework\Event\ManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $eventManagerMock;

    protected function setUp()
    {
        parent::setUp();

        $this->collectionFactoryMock = $this->createPartialMock(
            \OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory::class,
            ['create']
        );

        $this->eventManagerMock = $this->createMock(\Magento\Framework\Event\ManagerInterface::class);

        $this->commentCollectionMock = $this->createMock(\OpenTechiz\Blog\Model\ResourceModel\Comment\Collection::class);

        $this->contextMock = $this->createMock(\Magento\Backend\App\Action\Context::class);

        $this->contextMock->expects($this->any())->method('getEventManager')->willReturn($this->eventManagerMock);
        $this->massEnableController = $this->objectManager->getObject(
            \OpenTechiz\Blog\Controller\Adminhtml\Comment\MassEnable::class,
            [
                'context' => $this->contextMock,
                'filter' => $this->filterMock,
                'collectionFactory' => $this->collectionFactoryMock,
            ]
        );
    }

    public function testMassEnableAction()
    {
        $enabledPostsCount = 2;

        $collection = [
            $this->getCommentMock(),
            $this->getCommentMock()
        ];

        $this->collectionFactoryMock->expects($this->once())->method('create')->willReturn($this->commentCollectionMock);

        $this->eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->with(
                'blog_comment_mass_enable_prepare',
                ['comments' => $this->commentCollectionMock]
            );

        $this->filterMock->expects($this->once())
            ->method('getCollection')
            ->with($this->commentCollectionMock)
            ->willReturn($this->commentCollectionMock);

        $this->commentCollectionMock->expects($this->once())->method('getSize')->willReturn($enabledPostsCount);
        $this->commentCollectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($collection));

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('A total of %1 record(s) have been enabled.', $enabledPostsCount));

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->massEnableController->execute());
    }

    /**
     * Create Blog Post Collection Mock
     *
     * @return \OpenTechiz\Blog\Model\ResourceModel\Comment\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCommentMock()
    {
        $commentMock = $this->createPartialMock(
            \OpenTechiz\Blog\Model\ResourceModel\Comment\Collection::class,
            ['setIsActive', 'save']
        );
        $commentMock->expects($this->once())->method('setIsActive')->with(true)->willReturn(true);
        $commentMock->expects($this->once())->method('save')->willReturn(true);

        return $commentMock;
    }
}