<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace OpenTechiz\Blog\Test\Unit\Controller\Adminhtml\Post;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SaveTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \OpenTechiz\Blog\Model\PostFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $postFactory;

    /**
     * @var \Magento\Framework\App\RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    /** @var \Magento\Backend\App\Action\Context|\PHPUnit_Framework_MockObject_MockObject */
    protected $contextMock;

    /** @var \Magento\Backend\Model\View\Result\RedirectFactory|\PHPUnit_Framework_MockObject_MockObject */
    protected $resultRedirectFactoryMock;

    /** @var \Magento\Backend\Model\View\Result\Redirect|\PHPUnit_Framework_MockObject_MockObject */
    protected $resultRedirectMock;

    /**
     * @var \Magento\Framework\Message\ManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $messageManagerMock;

    /** @var \OpenTechiz\Blog\Model\Post|\PHPUnit_Framework_MockObject_MockObject $postMock */
    protected $postMock;

    /**
     * @var \OpenTechiz\Blog\Controller\Adminhtml\Post\Save
     */
    protected $saveController;

    /**
     * @var int
     */
    protected $postId = 1;

    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);


        $this->resultRedirectMock = $this->getMockBuilder(\Magento\Backend\Model\View\Result\Redirect::class)
            ->setMethods(['setPath'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultRedirectFactoryMock = $this->getMockBuilder(
            \Magento\Backend\Model\View\Result\RedirectFactory::class
        )->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->resultRedirectFactoryMock->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($this->resultRedirectMock);

        $this->requestMock = $this->getMockBuilder(\Magento\Framework\App\RequestInterface::class)
            ->setMethods(['getParam', 'getPostValue'])
            ->getMockForAbstractClass();

        $this->messageManagerMock = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);

        $this->postFactory = $this->getMockBuilder(\OpenTechiz\Blog\Model\PostFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->postMock = $this->getMockBuilder(\OpenTechiz\Blog\Model\Post::class)
            ->disableOriginalConstructor()
            ->setMethods(['load', 'save'])
            ->getMock();

        $this->contextMock = $this->createMock(\Magento\Backend\App\Action\Context::class);

        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->expects($this->any())->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->contextMock->expects($this->any())
            ->method('getResultRedirectFactory')
            ->willReturn($this->resultRedirectFactoryMock);

        $this->saveController = $objectManager->getObject(
            \OpenTechiz\Blog\Controller\Adminhtml\Post\Save::class,
            [
                'context' => $this->contextMock,
                'postFactory' => $this->postFactory
            ]
        );
    }

    public function testSaveAction()
    {
        $postData = [
            'title' => 'This is Title of post 01',
            'content' => 'This is Content of post 01',
            'url_key' => 'https://facebook.com/luongvuthuan99',
            'is_active' => 1
        ];

        $this->requestMock->expects($this->any())->method('getPostValue')->willReturn($postData);
        $this->requestMock->expects($this->atLeastOnce())
            ->method('getParam')
            ->willReturnMap(
                [
                    ['post_id', null, $this->postId],
                    ['back', null, false],
                ]
            );
        $this->postFactory->expects($this->once())->method('create')->willReturn($this->postMock);

        $this->postMock->expects($this->once())->method('save');

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccess')
            ->willReturn('You saved this Post.');

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->saveController->execute());
    }
}
