<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Post;

use \OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\AbstractMassActionTest;

class MassDisableTest extends AbstractMassActionTest
{
    /**
     * @var \OpenTechiz\Blog\Controller\Adminhtml\Post\MassDisable
     */
    protected $massDisableController;

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

        $this->pageCollectionMock = $this->createMock(\OpenTechiz\Blog\Model\ResourceModel\Post\Collection::class);

        $this->massDisableController = $this->objectManager->getObject(
            \OpenTechiz\Blog\Controller\Adminhtml\Post\MassDisable::class,
            [
                'context' => $this->contextMock,
                'filter' => $this->filterMock,
                'collectionFactory' => $this->collectionFactoryMock
            ]
        );
    }

    public function testMassDisableAction()
    {
        $disabledPagesCount = 2;

        $collection = [
            $this->getPageMock(),
            $this->getPageMock()
        ];

        $this->collectionFactoryMock->expects($this->once())->method('create')->willReturn($this->pageCollectionMock);

        $this->filterMock->expects($this->once())
            ->method('getCollection')
            ->with($this->pageCollectionMock)
            ->willReturn($this->pageCollectionMock);

        $this->pageCollectionMock->expects($this->once())->method('getSize')->willReturn($disabledPagesCount);
        $this->pageCollectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($collection));

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccess')
            ->with(__('A total of %1 record(s) have been disabled.', $disabledPagesCount));
        $this->messageManagerMock->expects($this->never())->method('addErrorMessage');

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->massDisableController->execute());
    }

    /**
     * Create Cms Post Collection Mock
     *
     * @return \\OpenTechiz\Blog\Model\ResourceModel\Post\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPageMock()
    {
        $pageMock = $this->createPartialMock(
            \OpenTechiz\Blog\Model\ResourceModel\Post\Collection::class,
            ['setIsActive', 'save']
        );
        $pageMock->expects($this->once())->method('setIsActive')->with(false)->willReturn(true);
        $pageMock->expects($this->once())->method('save')->willReturn(true);

        return $pageMock;
    }
}
