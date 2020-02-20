<?php

namespace OpenTechiz\Blog\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use OpenTechiz\Blog\Helper\SendEmail;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class SendEmailTest extends TestCase
{
    /**
     * @var ObjectManagerHelper
     */
    private $objectManagerHelper;

    /**
     * @var ScopeConfigInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $configMock;

    /**
     * @var TransportBuilder|PHPUnit_Framework_MockObject_MockObject
     */
    private $transportBuilderMock;

    /**
     * @var SendEmail
     */
    private $sendEmailHelper;

    protected function setUp()
    {
        $this->configMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->transportBuilderMock = $this->getMockBuilder(
            TransportBuilder::class
        )->disableOriginalConstructor(
        )->getMock();

        $this->objectManagerHelper = new ObjectManagerHelper($this);

        $this->sendEmailHelper = $this->objectManagerHelper->getObject(
            SendEmail::class,
            [
                'config' => $this->configMock,
                'transportBuilder' => $this->transportBuilderMock
        ]
        );
    }

    public function testSendEmail()
    {
    }
}
