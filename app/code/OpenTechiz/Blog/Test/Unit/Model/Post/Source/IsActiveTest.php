<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace OpenTechiz\Blog\Test\Unit\Model\Post\Source;

use OpenTechiz\Blog\Model\Post;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class IsActiveTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Post|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $blogPostMock;

    /**
     * @var ObjectManager
     */
    protected $objectManagerHelper;

    /**
     * @var Post\Source\IsActive
     */
    protected $object;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->objectManagerHelper = new ObjectManager($this);
        $this->blogPostMock = $this->getMockBuilder(\OpenTechiz\Blog\Model\Post::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAvailableStatuses'])
            ->getMock();

        $this->object = $this->objectManagerHelper->getObject($this->getSourceClassName(), [
            'blogPost' => $this->blogPostMock,
        ]);
    }

    /**
     * @return string
     */
    protected function getSourceClassName()
    {
        return \OpenTechiz\Blog\Model\Post\Source\IsActive::class;
    }

    /**
     * @param array $availableStatuses
     * @param array $expected
     * @return void
     * @dataProvider getAvailableStatusesDataProvider
     */
    public function testToOptionArray(array $availableStatuses, array $expected)
    {
        $this->blogPostMock->expects($this->once())
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
                ['testStatus' => 'testValue'],
                [['label' => 'testValue', 'value' => 'testStatus']]
            ],
        ];
    }
}
