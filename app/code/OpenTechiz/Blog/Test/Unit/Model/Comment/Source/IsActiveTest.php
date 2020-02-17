<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OpenTechiz\Blog\Test\Unit\Model\Comment\Source;

use OpenTechiz\Blog\Model\Comment;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class IsActiveTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Comment|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $blogCommentMock;

    /**
     * @var ObjectManager
     */
    protected $objectManagerHelper;

    /**
     * @var Page\Source\IsActive
     */
    protected $object;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->objectManagerHelper = new ObjectManager($this);
        $this->blogCommentMock = $this->getMockBuilder(\OpenTechiz\Blog\Model\Comment::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAvailableStatuses'])
            ->getMock();

        $this->object = $this->objectManagerHelper->getObject($this->getSourceClassName(), [
            'blogComment' => $this->blogCommentMock,
        ]);
    }

    /**
     * @return string
     */
    protected function getSourceClassName()
    {
        return \OpenTechiz\Blog\Model\Comment\Source\IsActive::class;
    }

    /**
     * @param array $availableStatuses
     * @param array $expected
     * @return void
     * @dataProvider getAvailableStatusesDataProvider
     */
    public function testToOptionArray(array $availableStatuses, array $expected)
    {
        $this->blogCommentMock->expects($this->once())
            ->method('getAvailableStatuses')
            ->willReturn($availableStatuses);

        $this->assertSame($expected, $this->object->toOptionArray());
    }

    /**
     * @return array
     */
    public function getAvailableStatusesDataProvider()
    {
        return [
            [
                [],
                [],
            ],
            [
                ['testStatus' => 'testValue'],
                [['label' => 'testValue', 'value' => 'testStatus']],
            ],
        ];
    }
}
