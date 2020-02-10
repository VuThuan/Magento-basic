<?php

namespace OpenTechiz\Blog\Test\Unit\Ui  \Component\Listing\Column;

use OpenTechiz\Blog\Ui\Component\Listing\Column\CommentActions;
use Magento\Framework\Escaper;

use PHPUnit\Framework\TestCase;

/**
 * Test For OpenTechiz\Blog\Ui\Component\Listing\Column\CommentActions Class
 */
class CommentActionsTest extends TestCase
{
    public function testPrepareItemsByPostId()
    {
        $commentID = 1;
        // Create Mocks and SUT
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        /** @var \PHPUnit_Framework_MockObject_MockObject $urlBuilderMock */
        $urlBuilderMock = $this->getMockBuilder(\Magento\Framework\UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $contextMock = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponent\ContextInterface::class)
            ->getMockForAbstractClass();

        $processor = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponent\Processor::class)
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock->expects($this->never())->method('getProcessor')->willReturn($processor);

        /** @var OpenTechiz\Blog\Ui\Component\Listing\Column\CommentActions $model */
        $model = $objectManager->getObject(
            \OpenTechiz\Blog\Ui\Component\Listing\Column\CommentActions::class,
            [
                'urlBuilder' => $urlBuilderMock,
                'context' => $contextMock,
            ]
        );

        $escaper = $this->getMockBuilder(Escaper::class)
            ->disableOriginalConstructor()
            ->setMethods(['escapeHtml'])
            ->getMock();
        $objectManager->setBackwardCompatibleProperty($model, 'escaper', $escaper);

        // Define test input and expectations
        $title = 'post title';
        $items = [
            'data' => [
                'items' => [
                    [
                        'comment_id' => $commentID,
                        'title' => $title
                    ]
                ]
            ]
        ];
        $name = 'item_name';
        $expectedItems = [
            [
                'comment_id' => $commentID,
                'title' => $title,
                $name => [
                    'edit' => [
                        'href' => 'test/url/edit',
                        'label' => __('Edit'),
                    ],
                    'delete' => [
                        'href' => 'test/url/delete',
                        'label' => __('Delete'),
                        'confirm' => [
                            'comment' => __('Delete %1', $title),
                            'message' => __('Are you sure you want to delete a %1 record?', $title),
                        ]
                    ],
                ],
            ],
        ];

        $escaper->expects(static::once())
            ->method('escapeHtml')
            ->with($title)
            ->willReturn($title);
        
        // Configure mocks and object data
        $urlBuilderMock->expects($this->any())
            ->method('getUrl')
            ->willReturnMap(
                [
                    [
                        CommentActions::BLOG_URL_PATH_EDIT,
                        [
                            'comment_id' => $commentID
                        ],
                        'test/url/edit',
                    ],
                    [
                        CommentActions::BLOG_URL_PATH_DELETE,
                        [
                            'comment_id' => $commentID
                        ],
                        'test/url/delete',
                    ],
                ]
            );
        $model->setName($name);
        $items = $model->prepareDataSource($items);
        // Run test
        $this->assertEquals($expectedItems, $items['data']['items']);
    }
}