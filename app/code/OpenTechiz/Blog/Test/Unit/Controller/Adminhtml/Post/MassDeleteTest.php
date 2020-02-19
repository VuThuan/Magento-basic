<?php

namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Post;

use ArrayIterator;
use OpenTechiz\Blog\Controller\Adminhtml\Post\MassDelete;
use OpenTechiz\Blog\Model\ResourceModel\Post\Collection;
use OpenTechiz\Blog\Model\ResourceModel\Post\CollectionFactory;
use OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\AbstractMassActionTest;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test for OpenTechiz\Blog\Controller\Adminhtml\Post\MassDelete Class
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
    protected $pageCollectionMock;

    protected function setUp()
    {
        parent::setUp();

        $this->collectionFactoryMock = $this->createPartialMock(
            CollectionFactory::class,
            ['create']
        );

        $this->pageCollectionMock =
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
        $deletedPagesCount = 2;

        $collection = [
            $this->getPageMock(),
            $this->getPageMock()
        ];

        $this->collectionFactoryMock->expects($this->once())->method('create')->willReturn($this->pageCollectionMock);

        $this->filterMock->expects($this->once())
            ->method('getCollection')
            ->with($this->pageCollectionMock)
            ->willReturn($this->pageCollectionMock);

        $this->pageCollectionMock->expects($this->once())->method('getSize')->willReturn($deletedPagesCount);
        $this->pageCollectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new ArrayIterator($collection));

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('A total of %1 record(s) have been deleted.', $deletedPagesCount));
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
    protected function getPageMock()
    {
        $pageMock = $this->createPartialMock(Collection::class, ['delete']);
        $pageMock->expects($this->once())->method('delete')->willReturn(true);

        return $pageMock;
    }
}