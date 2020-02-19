<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Post;

use ArrayIterator;
use OpenTechiz\Blog\Controller\Adminhtml\Post\MassEnable;
use OpenTechiz\Blog\Model\ResourceModel\Post\Collection;
use OpenTechiz\Blog\Model\ResourceModel\Post\CollectionFactory;
use OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\AbstractMassActionTest;
use PHPUnit_Framework_MockObject_MockObject;

class MassEnableTest extends AbstractMassActionTest
{
    /**
     * @var MassEnable
     */
    protected $massEnableController;

    /**
     * @var CollectionFactory|PHPUnit_Framework_MockObject_MockObject
     */
    protected $collectionFactoryMock;

    /**
     * @var Collection|PHPUnit_Framework_MockObject_MockObject
     */
    protected $postCollectionMock;

    protected function setUp()
    {
        parent::setUp();

        $this->collectionFactoryMock = $this->createPartialMock(
            CollectionFactory::class,
            ['create']
        );

        $this->postCollectionMock = $this->createMock(Collection::class);

        $this->massEnableController = $this->objectManager->getObject(
            MassEnable::class,
            [
                'context' => $this->contextMock,
                'filter' => $this->filterMock,
                'collectionFactory' => $this->collectionFactoryMock
            ]
        );
    }

    public function testMassEnableAction()
    {
        $enabledPostsCount = 2;

        $collection = [
            $this->getPostMock(),
            $this->getPostMock()
        ];

        $this->collectionFactoryMock->expects($this->once())->method('create')->willReturn($this->postCollectionMock);

        $this->filterMock->expects($this->once())
            ->method('getCollection')
            ->with($this->postCollectionMock)
            ->willReturn($this->postCollectionMock);

        $this->postCollectionMock->expects($this->once())->method('getSize')->willReturn($enabledPostsCount);
        $this->postCollectionMock->expects($this->once())
            ->method('getIterator')
            ->willReturn(new ArrayIterator($collection));

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('A total of %1 record(s) have been enabled.', $enabledPostsCount));
        $this->messageManagerMock->expects($this->never())->method('addErrorMessage');

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->massEnableController->execute());
    }

    /**
     * Create Blog Post Collection Mock
     *
     * @return Collection|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPostMock()
    {
        $postMock = $this->createPartialMock(
            Collection::class,
            ['setIsActive', 'save']
        );
        $postMock->expects($this->once())->method('setIsActive')->with(true)->willReturn(true);
        $postMock->expects($this->once())->method('save')->willReturn(true);

        return $postMock;
    }
}