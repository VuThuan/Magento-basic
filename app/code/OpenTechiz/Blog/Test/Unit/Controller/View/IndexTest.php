<?php

namespace OpenTechiz\Blog\Test\Unit\Controller\View;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use OpenTechiz\Blog\Controller\View\Index;
use OpenTechiz\Blog\Model\PostFactory;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    /**
     * @var Index
     */
    protected $controller;

    /**
     * @var PostFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $postFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    protected function setUp()
    {
        $this->requestMock = $this->createMock(\Magento\Framework\App\Request\Http::class);

        $this->postFactoryMock = $this->getMockBuilder(PostFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->registryMock = $this->createMock(\Magento\Framework\Registry::class);

        $objectManagerHelper = new ObjectManager($this);
        $this->controller = $objectManagerHelper->getObject(
            Index::class,
            [
                'postFactory' => $this->postFactoryMock,
                'request' => $this->requestMock,
                'registry' => $this->registryMock
            ]
        );
    }

    public function testExecuteResultPost()
    {
        $postID = 1;
        $this->requestMock->expects($this->once())
            ->method('getParams')
            ->willReturnMap([
                'id' => $postID
            ]);

        $pageMock = $this->createMock(\OpenTechiz\Blog\Model\Post::class);

        $this->registryMock->expects($this->once())
            ->method('register')
            ->will($this->returnValueMap([
                ['post_id', $postID]
            ]));
        $this->postFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($pageMock);

        $this->assertNull($this->controller->execute());
    }
}
