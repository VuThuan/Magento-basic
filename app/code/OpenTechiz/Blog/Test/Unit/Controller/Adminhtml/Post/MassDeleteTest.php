<?php

namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Post;

use OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\AbstractMassActionTest;

/**
 * Test for OpenTechiz\Blog\Controller\Adminhtml\Post\MassDelete Class
 */
class MassDeleteTest extends AbstractMassActionTest
{
    /**
     * @var \OpenTechiz\Blog\Controller\Adminhtml\Post\MassDelete
     */
    protected $massDeleteController;

    /**
     * @var \OpenTechiz\Blog\Model\ResourceModel\Post\CollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $collectionFactoryMock;

    /**
     * @var \OpenTechiz\Blog\Model\ResourceModel\Post\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $pageCollectionMock;

    protected function setUp()
    {
        parent::setUp();

        $this->collectionFactoryMock = $this->createPartialMock(
            \OpenTechiz\Blog\Model\ResourceModel\Post\CollectionFactory::class,
            ['create']
        );

        $this->pageCollectionMock =
            $this->createMock(\OpenTechiz\Blog\Model\ResourceModel\Post\Collection::class);

        $this->massDeleteController = $this->objectManager->getObject(
            \OpenTechiz\Blog\Controller\Adminhtml\Post\MassDelete::class,
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
            ->willReturn(new \ArrayIterator($collection));

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
     * @return \OpenTechiz\Blog\Model\ResourceModel\Post\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPageMock()
    {
        $pageMock = $this->createPartialMock(\OpenTechiz\Blog\Model\ResourceModel\Post\Collection::class, ['delete']);
        $pageMock->expects($this->once())->method('delete')->willReturn(true);

        return $pageMock;
    }
}