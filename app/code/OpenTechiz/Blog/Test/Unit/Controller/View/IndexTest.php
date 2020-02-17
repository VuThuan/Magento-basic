<?php

namespace OpenTechiz\Blog\Test\Unit\Controller\View;

use OpenTechiz\Blog\Model\PostFactory;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    /**
     * @var \OpenTechiz\Blog\Controller\View\Index
     */
    protected $controller;

    /**
     * @var \OpenTechiz\Blog\Model\PostFactory|\PHPUnit_Framework_MockObject_MockObject
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

        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->controller = $objectManagerHelper->getObject(
            \OpenTechiz\Blog\Controller\View\Index::class,
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
        $this->postFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($pageMock);
        
        $this->assertSame($pageMock, $this->controller->execute());
    }
}