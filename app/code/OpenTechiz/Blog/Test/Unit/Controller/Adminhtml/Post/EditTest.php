<?php

namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Post;

use PHPUnit\Framework\TestCase;

/**
 * Test for OpenTechiz\Blog\Controller\Adminhtml\Post\Edit Class
 */
class EditTest extends TestCase
{
    /**
     * @var \OpenTechiz\Blog\Controller\Adminhtml\Post\Edit
     */
    protected $editController;

    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Backend\App\Action\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var \Magento\Backend\Model\View\Result\RedirectFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultRedirectFactoryMock;

    /**
     * @var \Magento\Backend\Model\View\Result\Redirect|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultRedirectMock;

    /**
     * @var \Magento\Framework\Message\ManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $messageManagerMock;

    /**
     * @var \Magento\Framework\App\RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    /**
     * @var \OpenTechiz\Blog\Model\Post|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $postMock;

    /**
     * @var \Magento\Framework\ObjectManager\ObjectManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManagerMock;

    /**
     * @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $coreRegistryMock;

    /**
     * @var \Magento\Framework\View\Result\PageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultPageFactoryMock;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->messageManagerMock = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);
        $this->coreRegistryMock = $this->createMock(\Magento\Framework\Registry::class);

        $this->postMock = $this->getMockBuilder(\OpenTechiz\Blog\Model\Post::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManagerMock = $this->getMockBuilder(\Magento\Framework\ObjectManager\ObjectManager::class)
            ->setMethods(['create', 'get'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with(\OpenTechiz\Blog\Model\Post::class)
            ->willReturn($this->postMock);

        $this->resultRedirectMock = $this->getMockBuilder(\Magento\Backend\Model\View\Result\Redirect::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultRedirectFactoryMock = $this->getMockBuilder(
            \Magento\Backend\Model\View\Result\RedirectFactory::class
        )->disableOriginalConstructor()->getMock();

        $this->resultPageFactoryMock = $this->createMock(\Magento\Framework\View\Result\PageFactory::class);

        $this->requestMock = $this->getMockForAbstractClass(
            \Magento\Framework\App\RequestInterface::class,
            [],
            '',
            false,
            true,
            true,
            []
        );

        $this->contextMock = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->contextMock->expects($this->once())->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->expects($this->once())->method('getObjectManager')->willReturn($this->objectManagerMock);
        $this->contextMock->expects($this->once())->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->contextMock->expects($this->once())
            ->method('getResultRedirectFactory')
            ->willReturn($this->resultRedirectFactoryMock);

        $this->editController = $this->objectManager->getObject(
            \OpenTechiz\Blog\Controller\Adminhtml\Post\Edit::class,
            [
                'context' => $this->contextMock,
                'resultPageFactory' => $this->resultPageFactoryMock,
                'registry' => $this->coreRegistryMock,
            ]
        );
    }

    public function testEditActionPageNoExists()
    {
        $postId = 1;

        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('post_id')
            ->willReturn($postId);

        $this->postMock->expects($this->once())
            ->method('load')
            ->with($postId);
        $this->postMock->expects($this->once())
            ->method('getId')
            ->willReturn(null);

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with(__('This post no longer exists.'));

        $this->resultRedirectFactoryMock->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($this->resultRedirectMock);

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->editController->execute());
    }

    /**
     * @param int $postId
     * @param string $label
     * @param string $title
     * @dataProvider editActionData
     */
    public function testEditAction($postId, $label, $title)
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('post_id')
            ->willReturn($postId);

        $this->postMock->expects($this->any())
            ->method('load')
            ->with($postId);
        $this->postMock->expects($this->any())
            ->method('getId')
            ->willReturn($postId);
        $this->postMock->expects($this->any())
            ->method('getTitle')
            ->willReturn('Test title');

        $this->coreRegistryMock->expects($this->once())
            ->method('register')
            ->with('blog_post', $this->postMock);

        $resultpostMock = $this->createMock(\Magento\Backend\Model\View\Result\Page::class);

        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultpostMock);

        $titleMock = $this->createMock(\Magento\Framework\View\Page\Title::class);
        $titleMock->expects($this->at(0))->method('prepend')->with(__('Blog Posts'));
        $titleMock->expects($this->at(1))->method('prepend')->with($this->getTitle());
        $pageConfigMock = $this->createMock(\Magento\Framework\View\Page\Config::class);
        $pageConfigMock->expects($this->exactly(2))->method('getTitle')->willReturn($titleMock);

        $resultpostMock->expects($this->once())
            ->method('setActiveMenu')
            ->willReturnSelf();
        $resultpostMock->expects($this->any())
            ->method('addBreadcrumb')
            ->willReturnSelf();
        $resultpostMock->expects($this->at(3))
            ->method('addBreadcrumb')
            ->with(__($label), __($title))
            ->willReturnSelf();
        $resultpostMock->expects($this->exactly(2))
            ->method('getConfig')
            ->willReturn($pageConfigMock);

        $this->assertSame($resultpostMock, $this->editController->execute());
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    protected function getTitle()
    {
        return $this->postMock->getId() ? $this->postMock->getTitle() : __('New Blog Posts');
    }

    /**
     * @return array
     */
    public function editActionData()
    {
        return [
            [null, 'New Blog Posts', 'New Blog Posts'],
            [2, 'Edit Blog Posts', 'Edit Blog Posts']
        ];
    }
}