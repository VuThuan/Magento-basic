<?php

namespace OpenTechiz\Blog\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\ScopeInterface;
use OpenTechiz\Blog\Helper\SendEmail;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class SendEmailTest extends TestCase
{
    /**
     * @var ScopeConfigInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $scopeConfigMock;

    /**
     * @var TransportBuilder|PHPUnit_Framework_MockObject_MockObject
     */
    private $transportBuilderMock;

    /**
     * @var DataObject
     */
    private $dataObjectMock;

    /**
     * @var Context
     */
    private $contextMock;

    /**
     * @var SendEmail
     */
    private $sendEmailHelper;

    protected function setUp()
    {
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);

        $this->transportBuilderMock = $this->getMockBuilder(TransportBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataObjectMock = $this->getMockBuilder(DataObject::class)
            ->disableOriginalConstructor()
            ->setMethods(['setData'])
            ->getMock();

        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sendEmailHelper = new SendEmail(
            $this->transportBuilderMock,
            $this->scopeConfigMock,
            $this->contextMock
        );
    }

    public function testSendEmail()
    {
        $name = 'LeonaNewer';
        $sendToEmail = 'vuthuan3090@gmail.com';

        $transport = $this->createMock(\Magento\Framework\Mail\TransportInterface::class);

        $senderEmail = $this->scopeConfigMock->expects($this->at(0))
            ->method('getValue')
            ->with('trans_email/ident_general/email', ScopeInterface::SCOPE_STORE)
            ->willReturn('owner@example.com');

        $senderName = $this->scopeConfigMock->expects($this->at(1))
            ->method('getValue')
            ->with('trans_email/ident_general/name', ScopeInterface::SCOPE_STORE)
            ->willReturn('owner');

        $sender = [
            'name' => $senderName,
            'email' => $senderEmail
        ];

        $data['name'] = $name;
        $postObject = $this->dataObjectMock->expects($this->once())
            ->method('setData')
            ->with($data)
            ->will($this->returnSelf());

        $this->transportBuilderMock->expects($this->once())
            ->method('setTemplateIdentifier')
            ->with('blog_comment_notification_email_template')
            ->will($this->returnSelf());

        $this->transportBuilderMock->expects($this->once())
            ->method('setTemplateOptions')
            ->with([
                'area' => 'frontend',
                'store' => 0,
            ])->will($this->returnSelf());

        $this->transportBuilderMock->expects($this->once())
            ->method('setTemplateVars')
            ->with(['data' => $postObject])
            ->will($this->returnSelf());

        $this->transportBuilderMock->expects($this->once())
            ->method('setFrom')
            ->with($sender)
            ->will($this->returnSelf());

        $this->transportBuilderMock->expects($this->once())
            ->method('addTo')
            ->with($sendToEmail)
            ->will($this->returnSelf());

        $this->transportBuilderMock->expects($this->once())
            ->method('setReplyTo')
            ->with($sender['email'])
            ->will($this->returnSelf());

        $this->transportBuilderMock->expects($this->once())
            ->method('getTransport')
            ->willReturn($transport);

        $transport->expects($this->once())
            ->method('sendMessage');

        $this->sendEmailHelper->approvalEmail($sendToEmail, $name);
    }
}
