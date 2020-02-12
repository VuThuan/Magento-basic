<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Comment;

use \OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\AbstractMassActionTest;

class MassDisableTest extends AbstractMassActionTest
{
    /**
     * @var \OpenTechiz\Blog\Controller\Adminhtml\Comment\MassDisable
     */
    protected $massDisableController;

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

        $this->commentCollectionMock = $this->createMock(\OpenTechiz\Blog\Model\ResourceModel\Comment\Collection::class);

        $this->massDisableController = $this->objectManager->getObject(
            \OpenTechiz\Blog\Controller\Adminhtml\Comment\MassDisable::class,
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
            $this->getCommentMock(),
            $this->getCommentMock()
        ];

        $this->collectionFactoryMock->expects($this->once())->method('create')->willReturn($this->commentCollectionMock);

        $this->filterMock->expects($this->once())
            ->method('getCollection')
            ->with($this->commentCollectionMock)
            ->willReturn($this->commentCollectionMock);

        $this->commentCollectionMock->expects($this->once())->method('getSize')->willReturn($disabledPagesCount);
        $this->commentCollectionMock->expects($this->once())
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
     * @return \\OpenTechiz\Blog\Model\ResourceModel\Comment\Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getCommentMock()
    {
        $commentMock = $this->createPartialMock(
            \OpenTechiz\Blog\Model\ResourceModel\Comment\Collection::class,
            ['setIsActive', 'save']
        );
        $commentMock->expects($this->once())->method('setIsActive')->with(false)->willReturn(true);
        $commentMock->expects($this->once())->method('save')->willReturn(true);

        return $commentMock;
    }
}
