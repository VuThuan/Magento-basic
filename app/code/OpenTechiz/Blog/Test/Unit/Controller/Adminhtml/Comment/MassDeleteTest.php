<?php

namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Comment;

use ArrayIterator;
use OpenTechiz\Blog\Controller\Adminhtml\Comment\MassDelete;
use OpenTechiz\Blog\Model\ResourceModel\Comment\Collection;
use OpenTechiz\Blog\Model\ResourceModel\Comment\CollectionFactory;
use OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\AbstractMassActionTest;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test for OpenTechiz\Blog\Controller\Adminhtml\Comment\MassDelete Class
 */
class MassDeleteTest extends AbstractMassActionTest
{
    /**
     * @var MassDelete
     */
    protected $massDeleteController;

    /**
     * @var CollectionFactory|PHPUnit_Framework_MockObject_MockObject
     */
    protected $collectionFactoryMock;

    /**
     * @var Collection|PHPUnit_Framework_MockObject_MockObject
     */
    protected $commentCollectionMock;

    protected function setUp()
    {
        parent::setUp();

        $this->collectionFactoryMock = $this->createPartialMock(
            CollectionFactory::class,
            ['create']
        );

        $this->commentCollectionMock =
            $this->createMock(Collection::class);

        $this->massDeleteController = $this->objectManager->getObject(
            MassDelete::class,
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
            ->willReturn(new ArrayIterator($collection));

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
     * @return Collection|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCommentMock()
    {
        $commentMock = $this->createPartialMock(Collection::class, ['delete']);
        $commentMock->expects($this->once())->method('delete')->willReturn(true);

        return $commentMock;
    }
}