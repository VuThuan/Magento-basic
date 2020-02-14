<?php

namespace OpenTechiz\Blog\Test\Unit\Controller;

use OpenTechiz\Blog\Model\PostFactory;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    /**
     * @var \OpenTechiz\Blog\Controller\Router
     */
    private $router;

    /**
     * @var \OpenTechiz\Blog\Model\PostFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $postFactoryMock;

    /**
     * @var \Magento\Framework\App\ActionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $actionFactoryMock;
    
    public function setUp()
    {
        $this->postFactoryMock = $this->getMockBuilder(PostFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        
        $this->actionFactoryMock = $this->getMockBuilder(\Magento\Framework\App\ActionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->router = $objectManagerHelper->getObject(
            \OpenTechiz\Blog\Controller\Router::class,
            [
                'postFactory' => $this->postFactoryMock,
                'actionFactory' => $this->actionFactoryMock,
            ]
        );
    }

    public function testMatchPostControllerRouterMatchBeforeEventParams()
    {
        $identifier = '/test';
        $trimmedIdentifier = 'test';
        $postId = 1;

        /** @var \Magento\Framework\App\RequestInterface|\PHPUnit_Framework_MockObject_MockObject $requestMock */
        $requestMock = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->setMethods([
                'getPathInfo',
                'setModuleName',
                'setControllerName',
                'setActionName',
                'setParam',
                'setAlias',
            ])
            ->getMockForAbstractClass();
        
            $requestMock->expects($this->once())
            ->method('getPathInfo')
            ->willReturn($identifier);
        $requestMock->expects($this->once())
            ->method('setModuleName')
            ->with('blog')
            ->willReturnSelf();
        $requestMock->expects($this->once())
            ->method('setControllerName')
            ->with('view')
            ->willReturnSelf();
        $requestMock->expects($this->once())
            ->method('setActionName')
            ->with('index')
            ->willReturnSelf();
        $requestMock->expects($this->once())
            ->method('setParam')
            ->with('post_id', $postId)
            ->willReturnSelf();
        $requestMock->expects($this->once())
            ->method('setAlias')
            ->with(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $trimmedIdentifier)
            ->willReturnSelf();

        $pageMock = $this->getMockBuilder(\OpenTechiz\Blog\Model\Post::class)
            ->disableOriginalConstructor()
            ->getMock();
        $pageMock->expects($this->once())
            ->method('checkUrlKey')
            ->with($trimmedIdentifier)
            ->willReturn($postId);

        $this->postFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($pageMock);

        $actionMock = $this->getMockBuilder(\Magento\Framework\App\ActionInterface::class)
            ->getMockForAbstractClass();

        $this->actionFactoryMock->expects($this->once())
            ->method('create')
            ->with(\Magento\Framework\App\Action\Forward::class)
            ->willReturn($actionMock);  

        $this->assertEquals($actionMock, $this->router->match($requestMock));
    }
}