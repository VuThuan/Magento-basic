<?php

namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Comment;

use OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\AbstractMassActionTest;

/**
 * Test for OpenTechiz\Blog\Controller\Adminhtml\Comment\MassDelete Class
 */
class MassDeleteTest extends AbstractMassActionTest
{
    /**
     * @var \OpenTechiz\Blog\Controller\Adminhtml\Comment\MassDelete
     */
    protected $massDeleteController;

    /**
     * @var \OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $collectionFactoryMock;

    /**
     * @var \OpenTechiz\Blog\Model\ResourceModel\Comment\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $commentCollectionMock;

    protected function setUp()
    {
        parent::setUp();

        $this->collectionFactoryMock = $this->createPartialMock(
            \OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory::class,
            ['create']
        );

        $this->commentCollectionMock =
            $this->createMock(\OpenTechiz\Blog\Model\ResourceModel\Comment\Collection::class);

        $this->massDeleteController = $this->objectManager->getObject(
            \OpenTechiz\Blog\Controller\Adminhtml\Comment\MassDelete::class,
            [
                'context' => $this->contextMock,
                'filter' => $this->filterMock,
                'collectionFactory' => $this->collectionFactoryMock
            ]
        );
    }

    public function testMassDeleteAction()
    {
        $deletedCommentsCount = 2;

        $collection = [
            $this->getCommentMock(),
            $this->getCommentMock()
        ];

        $this->collectionFactoryMock->expects($this->once())->method('create')->willReturn($this->commentCollectionMock);

        $this->filterMock->expects($this->once())
            ->method('getCollection')
            ->with($this->commentCollectionMock)
            ->willReturn($this->commentCollectionMock);

        $this->commentCollectionMock->expects($this->once())->method('getSize')->willReturn($deletedCommentsCount);
        $this->commentCollectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($collection));

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('A total of %1 record(s) have been deleted.', $deletedCommentsCount));
        $this->messageManagerMock->expects($this->never())->method('addErrorMessage');

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->massDeleteController->execute());
    }

    /**
     * Create Blog Post Collection Mock
     *
     * @return \OpenTechiz\Blog\Model\ResourceModel\Comment\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCommentMock()
    {
        $commentMock = $this->createPartialMock(\OpenTechiz\Blog\Model\ResourceModel\Comment\Collection::class, ['delete']);
        $commentMock->expects($this->once())->method('delete')->willReturn(true);

        return $commentMock;
    }
}