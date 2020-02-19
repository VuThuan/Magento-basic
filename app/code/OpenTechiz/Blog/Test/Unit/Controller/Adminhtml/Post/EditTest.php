<?php

namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Post;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\ObjectManager\ObjectManager;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Title;
use Magento\Framework\View\Result\PageFactory;
use OpenTechiz\Blog\Controller\Adminhtml\Post\Edit;
use OpenTechiz\Blog\Model\Post;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test for OpenTechiz\Blog\Controller\Adminhtml\Post\Edit Class
 */
class EditTest extends TestCase
{
    /**
     * @var Edit
     */
    protected $editController;

    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var Context|PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var RedirectFactory|PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultRedirectFactoryMock;

    /**
     * @var Redirect|PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultRedirectMock;

    /**
     * @var ManagerInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $messageManagerMock;

    /**
     * @var RequestInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    /**
     * @var Post|PHPUnit_Framework_MockObject_MockObject
     */
    protected $postMock;

    /**
     * @var ObjectManager|PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManagerMock;

    /**
     * @var Registry|PHPUnit_Framework_MockObject_MockObject
     */
    protected $coreRegistryMock;

    /**
     * @var PageFactory|PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultPageFactoryMock;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->messageManagerMock = $this->createMock(ManagerInterface::class);
        $this->coreRegistryMock = $this->createMock(Registry::class);

        $this->postMock = $this->getMockBuilder(Post::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManagerMock = $this->getMockBuilder(ObjectManager::class)
            ->setMethods(['create', 'get'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with(Post::class)
            ->willReturn($this->postMock);

        $this->resultRedirectMock = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultRedirectFactoryMock = $this->getMockBuilder(
            RedirectFactory::class
        )->disableOriginalConstructor()->getMock();

        $this->resultPageFactoryMock = $this->createMock(PageFactory::class);

        $this->requestMock = $this->getMockForAbstractClass(
            RequestInterface::class,
            [],
            '',
            false,
            true,
            true,
            []
        );

        $this->contextMock = $this->createMock(Context::class);
        $this->contextMock->expects($this->once())->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->expects($this->once())->method('getObjectManager')->willReturn($this->objectManagerMock);
        $this->contextMock->expects($this->once())->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->contextMock->expects($this->once())
            ->method('getResultRedirectFactory')
            ->willReturn($this->resultRedirectFactoryMock);

        $this->editController = $this->objectManager->getObject(
            Edit::class,
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

        $resultpostMock = $this->createMock(Page::class);

        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultpostMock);

        $titleMock = $this->createMock(Title::class);
        $titleMock->expects($this->at(0))->method('prepend')->with(__('Blog Posts'));
        $titleMock->expects($this->at(1))->method('prepend')->with($this->getTitle());
        $pageConfigMock = $this->createMock(Config::class);
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
     * @return Phrase|string
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