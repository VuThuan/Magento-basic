<?php

namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Comment;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\ObjectManager\ObjectManager;
use OpenTechiz\Blog\Controller\Adminhtml\Comment\Delete;
use OpenTechiz\Blog\Model\Comment;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class DeleteTest extends TestCase
{
    /** @var Delete */
    protected $deleteController;

    /** @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager */
    protected $objectManager;

    /** @var Context|PHPUnit_Framework_MockObject_MockObject */
    protected $contextMock;

    /** @var RedirectFactory|PHPUnit_Framework_MockObject_MockObject */
    protected $resultRedirectFactoryMock;

    /** @var Redirect|PHPUnit_Framework_MockObject_MockObject */
    protected $resultRedirectMock;

    /** @var ManagerInterface|PHPUnit_Framework_MockObject_MockObject */
    protected $messageManagerMock;

    /** @var RequestInterface|PHPUnit_Framework_MockObject_MockObject */
    protected $requestMock;

    /** @var ObjectManager|PHPUnit_Framework_MockObject_MockObject */
    protected $objectManagerMock;

    /** @var Comment|PHPUnit_Framework_MockObject_MockObject $commentMock */
    protected $commentMock;

    /** @var string */
    protected $title = 'This is the title of the comment.';

    /** @var int */
    protected $commentID = 1;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->messageManagerMock = $this->createMock(ManagerInterface::class);

        $this->requestMock = $this->getMockForAbstractClass(
            RequestInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['getParam']
        );

        $this->commentMock = $this->getMockBuilder(Comment::class)
            ->disableOriginalConstructor()
            ->setMethods(['load', 'delete', 'getTitle'])
            ->getMock();

        $this->objectManagerMock = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->resultRedirectMock = $this->getMockBuilder(Redirect::class)
            ->setMethods(['setPath'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultRedirectFactoryMock = $this->getMockBuilder(
            RedirectFactory::class
        )->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->resultRedirectFactoryMock->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($this->resultRedirectMock);

        $this->contextMock = $this->createMock(Context::class);

        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->expects($this->any())->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->contextMock->expects($this->any())->method('getObjectManager')->willReturn($this->objectManagerMock);
        $this->contextMock->expects($this->any())
            ->method('getResultRedirectFactory')
            ->willReturn($this->resultRedirectFactoryMock);

        $this->deleteController = $this->objectManager->getObject(
            Delete::class,
            [
                'context' => $this->contextMock,
            ]
        );
    }

    public function testDeleteAction()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->willReturn($this->commentID);

        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with(Comment::class)
            ->willReturn($this->commentMock);

        $this->commentMock->expects($this->once())
            ->method('load')
            ->with($this->commentID);
        $this->commentMock->expects($this->once())
            ->method('getTitle')
            ->willReturn($this->title);
        $this->commentMock->expects($this->once())
            ->method('delete');

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccessMessage')
            ->with(__('The Comment has been deleted.'));
        $this->messageManagerMock->expects($this->never())
            ->method('addErrorMessage');

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->deleteController->execute());
    }

    public function testDeleteActionNoId()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->willReturn(null);

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with(__('We can\'t find a comment to delete.'));
        $this->messageManagerMock->expects($this->never())
            ->method('addSuccessMessage');

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->deleteController->execute());
    }

    public function testDeleteActionThrowsException()
    {
        $errorMsg = 'Can\'t delete the comment';

        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->willReturn($this->commentID);

        $this->objectManagerMock->expects($this->once())
            ->method('create')
            ->with(Comment::class)
            ->willReturn($this->commentMock);

        $this->commentMock->expects($this->once())
            ->method('load')
            ->with($this->commentID);
        $this->commentMock->expects($this->once())
            ->method('getTitle')
            ->willReturn($this->title);
        $this->commentMock->expects($this->once())
            ->method('delete')
            ->willThrowException(new Exception(__($errorMsg)));

        $this->messageManagerMock->expects($this->once())
            ->method('addErrorMessage')
            ->with($errorMsg);
        $this->messageManagerMock->expects($this->never())
            ->method('addSuccessMessage');

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/edit', ['comment_id' => $this->commentID])
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->deleteController->execute());
    }
}
